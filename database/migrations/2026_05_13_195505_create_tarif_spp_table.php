<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarif_spp', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('nominal_spp')->default(150000);
            $table->unsignedInteger('nominal_kebersihan')->default(25000);
            $table->unsignedSmallInteger('tahun_berlaku');
            $table->string('keterangan', 200)->nullable();
            $table->foreignId('dibuat_oleh')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamps();

            $table->unique('tahun_berlaku');
        });

        // Seed tarif default untuk tahun berjalan
        DB::table('tarif_spp')->insert([
            'nominal_spp'        => 50000,
            'nominal_kebersihan' => 5000,
            'tahun_berlaku'      => (int) date('Y'),
            'keterangan'         => 'Tarif awal — silakan sesuaikan',
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tarif_spp');
    }
};