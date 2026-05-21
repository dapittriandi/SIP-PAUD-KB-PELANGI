<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use App\Models\AbsensiGuru;
use App\Models\PembayaranSpp;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = $this->getDataByRole($user);

        return view('dashboard.index', compact('user', 'data'));
    }

    // ══════════════════════════════════════════════════════════════
    //  CHART SPP — endpoint AJAX (Admin & Bendahara)
    // ══════════════════════════════════════════════════════════════

    public function chartSpp(): JsonResponse
    {
        $labels    = [];
        $terbayar  = [];
        $tunggakan = [];

        // 6 bulan terakhir (dari paling lama ke paling baru)
        $bulanList = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i));
        $totalSiswa = Siswa::where('aktif', true)->count();

        foreach ($bulanList as $bln) {
            $labels[] = $bln->translatedFormat('M'); // Jan, Feb, …

            $sudahBayar  = PembayaranSpp::where('bulan', $bln->month)
                ->where('tahun', $bln->year)
                ->distinct('siswa_id')
                ->count('siswa_id');

            $tung        = max(0, $totalSiswa - $sudahBayar);

            $terbayar[]  = $sudahBayar;
            $tunggakan[] = $tung;
        }

        return response()->json(compact('labels', 'terbayar', 'tunggakan'));
    }

    // ══════════════════════════════════════════════════════════════
    //  CHART ABSENSI GURU — endpoint AJAX (Admin & Kepala Sekolah)
    // ══════════════════════════════════════════════════════════════

    public function chartAbsensi(Request $request): JsonResponse
    {
        $period = $request->query('period', 'bulan'); // 'bulan' | 'minggu'

        $payload = match ($period) {
            'minggu' => $this->chartAbsensiMinggu(),
            default  => $this->chartAbsensiBulan(),
        };

        return response()->json($payload);
    }

    /**
     * Data 5 bulan terakhir (termasuk bulan berjalan).
     * Hadir   = hadir + terlambat
     * Izin    = izin + sakit + tugas_luar
     * Alpha   = alpha
     */
    private function chartAbsensiBulan(): array
    {
        // Buat daftar 5 bulan terakhir (dari paling lama ke paling baru)
        $bulanList = collect(range(4, 0))->map(fn ($i) => now()->subMonths($i));

        $labels = [];
        $hadir  = [];
        $izin   = [];
        $alpha  = [];

        foreach ($bulanList as $bln) {
            $labels[] = $bln->translatedFormat('M'); // Jan, Feb, …

            // Query aggregate per-status untuk bulan ini
            $rows = AbsensiGuru::whereMonth('tanggal', $bln->month)
                ->whereYear('tanggal', $bln->year)
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            $hadir[]  = (int) (($rows['hadir']      ?? 0) + ($rows['terlambat']  ?? 0));
            $izin[]   = (int) (($rows['izin']        ?? 0) + ($rows['sakit']      ?? 0)
                                                           + ($rows['tugas_luar'] ?? 0));
            $alpha[]  = (int)  ($rows['alpha']       ?? 0);
        }

        return compact('labels', 'hadir', 'izin', 'alpha');
    }

    /**
     * Data Senin–Jumat minggu berjalan.
     */
    private function chartAbsensiMinggu(): array
    {
        $namaHari = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum'];
        $senin    = now()->startOfWeek(); // Carbon: Senin = hari pertama

        $labels = [];
        $hadir  = [];
        $izin   = [];
        $alpha  = [];

        foreach (range(0, 4) as $idx) {
            $hari     = $senin->copy()->addDays($idx);
            $labels[] = $namaHari[$idx];

            $rows = AbsensiGuru::whereDate('tanggal', $hari->toDateString())
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            $hadir[]  = (int) (($rows['hadir']      ?? 0) + ($rows['terlambat']  ?? 0));
            $izin[]   = (int) (($rows['izin']        ?? 0) + ($rows['sakit']      ?? 0)
                                                           + ($rows['tugas_luar'] ?? 0));
            $alpha[]  = (int)  ($rows['alpha']       ?? 0);
        }

        return compact('labels', 'hadir', 'izin', 'alpha');
    }

    // ══════════════════════════════════════════════════════════════
    //  DATA PER ROLE
    // ══════════════════════════════════════════════════════════════

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
                'tunggakan_list'   => $this->getTunggakanList($bulan, $tahun),
            ]);
        }

        // ── Bendahara ─────────────────────────────────────────
        if ($user->isBendahara()) {
            return array_merge($base, [
                'total_siswa'     => Siswa::where('aktif', true)->count(),
                'spp_bulan_ini'   => PembayaranSpp::where('bulan', $bulan)->where('tahun', $tahun)->count(),
                'tunggakan_spp'   => $this->hitungTunggakan($bulan, $tahun),
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
                'tunggakan_list'   => $this->getTunggakanList($bulan, $tahun),
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
                'sudah_absen'     => !is_null($absensiHariIni),
                'status_hari_ini' => $absensiHariIni?->status,
                'jam_masuk'       => $absensiHariIni?->jam_masuk,
                'total_hadir'     => $absenBulanIni->whereIn('status', ['hadir', 'terlambat'])->count(),
                'total_izin'      => $absenBulanIni->whereIn('status', ['izin', 'sakit', 'tugas_luar'])->count(),
                'total_alpha'     => $absenBulanIni->where('status', 'alpha')->count(),
            ]);
        }

        return $base;
    }

    // ══════════════════════════════════════════════════════════════
    //  HELPER PRIVATE
    // ══════════════════════════════════════════════════════════════

    private function getTunggakanList(int $bulan, int $tahun, int $limit = 5)
    {
        // Ambil ID siswa yang sudah bayar bulan ini
        $sudahBayarIds = PembayaranSpp::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->pluck('siswa_id');

        // Siswa aktif yang belum bayar, urutkan by nama, ambil $limit teratas
        return Siswa::where('aktif', true)
            ->whereNotIn('id', $sudahBayarIds)
            ->orderBy('nama_lengkap')
            ->limit($limit)
            ->get();
    }

    private function guruBelumAbsen(): int
    {
        $totalGuru  = User::where('role', 'guru')->where('aktif', true)->count();
        $sudahAbsen = AbsensiGuru::whereDate('tanggal', today())
                        ->whereHas('guru', fn ($q) => $q->where('aktif', true))
                        ->distinct('guru_id')
                        ->count('guru_id');

        return max(0, $totalGuru - $sudahAbsen);
    }

    private function hitungTunggakan(int $bulan, int $tahun): int
    {
        $totalSiswa = Siswa::where('aktif', true)->count();
        $sudahBayar = PembayaranSpp::where('bulan', $bulan)
                        ->where('tahun', $tahun)
                        ->distinct('siswa_id')
                        ->count('siswa_id');

        return max(0, $totalSiswa - $sudahBayar);
    }
}