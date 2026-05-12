<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin / Operator ─────────────────────────────────
        User::create([
            'name'               => 'Administrator',
            'email'              => null,
            'username'           => 'admin',
            'password'           => Hash::make('admin123'),
            'role'               => 'admin',
            'aktif'              => true,
            'nama_lengkap'       => 'Administrator',
            'jenis_kelamin'      => 'L',
            'status_kepegawaian' => 'honorer',
            'jabatan'            => 'Operator Sistem',
        ]);

        // ── Kepala Sekolah ────────────────────────────────────
        User::create([
            'name'               => 'Kepsek',
            'email'              => null,
            'username'           => 'kepsek',
            'password'           => Hash::make('kepsek123'),
            'role'               => 'kepala_sekolah',
            'aktif'              => true,
            'nama_lengkap'       => 'Nama Kepala Sekolah',
            'nik'                => '1571010101800001',
            'nip'                => null,
            'nuptk'              => null,
            'jenis_kelamin'      => 'P',
            'tempat_lahir'       => 'Jambi',
            'tanggal_lahir'      => '1980-01-01',
            'alamat'             => 'Jambi',
            'no_hp'              => '08110000001',
            'status_kepegawaian' => 'honorer',
            'jabatan'            => 'Kepala Sekolah',
            'pendidikan_terakhir'=> 'S1',
            'jurusan'            => 'PAUD',
            'tanggal_bergabung'  => '2015-01-01',
        ]);

        // ── Bendahara ─────────────────────────────────────────
        User::create([
            'name'               => 'Bendahara',
            'email'              => null,
            'username'           => 'bendahara',
            'password'           => Hash::make('bendahara123'),
            'role'               => 'bendahara',
            'aktif'              => true,
            'nama_lengkap'       => 'Mira Lestari',
            'nik'                => '1571010101850002',
            'nip'                => null,
            'nuptk'              => null,
            'jenis_kelamin'      => 'P',
            'tempat_lahir'       => 'Jambi',
            'tanggal_lahir'      => '1985-06-15',
            'alamat'             => 'Jambi',
            'no_hp'              => '08110000002',
            'status_kepegawaian' => 'honorer',
            'jabatan'            => 'Bendahara',
            'pendidikan_terakhir'=> 'S1',
            'jurusan'            => 'Akuntansi',
            'tanggal_bergabung'  => '2018-01-01',
        ]);
    }
}