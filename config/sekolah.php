<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Koordinat GPS Sekolah
    |--------------------------------------------------------------------------
    | Isi nilai di file .env:
    |   SCHOOL_LATITUDE=-1.6074600
    |   SCHOOL_LONGITUDE=103.6091800
    |   SCHOOL_RADIUS_METER=100
    |
    | Koordinat bisa dicek di Google Maps:
    |   Klik kanan pada lokasi sekolah → "What's here?"
    */

    'latitude'     => env('SCHOOL_LATITUDE', -1.2723042),
    'longitude'    => env('SCHOOL_LONGITUDE', 102.9486034),
    'radius_meter' => env('SCHOOL_RADIUS_METER', 100),

    /*
    |--------------------------------------------------------------------------
    | Jam Kerja Guru
    |--------------------------------------------------------------------------
    | jam_masuk          → jam resmi masuk (tepat waktu jika absen ≤ jam_toleransi)
    | jam_toleransi      → batas akhir masih dianggap "hadir" (toleransi 15 menit)
    | jam_cutoff_checkin → setelah jam ini guru tidak bisa absen mandiri
    | jam_cutoff_izin    → setelah jam ini guru tidak bisa lapor izin/sakit/tugas luar
    |
    | Tambahkan ke .env jika ingin override tanpa edit file ini:
    |   ATTENDANCE_START=08:00
    |   ATTENDANCE_LATE=08:15
    |   ATTENDANCE_CUTOFF_CHECKIN=10:30
    |   ATTENDANCE_CUTOFF_IZIN=09:00
    */
    'jam_masuk'          => env('ATTENDANCE_START',          '08:00'),
    'jam_toleransi'      => env('ATTENDANCE_LATE',           '08:15'),
    'jam_cutoff_checkin' => env('ATTENDANCE_CUTOFF_CHECKIN', '10:30'),
    'jam_mulai_izin'    => env('ATTENDANCE_MULAI_IZIN',    '00:01'),
    'jam_cutoff_izin'    => env('ATTENDANCE_CUTOFF_IZIN',    '09:00'),

    /*
    |--------------------------------------------------------------------------
    | Hari Kerja
    |--------------------------------------------------------------------------
    | Daftar hari kerja aktif (0=Minggu, 1=Senin, ..., 6=Sabtu).
    | Default: Senin–Jumat.
    |
    | Tambahkan ke .env jika ingin override (pisahkan dengan koma):
    |   ATTENDANCE_WORK_DAYS=1,2,3,4,5
    |
    | Contoh PAUD yang masuk Senin–Sabtu:
    |   ATTENDANCE_WORK_DAYS=1,2,3,4,5,6
    */
    'hari_kerja' => array_map(
        'intval',
        explode(',', env('ATTENDANCE_WORK_DAYS', '1,2,3,4,5'))
    ),

    /*
    |--------------------------------------------------------------------------
    | Informasi Sekolah (untuk kwitansi PDF, laporan, dll.)
    |--------------------------------------------------------------------------
    */
    'nama'    => env('SCHOOL_NAME',      'PAUD KB Pelangi'),
    'alamat'  => env('SCHOOL_ADDRESS',   ''),
    'telp'    => env('SCHOOL_PHONE',     ''),
    'email'   => env('SCHOOL_EMAIL',     ''),
    'kepala'  => env('SCHOOL_PRINCIPAL', ''),

];