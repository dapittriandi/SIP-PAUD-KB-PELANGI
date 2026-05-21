<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $gurus = [
            [
                'username'            => 'mira',
                'password'            => 'mira123',
                'pin'                 => '3456',
                'nama_lengkap'        => 'MIRA LESTARI',
                'nik'                 => '1506084211960001',
                'nuptk'               => null,
                'jenis_kelamin'       => 'P',
                'tempat_lahir'        => 'Pulau Pauh',
                'tanggal_lahir'       => null,
                'alamat'              => 'Pulau Pauh',
                'no_hp'               => '082282330000',
                'status_kepegawaian'  => 'honorer',
                'jabatan'             => 'Guru',
                'pendidikan_terakhir' => 'S1',
                'jurusan'             => 'PG PAUD',
                'tanggal_bergabung'   => '2023-01-02',
                'roles'               => ['guru'],
            ],
            [
                'username'            => 'renti',
                'password'            => 'renti123',
                'pin'                 => '5678',
                'nama_lengkap'        => 'RENTI PUTRIYANI',
                'nik'                 => '1571076904960060',
                'nuptk'               => null,
                'jenis_kelamin'       => 'P',
                'tempat_lahir'        => null,
                'tanggal_lahir'       => '1996-01-01',
                'alamat'              => 'Pulau Pauh',
                'no_hp'               => null,
                'status_kepegawaian'  => null,
                'jabatan'             => 'Kepala Sekolah',
                'pendidikan_terakhir' => 'S1',
                'jurusan'             => null,
                'tanggal_bergabung'   => null,
                'roles'               => ['kepala_sekolah', 'guru'],
            ],
        ];

        foreach ($gurus as $g) {
            // Skip jika sudah dibuat di AdminSeeder
            if (User::where('username', $g['username'])->exists()) {
                // Hanya update PIN dan roles
                User::where('username', $g['username'])->update([
                    'pin'   => Hash::make($g['pin']),
                    'roles' => $g['roles'],
                ]);
                continue;
            }

            User::create([
                'name'                => $g['nama_lengkap'],
                'email'               => null,
                'username'            => $g['username'],
                'password'            => Hash::make($g['password']),
                'pin'                 => Hash::make($g['pin']),
                'role'                => 'guru',
                'roles'               => $g['roles'],
                'aktif'               => true,
                'nama_lengkap'        => $g['nama_lengkap'],
                'nik'                 => $g['nik'],
                'nuptk'               => $g['nuptk'],
                'jenis_kelamin'       => $g['jenis_kelamin'],
                'tempat_lahir'        => $g['tempat_lahir'],
                'tanggal_lahir'       => $g['tanggal_lahir'],
                'alamat'              => $g['alamat'],
                'no_hp'               => $g['no_hp'],
                'status_kepegawaian'  => $g['status_kepegawaian'],
                'jabatan'             => $g['jabatan'],
                'pendidikan_terakhir' => $g['pendidikan_terakhir'],
                'jurusan'             => $g['jurusan'],
                'tanggal_bergabung'   => $g['tanggal_bergabung'],
            ]);
        }
    }
}