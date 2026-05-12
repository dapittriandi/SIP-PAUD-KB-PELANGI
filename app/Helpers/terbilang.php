<?php

// ============================================================
// app/Helpers/terbilang.php
// Letakkan file ini di: app/Helpers/terbilang.php
// Lalu daftarkan di composer.json → autoload → files
// ============================================================
//
// Di composer.json tambahkan:
//
// "autoload": {
//     "files": [
//         "app/Helpers/terbilang.php"
//     ],
//     ...
// }
//
// Lalu jalankan: composer dump-autoload
// ============================================================

if (! function_exists('terbilang')) {

    function terbilang(int $angka): string
    {
        $angka  = abs((int) $angka);
        $satuan = ['', 'satu', 'dua', 'tiga', 'empat', 'lima',
                   'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh',
                   'sebelas'];

        if ($angka < 12) {
            return $satuan[$angka];
        }

        if ($angka < 20) {
            return terbilang($angka - 10) . ' belas';
        }

        if ($angka < 100) {
            return terbilang((int) ($angka / 10)) . ' puluh' .
                   ($angka % 10 ? ' ' . terbilang($angka % 10) : '');
        }

        if ($angka < 200) {
            return 'seratus' . ($angka - 100 ? ' ' . terbilang($angka - 100) : '');
        }

        if ($angka < 1000) {
            return terbilang((int) ($angka / 100)) . ' ratus' .
                   ($angka % 100 ? ' ' . terbilang($angka % 100) : '');
        }

        if ($angka < 2000) {
            return 'seribu' . ($angka - 1000 ? ' ' . terbilang($angka - 1000) : '');
        }

        if ($angka < 1_000_000) {
            return terbilang((int) ($angka / 1000)) . ' ribu' .
                   ($angka % 1000 ? ' ' . terbilang($angka % 1000) : '');
        }

        if ($angka < 1_000_000_000) {
            return terbilang((int) ($angka / 1_000_000)) . ' juta' .
                   ($angka % 1_000_000 ? ' ' . terbilang($angka % 1_000_000) : '');
        }

        return terbilang((int) ($angka / 1_000_000_000)) . ' miliar' .
               ($angka % 1_000_000_000 ? ' ' . terbilang($angka % 1_000_000_000) : '');
    }

    /**
     * Versi kapital — untuk tampil di kwitansi
     * Contoh: terbilangRupiah(55000) → "Lima Puluh Lima Ribu Rupiah"
     */
    function terbilangRupiah(int $angka): string
    {
        return ucwords(terbilang($angka)) . ' Rupiah';
    }
}