<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom roles sebagai JSON array
            $table->json('roles')->nullable()->after('role');
        });

        // Isi kolom roles dari kolom role yang sudah ada
        DB::statement("UPDATE users SET roles = JSON_ARRAY(role)");
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('roles');
        });
    }
};