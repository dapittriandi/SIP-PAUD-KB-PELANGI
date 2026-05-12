<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use App\Models\AbsensiGuru;
use App\Models\PembayaranSpp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = $this->getDataByRole($user);

        return view('dashboard.index', compact('user', 'data'));
    }

    private function getDataByRole(User $user): array
    {
        $bulan = now()->month;
        $tahun = now()->year;

        // ── Data umum (semua role) ────────────────────────────
        $base = [
            'bulan_ini' => now()->translatedFormat('F Y'),
        ];

        // ── Admin ─────────────────────────────────────────────
        if ($user->isAdmin()) {
            return array_merge($base, [
                'total_siswa'      => Siswa::where('aktif', true)->count(),
                'total_guru'       => User::where('role', 'guru')->where('aktif', true)->count(),
                'absensi_hari_ini' => AbsensiGuru::whereDate('tanggal', today())->count(),
                'guru_belum_absen' => $this->guruBelumAbsen(),
                'spp_bulan_ini'    => PembayaranSpp::where('bulan', $bulan)->where('tahun', $tahun)->count(),
                'tunggakan_spp'    => $this->hitungTunggakan($bulan, $tahun),
            ]);
        }

        // ── Bendahara ─────────────────────────────────────────
        if ($user->isBendahara()) {
            return array_merge($base, [
                'total_siswa'   => Siswa::where('aktif', true)->count(),
                'spp_bulan_ini' => PembayaranSpp::where('bulan', $bulan)->where('tahun', $tahun)->count(),
                'tunggakan_spp' => $this->hitungTunggakan($bulan, $tahun),
                'total_pemasukan' => PembayaranSpp::where('bulan', $bulan)
                                        ->where('tahun', $tahun)
                                        ->sum('total'),
            ]);
        }

        // ── Kepala Sekolah ────────────────────────────────────
        if ($user->isKepalaSekolah()) {
            return array_merge($base, [
                'total_siswa'      => Siswa::where('aktif', true)->count(),
                'total_guru'       => User::where('role', 'guru')->where('aktif', true)->count(),
                'absensi_hari_ini' => AbsensiGuru::whereDate('tanggal', today())->count(),
                'guru_hadir'       => AbsensiGuru::whereDate('tanggal', today())
                                        ->whereIn('status', ['hadir', 'terlambat'])->count(),
                'spp_bulan_ini'    => PembayaranSpp::where('bulan', $bulan)->where('tahun', $tahun)->count(),
                'tunggakan_spp'    => $this->hitungTunggakan($bulan, $tahun),
            ]);
        }

        // ── Guru ──────────────────────────────────────────────
        if ($user->isGuru()) {
            $absensiHariIni = AbsensiGuru::where('guru_id', $user->id)
                                ->whereDate('tanggal', today())
                                ->first();

            $absenBulanIni = AbsensiGuru::where('guru_id', $user->id)
                                ->whereMonth('tanggal', $bulan)
                                ->whereYear('tanggal', $tahun)
                                ->get();

            return array_merge($base, [
                'sudah_absen'       => !is_null($absensiHariIni),
                'status_hari_ini'   => $absensiHariIni?->status,
                'jam_masuk'         => $absensiHariIni?->jam_masuk,
                'total_hadir'       => $absenBulanIni->whereIn('status', ['hadir', 'terlambat'])->count(),
                'total_izin'        => $absenBulanIni->whereIn('status', ['izin', 'sakit', 'tugas_luar'])->count(),
                'total_alpha'       => $absenBulanIni->where('status', 'alpha')->count(),
            ]);
        }

        return $base;
    }

    private function guruBelumAbsen(): int
    {
        $totalGuru   = User::where('role', 'guru')->where('aktif', true)->count();
        $sudahAbsen  = AbsensiGuru::whereDate('tanggal', today())
                            ->whereHas('guru', fn($q) => $q->where('aktif', true))
                            ->distinct('guru_id')
                            ->count('guru_id');

        return max(0, $totalGuru - $sudahAbsen);
    }

    private function hitungTunggakan(int $bulan, int $tahun): int
    {
        $totalSiswa  = Siswa::where('aktif', true)->count();
        $sudahBayar  = PembayaranSpp::where('bulan', $bulan)
                            ->where('tahun', $tahun)
                            ->distinct('siswa_id')
                            ->count('siswa_id');

        return max(0, $totalSiswa - $sudahBayar);
    }
}