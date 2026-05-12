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

    'latitude'     => env('SCHOOL_LATITUDE', -1.6074600),
    'longitude'    => env('SCHOOL_LONGITUDE', 103.6091800),
    'radius_meter' => env('SCHOOL_RADIUS_METER', 100),

    /*
    |--------------------------------------------------------------------------
    | Jam Kerja Guru
    |--------------------------------------------------------------------------
    */
    'jam_masuk'     => env('ATTENDANCE_START', '07:00'),
    'jam_terlambat' => env('ATTENDANCE_LATE',  '07:30'),

    /*
    |--------------------------------------------------------------------------
    | Informasi Sekolah (untuk kwitansi PDF, laporan, dll.)
    |--------------------------------------------------------------------------
    */
    'nama'    => env('SCHOOL_NAME',    'PAUD KB Pelangi'),
    'alamat'  => env('SCHOOL_ADDRESS', ''),
    'telp'    => env('SCHOOL_PHONE',   ''),
    'email'   => env('SCHOOL_EMAIL',   ''),
    'kepala'  => env('SCHOOL_PRINCIPAL', ''),

];