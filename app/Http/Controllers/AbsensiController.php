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
    // =========================================================================
    // Konstanta Jam Kerja
    // Jam masuk resmi       : 08:00
    // Toleransi terlambat   : 15 menit → batas tetap hadir s/d 08:15
    // Setelah 08:15         : status "terlambat", catat selisih dari 08:00
    // Cut-off check-in      : 10:30 → setelah ini tidak bisa absen mandiri
    // Cut-off lapor izin    : 09:00 → setelah ini tidak bisa lapor izin/sakit/tugas_luar
    // =========================================================================
    const JAM_MASUK          = '08:00';   // jam masuk resmi
    const JAM_TOLERANSI      = '08:15';   // batas akhir masih dianggap "hadir"
    const JAM_CUTOFF_CHECKIN = '10:30';   // setelah ini check-in mandiri ditutup
    const JAM_MULAI_IZIN     = '00:01';
    const JAM_CUTOFF_IZIN    = '09:00';   // setelah ini lapor izin/sakit/tugas_luar ditutup

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

        // Kirim info window waktu ke view agar bisa ditampilkan ke guru
        $infoWaktu = $this->infoWindowWaktu();

        return view('absensi.index', compact('user', 'absenHariIni', 'absenBulanIni', 'infoWaktu'));
    }

    /**
     * Proses self check-in guru via GPS (JSON response untuk Alpine.js)
     */
    public function checkIn(Request $request)
    {
        $user = Auth::user();
        abort_unless($user->isGuru(), 403);

        // ── 1. Cek sudah absen hari ini ─────────────────────────────────────
        if ($user->sudahAbsenHariIni()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi hari ini.',
            ], 422);
        }

        // ── 2. Cek window waktu check-in ────────────────────────────────────
        $sekarang      = now();
        $cutoffCheckIn = today()->setTimeFromTimeString($this->getJamCutoffCheckIn());

        if ($sekarang->greaterThan($cutoffCheckIn)) {
            return response()->json([
                'success' => false,
                'message' => "Batas waktu absensi mandiri sudah lewat (maks. pukul {$this->getJamCutoffCheckIn()}). Hubungi admin untuk pencatatan manual.",
            ], 422);
        }

        // ── 3. Validasi koordinat GPS ────────────────────────────────────────
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

        // ── 4. Tentukan status & menit terlambat ─────────────────────────────
        $jamSekarang = $sekarang->format('H:i');
        [$status, $terlambatMenit] = $this->tentukanStatus($jamSekarang);

        // ── 5. Simpan ke database ────────────────────────────────────────────
        AbsensiGuru::create([
            'guru_id'         => $user->id,
            'tanggal'         => today(),
            'jam_masuk'       => $sekarang->format('H:i:s'),
            'status'          => $status,
            'terlambat_menit' => $terlambatMenit,
            'latitude'        => $lat,
            'longitude'       => $lng,
            'jarak_meter'     => $geo['jarak_meter'],
            'lokasi_valid'    => true,
            'dicatat_oleh'    => null,
        ]);

        // ── 6. Susun pesan balasan ────────────────────────────────────────────
        $pesan = match ($status) {
            'hadir'     => 'Absensi berhasil! Selamat bekerja.',
            'terlambat' => "Absensi berhasil dicatat. Anda terlambat {$terlambatMenit} menit dari jam masuk (" . $this->getJamMasuk() . ").",
            default     => 'Absensi berhasil dicatat.',
        };

        return response()->json([
            'success'          => true,
            'message'          => $pesan,
            'status'           => $status,
            'jam_masuk'        => $sekarang->format('H:i'),
            'terlambat_menit'  => $terlambatMenit,
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

        $infoWaktu = $this->infoWindowWaktu();

        return view('absensi.izin', compact('absenHariIni', 'infoWaktu'));
    }

    /**
     * Simpan laporan izin / sakit / tugas_luar dari guru
     */
    public function storeIzin(Request $request)
    {
        $user = Auth::user();
        abort_unless($user->isGuru(), 403);

        // ── 1. Cek sudah absen hari ini ─────────────────────────────────────
        if (AbsensiGuru::where('guru_id', $user->id)->whereDate('tanggal', today())->exists()) {
            return redirect()->route('absensi.izin.form')
                ->with('error', 'Anda sudah memiliki catatan absensi hari ini.');
        }

        $sekarang  = now();
        $jamMulai  = today()->setTimeFromTimeString($this->getJamMulaiIzin());
        $jamCutoff = today()->setTimeFromTimeString($this->getJamCutoffIzin());

        if ($sekarang->lessThan($jamMulai)) {
            return redirect()->route('absensi.izin.form')
                ->with('error', "Laporan izin hanya bisa dikirim mulai pukul {$this->getJamMulaiIzin()}.");
        }

        if ($sekarang->greaterThan($jamCutoff)) {
            return redirect()->route('absensi.izin.form')
                ->with('error', "Batas waktu laporan izin/sakit/tugas luar adalah pukul {$this->getJamCutoffIzin()}. Silakan hubungi admin untuk pencatatan manual.");
        }

        // ── 3. Validasi input ────────────────────────────────────────────────
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

        // ── 4. Simpan ke database ────────────────────────────────────────────
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
     * (Admin tidak dibatasi oleh jam — bisa input kapan saja, termasuk hari lalu)
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
    // LAPORAN — view HTML
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

        // ── Ringkasan — FIX: variabel ini wajib dikirim ke view ──────────────
        $ringkasan = [
            'total_guru'      => $guruAktif->count(),
            'rata_hadir'      => $rekap->count() > 0 ? round($rekap->avg('hadir'), 1) : 0,
            'rata_alpha'      => $rekap->count() > 0 ? round($rekap->avg('alpha'), 1) : 0,
            'rata_persentase' => $rekap->count() > 0 ? round($rekap->avg('persentase'), 1) : 0,
        ];

        return view('absensi.laporan', compact('rekap', 'bulan', 'tahun', 'hariKerja', 'ringkasan'));
    }

    // =========================================================================
    // CETAK PDF
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
     * Tentukan status (hadir/terlambat) dan menit terlambat.
     */
    private function tentukanStatus(string $jam): array
    {
        $jamMasuk    = $this->getJamMasuk();
        $jamTolerasi = $this->getJamTolerasi();

        $waktu       = Carbon::createFromFormat('H:i', $jam);
        $batasHadir  = Carbon::createFromFormat('H:i', $jamTolerasi);

        if ($waktu->lessThanOrEqualTo($batasHadir)) {
            return ['hadir', 0];
        }

        $normal = Carbon::createFromFormat('H:i', $jamMasuk);
        $menit  = (int) $waktu->diffInMinutes($normal);

        return ['terlambat', $menit];
    }

    /**
     * Hitung menit terlambat dari jam string (H:i) — digunakan input manual admin.
     */
    private function hitungTerlambat(string $jam): int
    {
        $jamMasuk = $this->getJamMasuk();
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

    /**
     * Kembalikan array info window waktu untuk dikirim ke view.
     */
    private function infoWindowWaktu(): array
    {
        return [
            'jam_masuk'          => $this->getJamMasuk(),
            'jam_toleransi'      => $this->getJamTolerasi(),
            'jam_cutoff_checkin' => $this->getJamCutoffCheckIn(),
            'jam_mulai_izin'     => $this->getJamMulaiIzin(),
            'jam_cutoff_izin'    => $this->getJamCutoffIzin(),
        ];
    }

    // ── Getter jam (config > konstanta) ──────────────────────────────────────

    private function getJamMasuk(): string
    {
        return config('sekolah.jam_masuk', self::JAM_MASUK);
    }

    private function getJamTolerasi(): string
    {
        return config('sekolah.jam_toleransi', self::JAM_TOLERANSI);
    }

    private function getJamCutoffCheckIn(): string
    {
        return config('sekolah.jam_cutoff_checkin', self::JAM_CUTOFF_CHECKIN);
    }

    private function getJamMulaiIzin(): string
    {
        return config('sekolah.jam_mulai_izin', self::JAM_MULAI_IZIN);
    }

    private function getJamCutoffIzin(): string
    {
        return config('sekolah.jam_cutoff_izin', self::JAM_CUTOFF_IZIN);
    }
}