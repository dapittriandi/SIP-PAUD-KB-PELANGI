<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\PembayaranSpp;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    // =========================================================================
    // LAPORAN ABSENSI — rekap bulanan semua guru
    // =========================================================================

    public function absensi(Request $request)
    {
        abort_unless(in_array(Auth::user()->role, ['admin', 'kepala_sekolah']), 403);

        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        $guruAktif = User::where('role', 'guru')
                         ->where('aktif', true)
                         ->orderBy('nama_lengkap')
                         ->get();

        $semuaAbsensi = AbsensiGuru::periodik($bulan, $tahun)
                            ->get()
                            ->groupBy('guru_id');

        $hariKerja = $this->hitungHariKerja($bulan, $tahun);

        $rekap = $guruAktif->map(function (User $guru) use ($semuaAbsensi, $hariKerja) {
            $absensi = $semuaAbsensi->get($guru->id, collect());
            $hadir   = $absensi->whereIn('status', ['hadir', 'terlambat'])->count();
            return [
                'guru'        => $guru,
                'hadir'       => $hadir,
                'terlambat'   => $absensi->where('status', 'terlambat')->count(),
                'izin'        => $absensi->where('status', 'izin')->count(),
                'sakit'       => $absensi->where('status', 'sakit')->count(),
                'tugas_luar'  => $absensi->where('status', 'tugas_luar')->count(),
                'alpha'       => $absensi->where('status', 'alpha')->count(),
                'hari_kerja'  => $hariKerja,
                'persentase'  => $hariKerja > 0 ? round(($hadir / $hariKerja) * 100) : 0,
            ];
        });

        // Ringkasan keseluruhan
        $ringkasan = [
            'total_guru'        => $guruAktif->count(),
            'rata_hadir'        => $rekap->avg('hadir'),
            'rata_alpha'        => $rekap->avg('alpha'),
            'rata_persentase'   => $rekap->avg('persentase'),
        ];

        if ($request->get('format') === 'pdf') {
            return $this->cetakAbsensiPdf($rekap, $bulan, $tahun, $hariKerja, $ringkasan);
        }

        return view('laporan.absensi', compact('rekap', 'bulan', 'tahun', 'hariKerja', 'ringkasan'));
    }

    // =========================================================================
    // LAPORAN SPP — rekap pembayaran & tunggakan per bulan
    // =========================================================================

    public function spp(Request $request)
    {
        abort_unless(in_array(Auth::user()->role, ['admin', 'kepala_sekolah', 'bendahara']), 403);

        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        // Semua pembayaran bulan tersebut
        $pembayaran = PembayaranSpp::with('siswa', 'dicatatOleh')
                         ->periodik($bulan, $tahun)
                         ->orderBy('tanggal_bayar')
                         ->get();

        // Siswa yang BELUM bayar
        $sudahBayarIds = $pembayaran->pluck('siswa_id');
        $tunggakan = Siswa::where('aktif', true)
                         ->whereNotIn('id', $sudahBayarIds)
                         ->orderBy('nama_lengkap')
                         ->get();

        // Ringkasan keuangan
        $totalSiswa       = Siswa::where('aktif', true)->count();
        $totalSudahBayar  = $pembayaran->count();
        $totalPemasukan   = $pembayaran->sum('total');
        $totalSpp         = $pembayaran->sum('nominal_spp');
        $totalKebersihan  = $pembayaran->sum('nominal_kebersihan');
        $totalTunggakan   = $tunggakan->count();

        $ringkasan = [
            'total_siswa'      => $totalSiswa,
            'sudah_bayar'      => $totalSudahBayar,
            'tunggakan'        => $totalTunggakan,
            'persentase_lunas' => $totalSiswa > 0 ? round(($totalSudahBayar / $totalSiswa) * 100) : 0,
            'total_pemasukan'  => $totalPemasukan,
            'total_spp'        => $totalSpp,
            'total_kebersihan' => $totalKebersihan,
        ];

        if ($request->get('format') === 'pdf') {
            return $this->cetakSppPdf($pembayaran, $tunggakan, $bulan, $tahun, $ringkasan);
        }

        return view('laporan.spp', compact(
            'pembayaran', 'tunggakan', 'bulan', 'tahun', 'ringkasan'
        ));
    }

    // =========================================================================
    // PDF Export
    // =========================================================================

    private function cetakAbsensiPdf($rekap, int $bulan, int $tahun, int $hariKerja, array $ringkasan)
    {
        $pdf = Pdf::loadView('laporan.pdf.absensi', compact('rekap', 'bulan', 'tahun', 'hariKerja', 'ringkasan'))
                  ->setPaper('a4', 'landscape')
                  ->setOptions([
                      'defaultFont'        => 'sans-serif',
                      'isHtml5ParserEnabled' => true,
                      'isRemoteEnabled'    => false,
                  ]);

        $namaBulan = \Carbon\Carbon::create(null, $bulan)->translatedFormat('F');
        return $pdf->stream("laporan-absensi-{$namaBulan}-{$tahun}.pdf");
    }

    private function cetakSppPdf($pembayaran, $tunggakan, int $bulan, int $tahun, array $ringkasan)
    {
        $pdf = Pdf::loadView('laporan.pdf.spp', compact('pembayaran', 'tunggakan', 'bulan', 'tahun', 'ringkasan'))
                  ->setPaper('a4', 'portrait')
                  ->setOptions([
                      'defaultFont'        => 'sans-serif',
                      'isHtml5ParserEnabled' => true,
                      'isRemoteEnabled'    => false,
                  ]);

        $namaBulan = \Carbon\Carbon::create(null, $bulan)->translatedFormat('F');
        return $pdf->stream("laporan-spp-{$namaBulan}-{$tahun}.pdf");
    }

    // =========================================================================
    // Helper
    // =========================================================================

    private function hitungHariKerja(int $bulan, int $tahun): int
    {
        $start = \Carbon\Carbon::create($tahun, $bulan, 1);
        $end   = $start->copy()->endOfMonth();
        $hari  = 0;
        while ($start->lte($end)) {
            if ($start->isWeekday()) $hari++;
            $start->addDay();
        }
        return $hari;
    }
}