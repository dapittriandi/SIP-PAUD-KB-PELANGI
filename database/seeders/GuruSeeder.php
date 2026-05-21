<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        // ── Guru KB Pelangi ───────────────────────────────────
        // Semua staf berperan sebagai guru dalam operasional kelas.
        // Role struktural (admin, bendahara, kepsek) diatur di AdminSeeder.
        // PIN = 4 digit kode login di HP.

        $gurus = [
            [
                'username'            => 'bella',
                'pin'                 => '2345',
                'nama_lengkap'        => 'BELLA KURNIA SARI',
                'nik'                 => '1506084412000000',
                'nuptk'               => null,
                'jenis_kelamin'       => 'P',
                'tempat_lahir'        => 'Pulau Pauh',
                'tanggal_lahir'       => '2000-12-04',
                'alamat'              => 'Pulau Pauh',
                'no_hp'               => '082269340000',
                'status_kepegawaian'  => 'guru honor sekolah',
                'jabatan'             => 'Operator Sistem',
                'pendidikan_terakhir' => 'S1',
                'jurusan'             => null,
                'tanggal_bergabung'   => '2023-12-02',
            ],
            [
                'username'            => 'mira',
                'pin'                 => '3456',
                'nama_lengkap'        => 'MIRA LESTARI',
                'nik'                 => '1506084211960001',
                'nuptk'               => null,
                'jenis_kelamin'       => 'P',
                'tempat_lahir'        => 'Pulau Pauh',
                'tanggal_lahir'       => null,
                'alamat'              => 'Pulau Pauh',
                'no_hp'               => '082282330000',
                'status_kepegawaian'  => 'guru honor sekolah',
                'jabatan'             => 'Guru',
                'pendidikan_terakhir' => 'S1',
                'jurusan'             => 'PG PAUD',
                'tanggal_bergabung'   => '2023-01-02',
            ],
            [
                'username'            => 'nuriah',
                'pin'                 => '4567',
                'nama_lengkap'        => 'NURIAH',
                'nik'                 => '1506085002820000',
                'nuptk'               => null,
                'jenis_kelamin'       => 'P',
                'tempat_lahir'        => 'Jambi',
                'tanggal_lahir'       => '1982-02-10',
                'alamat'              => 'Pulau Pauh',
                'no_hp'               => '085368190000',
                'status_kepegawaian'  => 'GTY/PTY',
                'jabatan'             => 'Bendahara',
                'pendidikan_terakhir' => 'SMA',
                'jurusan'             => null,
                'tanggal_bergabung'   => '2017-01-09',
            ],
            [
                'username'            => 'renti',
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
            ],
        ];

        foreach ($gurus as $g) {
            // Gunakan updateOrCreate agar tidak duplikat jika AdminSeeder sudah jalan
            User::updateOrCreate(
                ['username' => $g['username']],
                [
                    'password'            => Hash::make($g['pin']),
                    'role'                => 'guru',
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
                ]
            );
        }
    }
}