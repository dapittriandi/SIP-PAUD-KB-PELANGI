<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();

            // ── Identitas Siswa ───────────────────────────────
            $table->string('nis', 20)->nullable()->unique(); // Nomor Induk Siswa
            $table->string('nama_lengkap', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('agama', 20)->nullable();         // Islam, Kristen, dll
            $table->text('alamat')->nullable();
            $table->string('foto', 255)->nullable();         // path foto siswa di storage

            // ── Data Akademik ─────────────────────────────────
            // Saat ini hanya ada KB, tambah enum jika ada kelas baru:
            // $table->enum('kelompok', ['KB', 'TK_A', 'TK_B'])->default('KB');
            $table->enum('kelompok', ['KB'])->default('KB');
            $table->date('tanggal_masuk')->nullable();       // tanggal pertama masuk sekolah
            $table->string('tahun_ajaran', 10)->nullable();  // contoh: 2025/2026
            $table->boolean('aktif')->default(true);
            $table->date('tanggal_keluar')->nullable();      // diisi jika siswa keluar/pindah
            $table->string('keterangan_keluar', 255)->nullable();

            // ── Data Ayah ─────────────────────────────────────
            $table->string('nama_ayah', 100)->nullable();
            $table->string('nik_ayah', 20)->nullable();
            $table->string('tempat_lahir_ayah', 100)->nullable();
            $table->date('tanggal_lahir_ayah')->nullable();
            $table->string('pendidikan_ayah', 20)->nullable(); // SD, SMP, SMA, D3, S1, dll
            $table->string('pekerjaan_ayah', 100)->nullable();
            $table->string('no_hp_ayah', 20)->nullable();

            // ── Data Ibu ──────────────────────────────────────
            $table->string('nama_ibu', 100)->nullable();
            $table->string('nik_ibu', 20)->nullable();
            $table->string('tempat_lahir_ibu', 100)->nullable();
            $table->date('tanggal_lahir_ibu')->nullable();
            $table->string('pendidikan_ibu', 20)->nullable();
            $table->string('pekerjaan_ibu', 100)->nullable();
            $table->string('no_hp_ibu', 20)->nullable();

            // ── Wali (jika bukan ayah/ibu) ───────────────────
            // Diisi hanya jika yang mengantar/bertanggung jawab adalah wali
            $table->string('nama_wali', 100)->nullable();
            $table->string('hubungan_wali', 50)->nullable();  // contoh: Kakek, Paman, dll
            $table->string('no_hp_wali', 20)->nullable();     // utama untuk notif WA

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
