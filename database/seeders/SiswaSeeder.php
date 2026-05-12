<?php

namespace Database\Seeders;

use App\Models\Siswa;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        // ── Contoh Data Siswa PAUD KB Pelangi ────────────────
        // Ini adalah DATA CONTOH — ganti/tambah dengan data siswa asli (45 siswa)
        // Salin dan duplikat blok array di bawah untuk setiap siswa

        $siswa = [
            [
                // ── Identitas Siswa ──
                'nis'                  => '2025001',            // ← sesuaikan / buat format NIS sendiri
                'nama_lengkap'         => 'Nama Siswa 1',       // ← sesuaikan
                'jenis_kelamin'        => 'L',
                'tempat_lahir'         => 'Jambi',
                'tanggal_lahir'        => '2020-03-15',
                'agama'                => 'Islam',
                'alamat'               => 'Jl. Contoh No. 1, Jambi',
                // ── Akademik ──
                'kelompok'             => 'KB',
                'tanggal_masuk'        => '2024-07-15',
                'tahun_ajaran'         => '2024/2025',
                'aktif'                => true,
                // ── Data Ayah ──
                'nama_ayah'            => 'Nama Ayah 1',
                'nik_ayah'             => '1571010101850011',
                'tempat_lahir_ayah'    => 'Jambi',
                'tanggal_lahir_ayah'   => '1985-05-10',
                'pendidikan_ayah'      => 'S1',
                'pekerjaan_ayah'       => 'Karyawan Swasta',
                'no_hp_ayah'           => '08120000001',
                // ── Data Ibu ──
                'nama_ibu'             => 'Nama Ibu 1',
                'nik_ibu'              => '1571010101880011',
                'tempat_lahir_ibu'     => 'Jambi',
                'tanggal_lahir_ibu'    => '1988-08-20',
                'pendidikan_ibu'       => 'S1',
                'pekerjaan_ibu'        => 'Ibu Rumah Tangga',
                'no_hp_ibu'            => '08130000001',
                // ── Wali (kosongkan jika orang tua sendiri yang antar) ──
                'nama_wali'            => null,
                'hubungan_wali'        => null,
                'no_hp_wali'           => null,
            ],
            [
                'nis'                  => '2025002',
                'nama_lengkap'         => 'Nama Siswa 2',
                'jenis_kelamin'        => 'P',
                'tempat_lahir'         => 'Jambi',
                'tanggal_lahir'        => '2020-07-22',
                'agama'                => 'Islam',
                'alamat'               => 'Jl. Contoh No. 2, Jambi',
                'kelompok'             => 'KB',
                'tanggal_masuk'        => '2024-07-15',
                'tahun_ajaran'         => '2024/2025',
                'aktif'                => true,
                'nama_ayah'            => 'Nama Ayah 2',
                'nik_ayah'             => '1571010101830022',
                'tempat_lahir_ayah'    => 'Jambi',
                'tanggal_lahir_ayah'   => '1983-11-30',
                'pendidikan_ayah'      => 'SMA',
                'pekerjaan_ayah'       => 'Wiraswasta',
                'no_hp_ayah'           => '08120000002',
                'nama_ibu'             => 'Nama Ibu 2',
                'nik_ibu'              => '1571010101870022',
                'tempat_lahir_ibu'     => 'Jambi',
                'tanggal_lahir_ibu'    => '1987-04-12',
                'pendidikan_ibu'       => 'SMA',
                'pekerjaan_ibu'        => 'Ibu Rumah Tangga',
                'no_hp_ibu'            => '08130000002',
                'nama_wali'            => null,
                'hubungan_wali'        => null,
                'no_hp_wali'           => null,
            ],
            [
                'nis'                  => '2025003',
                'nama_lengkap'         => 'Nama Siswa 3',
                'jenis_kelamin'        => 'L',
                'tempat_lahir'         => 'Jambi',
                'tanggal_lahir'        => '2019-12-01',
                'agama'                => 'Islam',
                'alamat'               => 'Jl. Contoh No. 3, Jambi',
                'kelompok'             => 'KB',
                'tanggal_masuk'        => '2024-07-15',
                'tahun_ajaran'         => '2024/2025',
                'aktif'                => true,
                'nama_ayah'            => 'Nama Ayah 3',
                'nik_ayah'             => '1571010101800033',
                'tempat_lahir_ayah'    => 'Jambi',
                'tanggal_lahir_ayah'   => '1980-09-17',
                'pendidikan_ayah'      => 'S1',
                'pekerjaan_ayah'       => 'PNS',
                'no_hp_ayah'           => '08120000003',
                'nama_ibu'             => 'Nama Ibu 3',
                'nik_ibu'              => '1571010101840033',
                'tempat_lahir_ibu'     => 'Palembang',
                'tanggal_lahir_ibu'    => '1984-02-25',
                'pendidikan_ibu'       => 'S1',
                'pekerjaan_ibu'        => 'Guru',
                'no_hp_ibu'            => '08130000003',
                'nama_wali'            => null,
                'hubungan_wali'        => null,
                'no_hp_wali'           => null,
            ],
            [
                // Contoh siswa yang dijemput wali (bukan ayah/ibu)
                'nis'                  => '2025004',
                'nama_lengkap'         => 'Nama Siswa 4',
                'jenis_kelamin'        => 'P',
                'tempat_lahir'         => 'Jambi',
                'tanggal_lahir'        => '2020-05-08',
                'agama'                => 'Islam',
                'alamat'               => 'Jl. Contoh No. 4, Jambi',
                'kelompok'             => 'KB',
                'tanggal_masuk'        => '2024-07-15',
                'tahun_ajaran'         => '2024/2025',
                'aktif'                => true,
                'nama_ayah'            => 'Nama Ayah 4',
                'nik_ayah'             => '1571010101820044',
                'tempat_lahir_ayah'    => 'Jambi',
                'tanggal_lahir_ayah'   => '1982-06-14',
                'pendidikan_ayah'      => 'S2',
                'pekerjaan_ayah'       => 'Dokter',
                'no_hp_ayah'           => '08120000004',
                'nama_ibu'             => 'Nama Ibu 4',
                'nik_ibu'              => '1571010101860044',
                'tempat_lahir_ibu'     => 'Jambi',
                'tanggal_lahir_ibu'    => '1986-10-30',
                'pendidikan_ibu'       => 'S1',
                'pekerjaan_ibu'        => 'Karyawan Swasta',
                'no_hp_ibu'            => '08130000004',
                // Wali yang mengantar/menjemput (nenek)
                'nama_wali'            => 'Nama Nenek 4',
                'hubungan_wali'        => 'Nenek',
                'no_hp_wali'           => '08140000004',      // nomor ini yang dapat notif WA
            ],
            [
                'nis'                  => '2025005',
                'nama_lengkap'         => 'Nama Siswa 5',
                'jenis_kelamin'        => 'L',
                'tempat_lahir'         => 'Jambi',
                'tanggal_lahir'        => '2020-01-19',
                'agama'                => 'Islam',
                'alamat'               => 'Jl. Contoh No. 5, Jambi',
                'kelompok'             => 'KB',
                'tanggal_masuk'        => '2024-07-15',
                'tahun_ajaran'         => '2024/2025',
                'aktif'                => true,
                'nama_ayah'            => 'Nama Ayah 5',
                'nik_ayah'             => '1571010101790055',
                'tempat_lahir_ayah'    => 'Jambi',
                'tanggal_lahir_ayah'   => '1979-03-05',
                'pendidikan_ayah'      => 'SMA',
                'pekerjaan_ayah'       => 'Petani',
                'no_hp_ayah'           => '08120000005',
                'nama_ibu'             => 'Nama Ibu 5',
                'nik_ibu'              => '1571010101830055',
                'tempat_lahir_ibu'     => 'Jambi',
                'tanggal_lahir_ibu'    => '1983-07-21',
                'pendidikan_ibu'       => 'SMP',
                'pekerjaan_ibu'        => 'Ibu Rumah Tangga',
                'no_hp_ibu'            => '08130000005',
                'nama_wali'            => null,
                'hubungan_wali'        => null,
                'no_hp_wali'           => null,
            ],
        ];

        foreach ($siswa as $s) {
            Siswa::create($s);
        }
    }
}