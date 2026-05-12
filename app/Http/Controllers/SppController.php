<?php

namespace App\Http\Controllers;

use App\Models\PembayaranSpp;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SppController extends Controller
{
    // =========================================================================
    // INDEX — daftar pembayaran bulan ini
    // =========================================================================

    public function index(Request $request)
    {
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        $query = PembayaranSpp::with('siswa', 'dicatatOleh')
            ->periodik($bulan, $tahun);

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->whereHas('siswa', fn($q) =>
                $q->where('nama_lengkap', 'like', "%{$cari}%")
                  ->orWhere('nis', 'like', "%{$cari}%")
            );
        }

        $pembayaran = $query->orderByDesc('tanggal_bayar')->paginate(20)->withQueryString();

        $totalPemasukan = PembayaranSpp::periodik($bulan, $tahun)->sum('total');
        $totalBayar     = PembayaranSpp::periodik($bulan, $tahun)->distinct('siswa_id')->count('siswa_id');
        $totalSiswa     = Siswa::where('aktif', true)->count();
        $tunggakan      = max(0, $totalSiswa - $totalBayar);

        return view('spp.index', compact(
            'pembayaran', 'bulan', 'tahun',
            'totalPemasukan', 'totalBayar', 'totalSiswa', 'tunggakan'
        ));
    }

    // =========================================================================
    // CREATE — form input pembayaran
    // =========================================================================

    public function create(Request $request)
    {
        // Bisa diakses dari halaman detail siswa dengan ?siswa_id=xxx
        $siswaId = $request->get('siswa_id');
        $siswa   = $siswaId ? Siswa::where('aktif', true)->find($siswaId) : null;

        $daftarSiswa = Siswa::where('aktif', true)
            ->orderBy('nama_lengkap')
            ->get(['id', 'nama_lengkap', 'nis', 'kelompok']);

        $bulanOptions = collect(range(1, 12))->map(fn($b) => [
            'value' => $b,
            'label' => \Carbon\Carbon::create(null, $b)->translatedFormat('F'),
        ]);

        return view('spp.create', compact('siswa', 'daftarSiswa', 'bulanOptions'));
    }

    // =========================================================================
    // STORE — simpan pembayaran
    // =========================================================================

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id'           => 'required|exists:siswa,id',
            'bulan'              => 'required|integer|between:1,12',
            'tahun'              => 'required|integer|min:2020',
            'nominal_spp'        => 'required|numeric|min:0',
            'nominal_kebersihan' => 'required|numeric|min:0',
            'tanggal_bayar'      => 'required|date|before_or_equal:today',
        ], [
            'siswa_id.required'    => 'Siswa wajib dipilih.',
            'siswa_id.exists'      => 'Siswa tidak ditemukan.',
            'bulan.required'       => 'Bulan wajib dipilih.',
            'tahun.required'       => 'Tahun wajib diisi.',
            'nominal_spp.required' => 'Nominal SPP wajib diisi.',
            'nominal_kebersihan.required' => 'Nominal kebersihan wajib diisi.',
            'tanggal_bayar.required' => 'Tanggal bayar wajib diisi.',
            'tanggal_bayar.before_or_equal' => 'Tanggal bayar tidak boleh melebihi hari ini.',
        ]);

        // Cek sudah bayar bulan ini
        $sudahBayar = PembayaranSpp::where('siswa_id', $validated['siswa_id'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->exists();

        if ($sudahBayar) {
            $siswa = Siswa::find($validated['siswa_id']);
            return back()->withInput()->withErrors([
                'msg' => "{$siswa->nama_lengkap} sudah membayar SPP untuk bulan/tahun tersebut."
            ]);
        }

        $validated['dicatat_oleh'] = Auth::id();

        $spp = PembayaranSpp::create($validated);

        return redirect()->route('spp.kwitansi', $spp)
            ->with('success', 'Pembayaran SPP berhasil disimpan.');
    }

    // =========================================================================
    // TUNGGAKAN — daftar siswa belum bayar
    // =========================================================================

    public function tunggakan(Request $request)
    {
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        $sudahBayarIds = PembayaranSpp::periodik($bulan, $tahun)
            ->pluck('siswa_id');

        $query = Siswa::where('aktif', true)
            ->whereNotIn('id', $sudahBayarIds);

        if ($request->filled('kelompok')) {
            $query->where('kelompok', $request->kelompok);
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(fn($q) =>
                $q->where('nama_lengkap', 'like', "%{$cari}%")
                  ->orWhere('nis', 'like', "%{$cari}%")
            );
        }

        $tunggakan = $query->orderBy('nama_lengkap')->paginate(20)->withQueryString();

        $totalTunggakan = Siswa::where('aktif', true)
            ->whereNotIn('id', $sudahBayarIds)
            ->count();

        return view('spp.tunggakan', compact('tunggakan', 'bulan', 'tahun', 'totalTunggakan'));
    }

    // =========================================================================
    // KWITANSI — tampil & cetak PDF
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
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'dpi' => 150,
            ]);

        $filename = 'kwitansi-' . str_replace('/', '-', $spp->no_kwitansi) . '.pdf';
        return $pdf->stream($filename);
    }

    // =========================================================================
    // DESTROY — hapus pembayaran
    // =========================================================================

    public function destroy(PembayaranSpp $spp)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $bulan = $spp->bulan;
        $tahun = $spp->tahun;
        $spp->delete();

        return redirect()->route('spp.index', compact('bulan', 'tahun'))
            ->with('success', 'Data pembayaran berhasil dihapus.');
    }
}