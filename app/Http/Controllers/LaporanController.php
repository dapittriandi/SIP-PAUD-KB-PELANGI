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
    // LAPORAN ABSENSI — ringkasan (HTML)
    // =========================================================================

    public function absensi(Request $request)
    {
        abort_unless(in_array(Auth::user()->role, ['admin', 'kepala_sekolah']), 403);

        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);
        $mode  = $request->get('mode', 'bulanan'); // 'bulanan' | 'tahunan'

        $guruAktif = User::where('role', 'guru')
                         ->where('aktif', true)
                         ->orderBy('nama_lengkap')
                         ->get();

        // ── Rekap Bulanan ─────────────────────────────────────────────────────
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

        // ── Rekap Tahunan ─────────────────────────────────────────────────────
        // BUG FIX #1: Struktur $rekapTahunan diubah dari "per-bulan agregat"
        // menjadi "per-guru dengan breakdown 12 bulan", sesuai yang diharapkan
        // blade: $rt['guru'], $rt['per_bulan'][0..11], $rt['rata_persentase']
        $rekapTahunan = collect();
        if ($mode === 'tahunan') {
            // Ambil semua absensi setahun sekaligus — lebih efisien dari 12x query
            $absensiTahunan = AbsensiGuru::whereYear('tanggal', $tahun)
                                ->get()
                                ->groupBy(function ($row) {
                                    // key: "guru_id-bulan"
                                    return $row->guru_id . '-' . (int) date('n', strtotime($row->tanggal));
                                });

            $rekapTahunan = $guruAktif->map(function (User $guru) use ($absensiTahunan, $tahun) {
                $perBulan = collect(range(1, 12))->map(function (int $bln) use ($guru, $absensiTahunan, $tahun) {
                    $key       = $guru->id . '-' . $bln;
                    $absensi   = $absensiTahunan->get($key, collect());
                    $hariKerja = $this->hitungHariKerja($bln, $tahun);
                    $hadir     = $absensi->whereIn('status', ['hadir', 'terlambat'])->count();

                    return [
                        'bulan'      => $bln,
                        'hadir'      => $hadir,
                        'hari_kerja' => $hariKerja,
                        'persentase' => $hariKerja > 0 ? round(($hadir / $hariKerja) * 100) : 0,
                    ];
                });

                // Rata-rata hanya dari bulan yang ada hari kerja
                $bulanAktif     = $perBulan->where('hari_kerja', '>', 0);
                $rataPersentase = $bulanAktif->count() > 0
                    ? round($bulanAktif->avg('persentase'), 1)
                    : 0;

                return [
                    'guru'            => $guru,
                    'per_bulan'       => $perBulan,   // Collection 12 item, index 0=Jan … 11=Des
                    'rata_persentase' => $rataPersentase,
                ];
            });
        }
        // ─────────────────────────────────────────────────────────────────────

        $ringkasan = [
            'total_guru'      => $guruAktif->count(),
            'rata_hadir'      => $rekap->count() > 0 ? round($rekap->avg('hadir'), 1) : 0,
            'rata_alpha'      => $rekap->count() > 0 ? round($rekap->avg('alpha'), 1) : 0,
            'rata_persentase' => $rekap->count() > 0 ? round($rekap->avg('persentase'), 1) : 0,
        ];

        // BUG FIX #2: Cek format=pdf DIHAPUS dari method ini.
        // Cetak PDF sekarang ditangani oleh method cetakAbsensi() yang terpisah,
        // dipanggil melalui route tersendiri (absensi.cetak-laporan).
        // Ini mencegah form "Tampilkan" secara tidak sengaja menghasilkan PDF.

        return view('laporan.absensi', compact(
            'rekap', 'bulan', 'tahun', 'hariKerja', 'ringkasan', 'rekapTahunan', 'mode'
        ));
    }

    // =========================================================================
    // CETAK PDF ABSENSI — method terpisah, route terpisah
    // =========================================================================

    public function cetakAbsensi(Request $request)
    {
        abort_unless(in_array(Auth::user()->role, ['admin', 'kepala_sekolah']), 403);

        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);
        $mode  = $request->get('mode', 'bulanan');

        $guruAktif = User::where('role', 'guru')
                         ->where('aktif', true)
                         ->orderBy('nama_lengkap')
                         ->get();

        $hariKerja = $this->hitungHariKerja($bulan, $tahun);

        if ($mode === 'tahunan') {
            // ── PDF Tahunan ───────────────────────────────────────────────────
            // Ambil semua absensi setahun dalam 1 query
            $absensiTahunan = AbsensiGuru::whereYear('tanggal', $tahun)
                                ->get()
                                ->groupBy(function ($row) {
                                    return $row->guru_id . '-' . (int) date('n', strtotime($row->tanggal));
                                });

            $rekapTahunan = $guruAktif->map(function (User $guru) use ($absensiTahunan, $tahun) {
                $perBulan = collect(range(1, 12))->map(function (int $bln) use ($guru, $absensiTahunan, $tahun) {
                    $key       = $guru->id . '-' . $bln;
                    $absensi   = $absensiTahunan->get($key, collect());
                    $hk        = $this->hitungHariKerja($bln, $tahun);
                    $hadir     = $absensi->whereIn('status', ['hadir', 'terlambat'])->count();
                    return [
                        'bulan'      => $bln,
                        'hadir'      => $hadir,
                        'alpha'      => $absensi->where('status', 'alpha')->count(),
                        'hari_kerja' => $hk,
                        'persentase' => $hk > 0 ? round(($hadir / $hk) * 100) : 0,
                    ];
                });

                $bulanAktif     = $perBulan->where('hari_kerja', '>', 0);
                $rataPersentase = $bulanAktif->count() > 0
                    ? round($bulanAktif->avg('persentase'), 1)
                    : 0;

                return [
                    'guru'            => $guru,
                    'per_bulan'       => $perBulan,
                    'rata_persentase' => $rataPersentase,
                ];
            });

            // rekap bulanan tetap dibutuhkan untuk $ringkasan
            $semuaAbsensi = AbsensiGuru::periodik($bulan, $tahun)->get()->groupBy('guru_id');
            $rekap = $guruAktif->map(function (User $guru) use ($semuaAbsensi, $hariKerja) {
                $absensi = $semuaAbsensi->get($guru->id, collect());
                $hadir   = $absensi->whereIn('status', ['hadir', 'terlambat'])->count();
                return [
                    'guru'       => $guru,
                    'hadir'      => $hadir,
                    'alpha'      => $absensi->where('status', 'alpha')->count(),
                    'persentase' => $hariKerja > 0 ? round(($hadir / $hariKerja) * 100) : 0,
                ];
            });

            $ringkasan = [
                'total_guru'      => $guruAktif->count(),
                'rata_hadir'      => $rekap->count() > 0 ? round($rekap->avg('hadir'), 1) : 0,
                'rata_alpha'      => $rekap->count() > 0 ? round($rekap->avg('alpha'), 1) : 0,
                'rata_persentase' => $rekap->count() > 0 ? round($rekap->avg('persentase'), 1) : 0,
            ];

            return $this->cetakAbsensiPdf($rekap, $bulan, $tahun, $hariKerja, $ringkasan, $mode, $rekapTahunan);

        } else {
            // ── PDF Bulanan ───────────────────────────────────────────────────
            // Ambil semua absensi bulan ini, group per guru
            $semuaAbsensi = AbsensiGuru::periodik($bulan, $tahun)
                                ->get()
                                ->groupBy('guru_id');

            $rekap = $guruAktif->map(function (User $guru) use ($semuaAbsensi, $hariKerja) {
                $absensi = $semuaAbsensi->get($guru->id, collect());
                $hadir   = $absensi->whereIn('status', ['hadir', 'terlambat'])->count();

                // absensi_harian: array per-record dengan tanggal, status, keterangan
                // Dibutuhkan blade PDF untuk menampilkan status per-tanggal di kolom tabel
                $absensiHarian = $absensi->map(fn($a) => [
                    'tanggal'    => $a->tanggal,          // date string / Carbon
                    'status'     => $a->status,
                    'keterangan' => $a->keterangan ?? '',
                ])->values()->toArray();

                return [
                    'guru'            => $guru,
                    'hadir'           => $hadir,
                    'terlambat'       => $absensi->where('status', 'terlambat')->count(),
                    'izin'            => $absensi->where('status', 'izin')->count(),
                    'sakit'           => $absensi->where('status', 'sakit')->count(),
                    'tugas_luar'      => $absensi->where('status', 'tugas_luar')->count(),
                    'alpha'           => $absensi->where('status', 'alpha')->count(),
                    'hari_kerja'      => $hariKerja,
                    'persentase'      => $hariKerja > 0 ? round(($hadir / $hariKerja) * 100) : 0,
                    'absensi_harian'  => $absensiHarian, // ← kunci untuk tabel per-tanggal
                ];
            });

            $ringkasan = [
                'total_guru'      => $guruAktif->count(),
                'rata_hadir'      => $rekap->count() > 0 ? round($rekap->avg('hadir'), 1) : 0,
                'rata_alpha'      => $rekap->count() > 0 ? round($rekap->avg('alpha'), 1) : 0,
                'rata_persentase' => $rekap->count() > 0 ? round($rekap->avg('persentase'), 1) : 0,
            ];

            return $this->cetakAbsensiPdf($rekap, $bulan, $tahun, $hariKerja, $ringkasan, $mode);
        }
    }

    // =========================================================================
    // LAPORAN SPP — rekap pembayaran & tunggakan per bulan
    // =========================================================================

    public function spp(Request $request)
    {
        abort_unless(in_array(Auth::user()->role, ['admin', 'kepala_sekolah', 'bendahara']), 403);

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

        // BUG FIX #2 (SPP): Sama — cek format=pdf dihapus, pakai route terpisah
        return view('laporan.spp', compact(
            'pembayaran', 'tunggakan', 'bulan', 'tahun', 'ringkasan'
        ));
    }

    // =========================================================================
    // CETAK PDF SPP — method terpisah, route terpisah
    // =========================================================================

    public function cetakSpp(Request $request)
    {
        abort_unless(in_array(Auth::user()->role, ['admin', 'kepala_sekolah', 'bendahara']), 403);

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
        $ringkasan = [
            'total_siswa'      => $totalSiswa,
            'sudah_bayar'      => $totalSudahBayar,
            'tunggakan'        => $tunggakan->count(),
            'persentase_lunas' => $totalSiswa > 0 ? round(($totalSudahBayar / $totalSiswa) * 100) : 0,
            'total_pemasukan'  => $pembayaran->sum('total'),
            'total_spp'        => $pembayaran->sum('nominal_spp'),
            'total_kebersihan' => $pembayaran->sum('nominal_kebersihan'),
        ];

        return $this->cetakSppPdf($pembayaran, $tunggakan, $bulan, $tahun, $ringkasan);
    }

    // =========================================================================
    // PDF Private Helpers
    // =========================================================================

    private function cetakAbsensiPdf($rekap, int $bulan, int $tahun, int $hariKerja, array $ringkasan, string $mode = 'bulanan', $rekapTahunan = null)
    {
        $rekapTahunan = $rekapTahunan ?? collect();

        $pdf = Pdf::loadView('laporan.pdf.absensi', compact(
                        'rekap', 'bulan', 'tahun', 'hariKerja', 'ringkasan', 'mode', 'rekapTahunan'
                    ))
                  ->setPaper('a4', 'landscape')
                  ->setOptions([
                      'defaultFont'          => 'sans-serif',
                      'isHtml5ParserEnabled' => true,
                      'isRemoteEnabled'      => false,
                  ]);

        $namaBulan = \Carbon\Carbon::create(null, $bulan)->translatedFormat('F');
        $suffix    = $mode === 'tahunan' ? "tahunan-{$tahun}" : "{$namaBulan}-{$tahun}";
        return $pdf->stream("laporan-absensi-{$suffix}.pdf");
    }

    private function cetakSppPdf($pembayaran, $tunggakan, int $bulan, int $tahun, array $ringkasan)
    {
        $pdf = Pdf::loadView('laporan.pdf.spp', compact('pembayaran', 'tunggakan', 'bulan', 'tahun', 'ringkasan'))
                  ->setPaper('a4', 'portrait')
                  ->setOptions([
                      'defaultFont'          => 'sans-serif',
                      'isHtml5ParserEnabled' => true,
                      'isRemoteEnabled'      => false,
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