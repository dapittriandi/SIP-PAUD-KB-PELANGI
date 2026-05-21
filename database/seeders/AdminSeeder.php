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
            'pin'                => Hash::make('2345'),
            'role'               => 'admin',
            'roles'              => ['admin', 'guru'], // multi-role
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
            'status_kepegawaian' => 'honorer',
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
            'pin'                => null,
            'role'               => 'kepala_sekolah',
            'roles'              => ['kepala_sekolah', 'guru'],
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
            'pin'                => Hash::make('4567'),
            'role'               => 'bendahara',
            'roles'              => ['bendahara', 'guru'], // multi-role
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
            'status_kepegawaian' => 'gtty',
            'jabatan'            => 'Bendahara',
            'pendidikan_terakhir'=> 'SMA',
            'jurusan'            => null,
            'tanggal_bergabung'  => '2017-01-09',
        ]);
    }
}