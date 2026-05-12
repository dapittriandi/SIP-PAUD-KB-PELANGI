<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\User;
use App\Services\GeolocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AbsensiController extends Controller
{
    // Jam kerja (fallback jika config/sekolah.php belum ada)
    const JAM_MASUK     = '07:00';
    const JAM_TERLAMBAT = '07:30';

    // =========================================================================
    // GURU — Halaman absensi self check-in
    // =========================================================================

    public function index()
    {
        $user = Auth::user();
        abort_unless($user->isGuru(), 403);

        $absenHariIni = AbsensiGuru::where('guru_id', $user->id)
                            ->whereDate('tanggal', today())
                            ->first();

        $bulan = now()->month;
        $tahun = now()->year;

        $absenBulanIni = AbsensiGuru::where('guru_id', $user->id)
                            ->periodik($bulan, $tahun)
                            ->orderByDesc('tanggal')
                            ->get();

        return view('absensi.index', compact('user', 'absenHariIni', 'absenBulanIni'));
    }

    /**
     * Proses self check-in guru via GPS (JSON response untuk Alpine.js)
     */
    public function checkIn(Request $request)
    {
        $user = Auth::user();
        abort_unless($user->isGuru(), 403);

        if ($user->sudahAbsenHariIni()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi hari ini.',
            ], 422);
        }

        $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $lat = (float) $request->latitude;
        $lng = (float) $request->longitude;

        $geo = GeolocationService::validasiLokasi($lat, $lng);

        if (!$geo['valid']) {
            $radius = config('sekolah.radius_meter', 100);
            return response()->json([
                'success'      => false,
                'message'      => "Lokasi Anda terlalu jauh dari sekolah ({$geo['jarak_meter']}m). Maksimal {$radius}m.",
                'jarak'        => $geo['jarak_meter'],
                'lokasi_valid' => false,
            ], 422);
        }

        $jamSekarang = now()->format('H:i');
        [$status, $terlambatMenit] = $this->tentukanStatus($jamSekarang);

        AbsensiGuru::create([
            'guru_id'         => $user->id,
            'tanggal'         => today(),
            'jam_masuk'       => now()->format('H:i:s'),
            'status'          => $status,
            'terlambat_menit' => $terlambatMenit,
            'latitude'        => $lat,
            'longitude'       => $lng,
            'jarak_meter'     => $geo['jarak_meter'],
            'lokasi_valid'    => true,
            'dicatat_oleh'    => null,
        ]);

        $pesan = match ($status) {
            'hadir'     => 'Absensi berhasil! Selamat bekerja.',
            'terlambat' => "Absensi berhasil. Anda terlambat {$terlambatMenit} menit.",
            default     => 'Absensi berhasil dicatat.',
        };

        return response()->json([
            'success'   => true,
            'message'   => $pesan,
            'status'    => $status,
            'jam_masuk' => now()->format('H:i'),
        ]);
    }

    // =========================================================================
    // GURU — Form izin / sakit / tugas luar mandiri
    // =========================================================================

    /**
     * Tampilkan form lapor izin/sakit/tugas_luar
     */
    public function formIzin()
    {
        $user = Auth::user();
        abort_unless($user->isGuru(), 403);

        $absenHariIni = AbsensiGuru::where('guru_id', $user->id)
                            ->whereDate('tanggal', today())
                            ->first();

        return view('absensi.izin', compact('absenHariIni'));
    }

    /**
     * Simpan laporan izin / sakit / tugas_luar dari guru
     */
    public function storeIzin(Request $request)
    {
        $user = Auth::user();
        abort_unless($user->isGuru(), 403);

        // Kalau sudah absen hari ini, tolak
        if (AbsensiGuru::where('guru_id', $user->id)->whereDate('tanggal', today())->exists()) {
            return redirect()->route('absensi.izin.form')
                ->with('error', 'Anda sudah memiliki catatan absensi hari ini.');
        }

        $request->validate([
            'status'     => 'required|in:izin,sakit,tugas_luar',
            'keterangan' => 'required|string|min:5|max:500',
        ], [
            'status.required'     => 'Jenis keterangan wajib dipilih.',
            'status.in'           => 'Jenis keterangan tidak valid.',
            'keterangan.required' => 'Keterangan wajib diisi.',
            'keterangan.min'      => 'Keterangan minimal 5 karakter.',
            'keterangan.max'      => 'Keterangan maksimal 500 karakter.',
        ]);

        AbsensiGuru::create([
            'guru_id'      => $user->id,
            'tanggal'      => today(),
            'jam_masuk'    => null,
            'status'       => $request->status,
            'keterangan'   => $request->keterangan,
            'lokasi_valid' => false,
            'dicatat_oleh' => null,
        ]);

        return redirect()->route('absensi.index')
            ->with('success', 'Laporan ' . ucfirst(str_replace('_', ' ', $request->status)) . ' berhasil dikirim.');
    }

    // =========================================================================
    // ADMIN — Kelola absensi semua guru
    // =========================================================================

    public function kelola(Request $request)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $tanggal = $request->filled('tanggal')
            ? Carbon::parse($request->tanggal)
            : today();

        $guruAktif = User::where('role', 'guru')
                         ->where('aktif', true)
                         ->orderBy('nama_lengkap')
                         ->get();

        $absensiHari = AbsensiGuru::whereDate('tanggal', $tanggal)
                           ->with('guru')
                           ->get()
                           ->keyBy('guru_id');

        return view('absensi.kelola', compact('guruAktif', 'absensiHari', 'tanggal'));
    }

    /**
     * Admin input / update absensi satu guru
     */
    public function simpanManual(Request $request)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $request->validate([
            'guru_id'    => 'required|exists:users,id',
            'tanggal'    => 'required|date|before_or_equal:today',
            'status'     => 'required|in:hadir,terlambat,izin,sakit,tugas_luar,alpha',
            'jam_masuk'  => 'nullable|date_format:H:i',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'guru_id.required'        => 'Guru wajib dipilih.',
            'tanggal.required'        => 'Tanggal wajib diisi.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.',
            'status.required'         => 'Status wajib dipilih.',
        ]);

        $terlambatMenit = 0;
        if ($request->status === 'terlambat' && $request->filled('jam_masuk')) {
            $terlambatMenit = $this->hitungTerlambat($request->jam_masuk);
        }

        AbsensiGuru::updateOrCreate(
            [
                'guru_id' => $request->guru_id,
                'tanggal' => $request->tanggal,
            ],
            [
                'jam_masuk'       => $request->filled('jam_masuk') ? $request->jam_masuk . ':00' : null,
                'status'          => $request->status,
                'terlambat_menit' => $terlambatMenit,
                'keterangan'      => $request->keterangan,
                'lokasi_valid'    => false,
                'dicatat_oleh'    => Auth::id(),
            ]
        );

        return redirect()->route('absensi.kelola', ['tanggal' => $request->tanggal])
            ->with('success', 'Absensi berhasil disimpan.');
    }

    /**
     * Hapus satu record absensi (admin)
     */
    public function hapus(AbsensiGuru $absensi)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $tanggal = $absensi->tanggal->format('Y-m-d');
        $absensi->delete();

        return redirect()->route('absensi.kelola', ['tanggal' => $tanggal])
            ->with('success', 'Data absensi berhasil dihapus.');
    }

    /**
     * Auto-alpha: tandai semua guru yang belum absen pada tanggal tertentu.
     */
    public function autoAlpha(Request $request)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $tanggal = $request->filled('tanggal')
            ? Carbon::parse($request->tanggal)->toDateString()
            : today()->toDateString();

        $guruAktif  = User::where('role', 'guru')->where('aktif', true)->pluck('id');
        $sudahAbsen = AbsensiGuru::whereDate('tanggal', $tanggal)->pluck('guru_id');
        $belumAbsen = $guruAktif->diff($sudahAbsen);

        $jumlah = 0;
        foreach ($belumAbsen as $guruId) {
            AbsensiGuru::create([
                'guru_id'      => $guruId,
                'tanggal'      => $tanggal,
                'jam_masuk'    => null,
                'status'       => 'alpha',
                'keterangan'   => 'Auto-alpha oleh sistem',
                'dicatat_oleh' => Auth::id(),
            ]);
            $jumlah++;
        }

        return redirect()->route('absensi.kelola', ['tanggal' => $tanggal])
            ->with('success', "{$jumlah} guru ditandai alpha.");
    }

    // =========================================================================
    // LAPORAN — view HTML (untuk ditampilkan di browser)
    // =========================================================================

    public function laporan(Request $request)
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
            $pct     = $hariKerja > 0 ? round(($hadir / $hariKerja) * 100) : 0;

            return [
                'guru'       => $guru,
                'hadir'      => $hadir,
                'terlambat'  => $absensi->where('status', 'terlambat')->count(),
                'izin'       => $absensi->where('status', 'izin')->count(),
                'sakit'      => $absensi->where('status', 'sakit')->count(),
                'tugas_luar' => $absensi->where('status', 'tugas_luar')->count(),
                'alpha'      => $absensi->where('status', 'alpha')->count(),
                'hari_kerja' => $hariKerja,
                'persentase' => $pct,
            ];
        });

        return view('absensi.laporan', compact('rekap', 'bulan', 'tahun', 'hariKerja'));
    }

    // =========================================================================
    // CETAK PDF — laporan lengkap per tanggal (untuk DomPDF)
    // =========================================================================

    public function cetakLaporan(Request $request)
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
            $pct     = $hariKerja > 0 ? round(($hadir / $hariKerja) * 100) : 0;

            return [
                'guru'           => $guru,
                'absensi_harian' => $absensi->map(fn($a) => [
                    'tanggal'    => $a->tanggal->toDateString(),
                    'status'     => $a->status,
                    'keterangan' => $a->keterangan,
                ])->values()->toArray(),
                'hadir'      => $hadir,
                'terlambat'  => $absensi->where('status', 'terlambat')->count(),
                'izin'       => $absensi->where('status', 'izin')->count(),
                'sakit'      => $absensi->where('status', 'sakit')->count(),
                'tugas_luar' => $absensi->where('status', 'tugas_luar')->count(),
                'alpha'      => $absensi->where('status', 'alpha')->count(),
                'hari_kerja' => $hariKerja,
                'persentase' => $pct,
            ];
        });

        $ringkasan = [
            'total_guru'      => $guruAktif->count(),
            'rata_hadir'      => $rekap->count() > 0 ? round($rekap->avg('hadir'), 1) : 0,
            'rata_alpha'      => $rekap->count() > 0 ? round($rekap->avg('alpha'), 1) : 0,
            'rata_persentase' => $rekap->count() > 0 ? round($rekap->avg('persentase'), 1) : 0,
        ];

        $namaBulan = Carbon::create(null, $bulan)->translatedFormat('F');

        $pdf = Pdf::loadView('absensi.laporan-pdf', compact(
                'rekap', 'bulan', 'tahun', 'hariKerja', 'ringkasan', 'namaBulan'
            ))
            ->setPaper('a3', 'landscape')
            ->setOptions([
                'defaultFont'          => 'Arial',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'dpi'                  => 120,
            ]);

        return $pdf->stream("laporan-absensi-{$namaBulan}-{$tahun}.pdf");
    }

    // =========================================================================
    // Helper private
    // =========================================================================

    /**
     * Tentukan status (hadir/terlambat) dan menit terlambat dari jam masuk.
     */
    private function tentukanStatus(string $jam): array
    {
        $jamMasuk    = config('sekolah.jam_masuk', self::JAM_MASUK);
        $jamTerlambat = config('sekolah.jam_terlambat', self::JAM_TERLAMBAT);

        $waktu      = Carbon::createFromFormat('H:i', $jam);
        $batasHadir = Carbon::createFromFormat('H:i', $jamTerlambat);

        if ($waktu->lessThanOrEqualTo($batasHadir)) {
            return ['hadir', 0];
        }

        $normal = Carbon::createFromFormat('H:i', $jamMasuk);
        $menit  = (int) $waktu->diffInMinutes($normal);

        return ['terlambat', $menit];
    }

    /**
     * Hitung menit terlambat dari jam string (H:i).
     */
    private function hitungTerlambat(string $jam): int
    {
        $jamMasuk = config('sekolah.jam_masuk', self::JAM_MASUK);
        $waktu    = Carbon::createFromFormat('H:i', $jam);
        $normal   = Carbon::createFromFormat('H:i', $jamMasuk);

        return max(0, (int) $waktu->diffInMinutes($normal));
    }

    /**
     * Hitung jumlah hari kerja efektif (Senin–Jumat) dalam satu bulan.
     */
    private function hitungHariKerja(int $bulan, int $tahun): int
    {
        $start = Carbon::create($tahun, $bulan, 1)->startOfDay();
        $end   = $start->copy()->endOfMonth();
        $hari  = 0;

        while ($start->lte($end)) {
            if ($start->isWeekday()) {
                $hari++;
            }
            $start->addDay();
        }

        return $hari;
    }
}