<?php

namespace App\Console\Commands;

use App\Models\AbsensiGuru;
use App\Models\User;
use Illuminate\Console\Command;

class AutoAlphaCommand extends Command
{
    /**
     * Nama dan signature command.
     * Jalankan manual: php artisan absensi:auto-alpha
     * Jalankan untuk tanggal tertentu: php artisan absensi:auto-alpha --tanggal=2025-05-10
     */
    protected $signature = 'absensi:auto-alpha
                            {--tanggal= : Tanggal target (format Y-m-d). Default: hari ini.}
                            {--dry-run : Simulasi tanpa menyimpan ke database.}';

    protected $description = 'Tandai guru yang belum absen pada tanggal tertentu sebagai Alpha.';

    public function handle(): int
    {
        $tanggal = $this->option('tanggal')
            ? \Carbon\Carbon::parse($this->option('tanggal'))->toDateString()
            : today()->toDateString();

        $dryRun = $this->option('dry-run');

        $this->info("Auto-Alpha — tanggal: {$tanggal}" . ($dryRun ? ' [DRY RUN]' : ''));
        $this->newLine();

        // Ambil semua guru aktif
        $guruAktif = User::where('role', 'guru')
                         ->where('aktif', true)
                         ->get(['id', 'nama_lengkap']);

        if ($guruAktif->isEmpty()) {
            $this->warn('Tidak ada guru aktif ditemukan.');
            return self::SUCCESS;
        }

        // Guru yang sudah absen pada tanggal ini
        $sudahAbsenIds = AbsensiGuru::whereDate('tanggal', $tanggal)
                             ->pluck('guru_id')
                             ->toArray();

        // Guru yang belum absen
        $belumAbsen = $guruAktif->filter(fn($g) => !in_array($g->id, $sudahAbsenIds));

        if ($belumAbsen->isEmpty()) {
            $this->info('✓ Semua guru sudah memiliki record absensi hari ini.');
            return self::SUCCESS;
        }

        $this->line("Guru belum absen ({$belumAbsen->count()} orang):");

        $jumlah = 0;
        foreach ($belumAbsen as $guru) {
            $this->line("  → {$guru->nama_lengkap}");

            if (!$dryRun) {
                AbsensiGuru::create([
                    'guru_id'      => $guru->id,
                    'tanggal'      => $tanggal,
                    'jam_masuk'    => null,
                    'status'       => 'alpha',
                    'keterangan'   => 'Auto-alpha oleh sistem (scheduler 23:59)',
                    'dicatat_oleh' => null,
                ]);
            }

            $jumlah++;
        }

        $this->newLine();
        if ($dryRun) {
            $this->warn("[DRY RUN] {$jumlah} guru akan ditandai Alpha (tidak disimpan).");
        } else {
            $this->info("✓ {$jumlah} guru berhasil ditandai Alpha.");
        }

        return self::SUCCESS;
    }
}