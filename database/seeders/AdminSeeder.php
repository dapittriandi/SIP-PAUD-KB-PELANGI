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
            'name'               => 'Bella Kurnia Sari',
            'email'              => null,
            'username'           => 'bella',
            'password'           => Hash::make('bella123'),
            'role'               => 'admin',
            'aktif'              => true,
            'nama_lengkap'       => 'BELLA KURNIA SARI',
            'nik'                => '1506084412000000',
            'nip'                => null,
            'nuptk'              => null,
            'jenis_kelamin'      => 'P',
            'tempat_lahir'       => 'Pulau Pauh',
            'tanggal_lahir'      => '2000-12-04',
            'alamat'             => 'Pulau Pauh',
            'no_hp'              => '082269340000',
            'status_kepegawaian' => 'guru honor sekolah',
            'jabatan'            => 'Operator Sistem',
            'pendidikan_terakhir'=> 'S1',
            'jurusan'            => null,
            'tanggal_bergabung'  => '2023-12-02',
        ]);

        // ── Kepala Sekolah ────────────────────────────────────
        User::create([
            'name'               => 'Renti Putriyani',
            'email'              => null,
            'username'           => 'kepsek',
            'password'           => Hash::make('renti123'),
            'role'               => 'kepala_sekolah',
            'aktif'              => true,
            'nama_lengkap'       => 'RENTI PUTRIYANI',
            'nik'                => '1571076904960060',
            'nip'                => null,
            'nuptk'              => null,
            'jenis_kelamin'      => 'P',
            'tempat_lahir'       => null,
            'tanggal_lahir'      => '1996-01-01',
            'alamat'             => 'Pulau Pauh',
            'no_hp'              => null,
            'status_kepegawaian' => null,
            'jabatan'            => 'Kepala Sekolah',
            'pendidikan_terakhir'=> 'S1',
            'jurusan'            => null,
            'tanggal_bergabung'  => null,
        ]);

        // ── Bendahara ─────────────────────────────────────────
        User::create([
            'name'               => 'Nuriah',
            'email'              => null,
            'username'           => 'bendahara',
            'password'           => Hash::make('nuriah123'),
            'role'               => 'bendahara',
            'aktif'              => true,
            'nama_lengkap'       => 'NURIAH',
            'nik'                => '1506085002820000',
            'nip'                => null,
            'nuptk'              => null,
            'jenis_kelamin'      => 'P',
            'tempat_lahir'       => 'Jambi',
            'tanggal_lahir'      => '1982-02-10',
            'alamat'             => 'Pulau Pauh',
            'no_hp'              => '085368190000',
            'status_kepegawaian' => 'GTY/PTY',
            'jabatan'            => 'Bendahara',
            'pendidikan_terakhir'=> 'SMA',
            'jurusan'            => null,
            'tanggal_bergabung'  => '2017-01-09',
        ]);

    }
}