<?php

namespace App\Services;

use App\Models\PembayaranSpp;

class KwitansiService
{
    /**
     * Generate nomor kwitansi format: KWT-YYYY-MM-NNNN
     * Nomor urut reset setiap bulan.
     *
     * Contoh: KWT-2025-05-0001
     */
    public static function generateNomor(): string
    {
        $tahun  = now()->format('Y');
        $bulan  = now()->format('m');
        $prefix = "KWT-{$tahun}-{$bulan}-";

        // Ambil nomor kwitansi terakhir bulan ini
        $last = PembayaranSpp::where('no_kwitansi', 'like', $prefix . '%')
                             ->orderByDesc('no_kwitansi')
                             ->lockForUpdate()
                             ->first();

        $urut = $last ? ((int) substr($last->no_kwitansi, -4)) + 1 : 1;

        return $prefix . str_pad($urut, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Parse nomor kwitansi menjadi komponen-komponennya.
     *
     * @return array{prefix: string, tahun: string, bulan: string, urut: int}|null
     */
    public static function parseNomor(string $noKwitansi): ?array
    {
        // Format: KWT-YYYY-MM-NNNN
        if (!preg_match('/^KWT-(\d{4})-(\d{2})-(\d{4})$/', $noKwitansi, $m)) {
            return null;
        }

        return [
            'prefix' => 'KWT',
            'tahun'  => $m[1],
            'bulan'  => $m[2],
            'urut'   => (int) $m[3],
        ];
    }

    /**
     * Validasi format nomor kwitansi.
     */
    public static function isValid(string $noKwitansi): bool
    {
        return (bool) preg_match('/^KWT-\d{4}-\d{2}-\d{4}$/', $noKwitansi);
    }
}