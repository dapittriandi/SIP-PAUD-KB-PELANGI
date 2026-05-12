<?php

namespace App\Services;

class GeolocationService
{
    const EARTH_RADIUS_METER = 6371000;

    /**
     * Hitung jarak dua titik koordinat (meter) pakai rumus Haversine.
     */
    public static function hitungJarak(
        float $lat1, float $lon1,
        float $lat2, float $lon2
    ): float {
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round(self::EARTH_RADIUS_METER * $c);
    }

    /**
     * Validasi apakah koordinat guru dalam radius sekolah.
     * Koordinat & radius dibaca dari config (file config/sekolah.php).
     *
     * @return array{valid: bool, jarak_meter: int}
     */
    public static function validasiLokasi(float $latGuru, float $lonGuru): array
    {
        $latSekolah = (float) config('sekolah.latitude');
        $lonSekolah = (float) config('sekolah.longitude');
        $radius     = (int)   config('sekolah.radius_meter', 100);

        $jarak = self::hitungJarak($latGuru, $lonGuru, $latSekolah, $lonSekolah);

        return [
            'valid'       => $jarak <= $radius,
            'jarak_meter' => (int) $jarak,
        ];
    }
}