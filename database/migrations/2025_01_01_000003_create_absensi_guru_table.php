<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_guru', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guru_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();

            $table->enum('status', [
                'hadir',
                'terlambat',
                'izin',
                'sakit',
                'tugas_luar',
                'alpha',
            ]);

            $table->smallInteger('terlambat_menit')->unsigned()->nullable();
            $table->text('keterangan')->nullable();

            // GPS — hanya untuk hadir/terlambat
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->smallInteger('jarak_meter')->unsigned()->nullable();
            $table->boolean('lokasi_valid')->nullable();

            // Audit — NULL berarti self check-in guru
            $table->foreignId('dicatat_oleh')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();

            $table->unique(['guru_id', 'tanggal'], 'uq_guru_tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_guru');
    }
};
