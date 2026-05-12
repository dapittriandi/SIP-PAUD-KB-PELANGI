<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        // ── 4 Guru PAUD KB Pelangi ────────────────────────────
        // Sesuaikan semua data dengan data asli masing-masing guru
        // PIN adalah kode 4 digit yang dipakai guru untuk login di HP

        $gurus = [
            [
                'username'            => 'guru1',
                'pin'                 => '1111',               // ⚠️ GANTI!
                'nama_lengkap'        => 'Nama Guru 1',        // ← sesuaikan
                'nik'                 => '1571010101900001',   // ← sesuaikan
                'nuptk'               => null,                 // ← isi jika ada
                'jenis_kelamin'       => 'P',
                'tempat_lahir'        => 'Jambi',              // ← sesuaikan
                'tanggal_lahir'       => '1990-03-10',         // ← sesuaikan
                'alamat'              => 'Jambi',              // ← sesuaikan
                'no_hp'               => '08110000003',        // ← sesuaikan
                'status_kepegawaian'  => 'honorer',            // ← sesuaikan
                'jabatan'             => 'Guru Kelas KB',
                'pendidikan_terakhir' => 'S1',
                'jurusan'             => 'PAUD',
                'tanggal_bergabung'   => '2019-07-01',         // ← sesuaikan
            ],
            [
                'username'            => 'guru2',
                'pin'                 => '2222',               // ⚠️ GANTI!
                'nama_lengkap'        => 'Nama Guru 2',        // ← sesuaikan
                'nik'                 => '1571010101920002',   // ← sesuaikan
                'nuptk'               => null,
                'jenis_kelamin'       => 'P',
                'tempat_lahir'        => 'Jambi',
                'tanggal_lahir'       => '1992-07-22',
                'alamat'              => 'Jambi',
                'no_hp'               => '08110000004',
                'status_kepegawaian'  => 'honorer',
                'jabatan'             => 'Guru Kelas KB',
                'pendidikan_terakhir' => 'S1',
                'jurusan'             => 'PAUD',
                'tanggal_bergabung'   => '2020-01-01',
            ],
            [
                'username'            => 'guru3',
                'pin'                 => '3333',               // ⚠️ GANTI!
                'nama_lengkap'        => 'Nama Guru 3',        // ← sesuaikan
                'nik'                 => '1571010101880003',   // ← sesuaikan
                'nuptk'               => null,
                'jenis_kelamin'       => 'P',
                'tempat_lahir'        => 'Jambi',
                'tanggal_lahir'       => '1988-11-05',
                'alamat'              => 'Jambi',
                'no_hp'               => '08110000005',
                'status_kepegawaian'  => 'honorer',
                'jabatan'             => 'Guru Pendamping KB',
                'pendidikan_terakhir' => 'S1',
                'jurusan'             => 'Psikologi',
                'tanggal_bergabung'   => '2021-07-01',
            ],
            [
                'username'            => 'guru4',
                'pin'                 => '4444',               // ⚠️ GANTI!
                'nama_lengkap'        => 'Nama Guru 4',        // ← sesuaikan
                'nik'                 => '1571010101950004',   // ← sesuaikan
                'nuptk'               => null,
                'jenis_kelamin'       => 'P',
                'tempat_lahir'        => 'Jambi',
                'tanggal_lahir'       => '1995-02-14',
                'alamat'              => 'Jambi',
                'no_hp'               => '08110000006',
                'status_kepegawaian'  => 'honorer',
                'jabatan'             => 'Guru Pendamping KB',
                'pendidikan_terakhir' => 'S1',
                'jurusan'             => 'PAUD',
                'tanggal_bergabung'   => '2022-01-01',
            ],
        ];

        foreach ($gurus as $g) {
            User::create([
                'username'            => $g['username'],
                'pin'                 => Hash::make($g['pin']),
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
            ]);
        }
    }
}