<?php

namespace App\Http\Controllers;

use App\Models\PembayaranSpp;
use App\Models\Siswa;
use App\Models\TarifSpp;
use App\Services\KwitansiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SppController extends Controller
{
    // CATATAN: middleware role sudah ditangani di web.php — tidak perlu constructor.

    // =========================================================================
    // INDEX — dashboard riwayat pembayaran per periode
    // =========================================================================

    public function index(Request $request)
    {
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        $query = PembayaranSpp::with('siswa', 'dicatatOleh')->periodik($bulan, $tahun);

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->whereHas('siswa', fn($q) =>
                $q->where('nama_lengkap', 'like', "%{$cari}%")
                  ->orWhere('nis', 'like', "%{$cari}%")
            );
        }
        if ($request->filled('kelompok')) {
            $query->whereHas('siswa', fn($q) => $q->where('kelompok', $request->kelompok));
        }

        $pembayaran     = $query->orderByDesc('tanggal_bayar')->paginate(20)->withQueryString();
        $tarif          = TarifSpp::aktif($tahun);
        $totalPemasukan = PembayaranSpp::periodik($bulan, $tahun)->sum('total');
        $totalBayar     = PembayaranSpp::periodik($bulan, $tahun)->distinct('siswa_id')->count('siswa_id');
        $totalSiswa     = Siswa::where('aktif', true)->count();
        $tunggakan      = max(0, $totalSiswa - $totalBayar);
        $kelompokList   = Siswa::where('aktif', true)->distinct()->orderBy('kelompok')->pluck('kelompok');

        return view('spp.index', compact(
            'pembayaran', 'bulan', 'tahun', 'tarif',
            'totalPemasukan', 'totalBayar', 'totalSiswa', 'tunggakan', 'kelompokList'
        ));
    }

    // =========================================================================
    // KONFIRMASI — halaman utama kerja bendahara
    // =========================================================================

    public function konfirmasi(Request $request)
    {
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);
        $tarif = TarifSpp::aktif($tahun);

        $query = Siswa::where('aktif', true)
            ->with(['pembayaranSpp' => fn($q) => $q->where('bulan', $bulan)->where('tahun', $tahun)]);

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(fn($q) =>
                $q->where('nama_lengkap', 'like', "%{$cari}%")
                  ->orWhere('nis', 'like', "%{$cari}%")
            );
        }
        if ($request->filled('kelompok')) {
            $query->where('kelompok', $request->kelompok);
        }
        if ($request->filled('status')) {
            $sudahBayarIds = PembayaranSpp::periodik($bulan, $tahun)->pluck('siswa_id');
            match ($request->status) {
                'lunas' => $query->whereIn('id', $sudahBayarIds),
                'belum' => $query->whereNotIn('id', $sudahBayarIds),
                default => null,
            };
        }

        $siswaList    = $query->orderBy('kelompok')->orderBy('nama_lengkap')->paginate(25)->withQueryString();
        $totalLunas   = PembayaranSpp::periodik($bulan, $tahun)->distinct('siswa_id')->count('siswa_id');
        $totalSiswa   = Siswa::where('aktif', true)->count();
        $totalBelum   = max(0, $totalSiswa - $totalLunas);
        $kelompokList = Siswa::where('aktif', true)->distinct()->orderBy('kelompok')->pluck('kelompok');

        return view('spp.konfirmasi', compact(
            'siswaList', 'bulan', 'tahun', 'tarif',
            'totalLunas', 'totalBelum', 'totalSiswa', 'kelompokList'
        ));
    }

    // =========================================================================
    // KONFIRMASI SATU — 1 siswa → redirect ke kwitansi
    // =========================================================================

    public function konfirmasiSatu(Request $request)
    {
        $v = $request->validate([
            'siswa_id'      => 'required|exists:siswa,id',
            'bulan'         => 'required|integer|between:1,12',
            'tahun'         => 'required|integer|min:2020',
            'tanggal_bayar' => 'required|date|before_or_equal:today',
        ]);

        $sudah = PembayaranSpp::where('siswa_id', $v['siswa_id'])
            ->where('bulan', $v['bulan'])->where('tahun', $v['tahun'])->exists();

        if ($sudah) {
            return back()->with('error', 'Siswa ini sudah tercatat lunas untuk periode tersebut.');
        }

        $tarif = TarifSpp::aktif((int) $v['tahun']);

        // Model booted() otomatis isi total & no_kwitansi
        $spp = PembayaranSpp::create([
            'siswa_id'           => $v['siswa_id'],
            'bulan'              => $v['bulan'],
            'tahun'              => $v['tahun'],
            'tanggal_bayar'      => $v['tanggal_bayar'],
            'nominal_spp'        => $tarif->nominal_spp,
            'nominal_kebersihan' => $tarif->nominal_kebersihan,
            'dicatat_oleh'       => Auth::id(),
        ]);

        return redirect()->route('spp.kwitansi', $spp)->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    // =========================================================================
    // KONFIRMASI MASSAL — banyak siswa sekaligus → kembali ke konfirmasi
    // =========================================================================

    public function konfirmasiMassal(Request $request)
    {
        $request->validate([
            'siswa_ids'     => 'required|array|min:1',
            'siswa_ids.*'   => 'exists:siswa,id',
            'bulan'         => 'required|integer|between:1,12',
            'tahun'         => 'required|integer|min:2020',
            'tanggal_bayar' => 'required|date|before_or_equal:today',
        ]);

        $bulan       = (int) $request->bulan;
        $tahun       = (int) $request->tahun;
        $tarif       = TarifSpp::aktif($tahun);
        $dicatatOleh = Auth::id();

        $sudahIds  = PembayaranSpp::periodik($bulan, $tahun)
            ->whereIn('siswa_id', $request->siswa_ids)->pluck('siswa_id')->toArray();
        $siswaBaru = array_values(array_diff($request->siswa_ids, $sudahIds));

        if (empty($siswaBaru)) {
            return back()->with('error', 'Semua siswa yang dipilih sudah tercatat lunas.');
        }

        DB::transaction(function () use ($siswaBaru, $bulan, $tahun, $request, $tarif, $dicatatOleh) {
            $now = now();
            foreach ($siswaBaru as $siswaId) {
                PembayaranSpp::insert([[
                    'siswa_id'           => $siswaId,
                    'bulan'              => $bulan,
                    'tahun'              => $tahun,
                    'tanggal_bayar'      => $request->tanggal_bayar,
                    'nominal_spp'        => $tarif->nominal_spp,
                    'nominal_kebersihan' => $tarif->nominal_kebersihan,
                    'total'              => $tarif->total,
                    'dicatat_oleh'       => $dicatatOleh,
                    'no_kwitansi'        => KwitansiService::generateNomor(),
                    'created_at'         => $now,
                    'updated_at'         => $now,
                ]]);
            }
        });

        $pesan = count($siswaBaru) . ' siswa berhasil dikonfirmasi.';
        if (count($sudahIds) > 0) $pesan .= ' (' . count($sudahIds) . ' dilewati, sudah lunas.)';

        return redirect()->route('spp.konfirmasi', ['bulan' => $bulan, 'tahun' => $tahun])->with('success', $pesan);
    }

    // =========================================================================
    // TARIF — admin only (dijaga di web.php)
    // =========================================================================

    public function tarifIndex()
    {
        $tarifList = TarifSpp::with('dibuatOleh')->orderByDesc('tahun_berlaku')->get();
        return view('spp.tarif', compact('tarifList'));
    }

    public function tarifStore(Request $request)
    {
        $v = $request->validate([
            'nominal_spp'        => 'required|integer|min:0',
            'nominal_kebersihan' => 'required|integer|min:0',
            'tahun_berlaku'      => 'required|integer|min:2020',
            'keterangan'         => 'nullable|string|max:200',
        ]);

        TarifSpp::updateOrCreate(
            ['tahun_berlaku' => $v['tahun_berlaku']],
            [...$v, 'dibuat_oleh' => Auth::id()]
        );

        return back()->with('success', "Tarif tahun {$v['tahun_berlaku']} berhasil disimpan.");
    }

    // =========================================================================
    // BACKWARD-COMPAT — /spp/input lama redirect ke konfirmasi
    // =========================================================================

    public function create(Request $request)
    {
        return redirect()->route('spp.konfirmasi', [
            'bulan' => now()->month,
            'tahun' => now()->year,
        ]);
    }

    public function store(Request $request)
    {
        return $this->konfirmasiSatu($request);
    }

    // =========================================================================
    // KWITANSI
    // =========================================================================

    public function kwitansi(PembayaranSpp $spp)
    {
        $spp->load('siswa', 'dicatatOleh');
        return view('spp.kwitansi', compact('spp'));
    }

    public function cetakKwitansi(PembayaranSpp $spp)
    {
        $spp->load('siswa', 'dicatatOleh');
        $pdf = Pdf::loadView('spp.kwitansi-pdf', compact('spp'))
            ->setPaper('a5', 'portrait')
            ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => false, 'dpi' => 150]);
        $filename = 'kwitansi-' . str_replace('/', '-', $spp->no_kwitansi) . '.pdf';
        return $pdf->stream($filename);
    }

    // =========================================================================
    // TUNGGAKAN
    // =========================================================================

    public function tunggakan(Request $request)
    {
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        $sudahBayarIds = PembayaranSpp::periodik($bulan, $tahun)->pluck('siswa_id');
        $query         = Siswa::where('aktif', true)->whereNotIn('id', $sudahBayarIds);

        if ($request->filled('kelompok')) $query->where('kelompok', $request->kelompok);
        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(fn($q) =>
                $q->where('nama_lengkap', 'like', "%{$cari}%")->orWhere('nis', 'like', "%{$cari}%")
            );
        }

        $tunggakan      = $query->orderBy('nama_lengkap')->paginate(20)->withQueryString();
        $totalTunggakan = Siswa::where('aktif', true)->whereNotIn('id', $sudahBayarIds)->count();
        $tarif          = TarifSpp::aktif($tahun);
        $tarifSpp       = TarifSpp::where('tahun_berlaku', $tahun)->first();
        $kelompokList   = Siswa::where('aktif', true)->distinct()->orderBy('kelompok')->pluck('kelompok');

        return view('spp.tunggakan', compact('tunggakan', 'bulan', 'tahun', 'totalTunggakan', 'tarif', 'kelompokList', 'tarifSpp'));
    }

    // =========================================================================
    // LAPORAN
    // =========================================================================

    public function laporan(Request $request)
    {
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        $pembayaran = PembayaranSpp::with('siswa', 'dicatatOleh')
                         ->periodik($bulan, $tahun)
                         ->orderBy('tanggal_bayar')
                         ->get();

        $sudahBayarIds = $pembayaran->pluck('siswa_id');
        $tunggakan = Siswa::where('aktif', true)
                         ->whereNotIn('id', $sudahBayarIds)
                         ->orderBy('nama_lengkap')
                         ->get();

        $totalSiswa      = Siswa::where('aktif', true)->count();
        $totalSudahBayar = $pembayaran->count();

        // Ambil tarif tahun ini, jika belum ada ambil tahun terdekat sebelumnya
        $tarif = TarifSpp::where('tahun_berlaku', $tahun)->first()
               ?? TarifSpp::where('tahun_berlaku', '<', $tahun)->orderByDesc('tahun_berlaku')->first()
               ?? TarifSpp::orderByDesc('tahun_berlaku')->first();
        $tarifTotal = $tarif ? ($tarif->nominal_spp + $tarif->nominal_kebersihan) : 0;

        $ringkasan = [
            'total_siswa'      => $totalSiswa,
            'sudah_bayar'      => $totalSudahBayar,
            'tunggakan'        => $tunggakan->count(),
            'persentase_lunas' => $totalSiswa > 0 ? round(($totalSudahBayar / $totalSiswa) * 100) : 0,
            'total_pemasukan'  => $pembayaran->sum('total'),
            'total_spp'        => $pembayaran->sum('nominal_spp'),
            'total_kebersihan' => $pembayaran->sum('nominal_kebersihan'),
        ];

        return view('laporan.spp', compact('pembayaran', 'tunggakan', 'bulan', 'tahun', 'ringkasan', 'tarif', 'tarifTotal'));
    }

    // =========================================================================
    // HAPUS PEMBAYARAN
    // =========================================================================

    public function destroy(PembayaranSpp $spp)
    {
        $spp->delete();

        return back()->with('success', 'Data pembayaran berhasil dihapus.');
    }
}