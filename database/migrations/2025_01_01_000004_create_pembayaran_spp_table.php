<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_spp', function (Blueprint $table) {
            $table->id();

            $table->foreignId('siswa_id')
                  ->constrained('siswa')
                  ->cascadeOnDelete();

            $table->tinyInteger('bulan')->unsigned(); // 1–12
            $table->year('tahun');

            $table->integer('nominal_spp')->unsigned()->default(50000);
            $table->integer('nominal_kebersihan')->unsigned()->default(5000);
            $table->integer('total')->unsigned();

            $table->date('tanggal_bayar');

            $table->foreignId('dicatat_oleh')
                  ->constrained('users');

            $table->string('no_kwitansi', 25)->unique();

            $table->timestamps();

            $table->unique(['siswa_id', 'bulan', 'tahun'], 'uq_siswa_bulan_tahun');
            $table->index(['bulan', 'tahun'], 'idx_bulan_tahun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_spp');
    }
};
