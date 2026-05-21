<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Hapus kolom yang sudah ada di migration default Laravel
        // Tambahkan hanya kolom BARU milik kamu
        $table->string('username', 50)->unique()->after('id');
        $table->string('pin', 255)->nullable()->after('password');
        $table->enum('role', ['admin', 'bendahara', 'kepala_sekolah', 'guru'])->after('pin');
        $table->boolean('aktif')->default(1)->after('role');
        $table->string('nama_lengkap', 100)->after('aktif');
        $table->string('nik', 20)->nullable()->after('nama_lengkap');
        $table->string('nip', 30)->nullable()->after('nik');
        $table->string('nuptk', 20)->nullable()->after('nip');
        $table->enum('jenis_kelamin', ['L', 'P'])->after('nuptk');
        $table->string('tempat_lahir', 100)->nullable()->after('jenis_kelamin');
        $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
        $table->text('alamat')->nullable()->after('tanggal_lahir');
        $table->string('no_hp', 20)->nullable()->after('alamat');
        $table->string('foto', 255)->nullable()->after('no_hp');
        $table->enum('status_kepegawaian', ['pns', 'pppk', 'honorer', 'gtty', 'GTY/PTY'])->nullable()->after('foto');
        $table->string('jabatan', 100)->nullable()->after('status_kepegawaian');
        $table->string('pendidikan_terakhir', 10)->nullable()->after('jabatan');
        $table->string('jurusan', 100)->nullable()->after('pendidikan_terakhir');
        $table->date('tanggal_bergabung')->nullable()->after('jurusan');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'username', 'pin', 'role', 'aktif', 'nama_lengkap',
            'nik', 'nip', 'nuptk', 'jenis_kelamin', 'tempat_lahir',
            'tanggal_lahir', 'alamat', 'no_hp', 'foto',
            'status_kepegawaian', 'jabatan', 'pendidikan_terakhir',
            'jurusan', 'tanggal_bergabung'
        ]);
    });
}
};
