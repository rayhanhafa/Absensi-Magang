<?php

namespace App\Services;

use App\Models\OfficeSetting;
use Illuminate\Validation\ValidationException;

class LocationService
{
    /**
     * Radius bumi dalam meter (rata-rata), dipakai untuk formula Haversine.
     */
    private const EARTH_RADIUS_METERS = 6371000;

    /**
     * Mengambil pengaturan lokasi kantor yang sedang aktif.
     */
    public function getActiveOffice(): OfficeSetting
    {
        $office = OfficeSetting::active()->first();

        if (! $office) {
            throw ValidationException::withMessages([
                'location' => 'Lokasi kantor belum dikonfigurasi. Hubungi admin.',
            ]);
        }

        return $office;
    }

    /**
     * Menghitung jarak antara dua titik koordinat memakai formula Haversine.
     *
     * @return float Jarak dalam meter.
     */
    public function calculateDistance(
        float $latitudeA,
        float $longitudeA,
        float $latitudeB,
        float $longitudeB
    ): float {
        $latA = deg2rad($latitudeA);
        $latB = deg2rad($latitudeB);
        $deltaLat = deg2rad($latitudeB - $latitudeA);
        $deltaLng = deg2rad($longitudeB - $longitudeA);

        $a = sin($deltaLat / 2) ** 2
            + cos($latA) * cos($latB) * sin($deltaLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round(self::EARTH_RADIUS_METERS * $c, 2);
    }

    /**
     * Memvalidasi apakah akurasi GPS yang dilaporkan cukup baik.
     */
    public function isAccuracyAcceptable(float $accuracy): bool
    {
        return $accuracy <= config('attendance.max_accuracy_meters');
    }

    /**
     * Memvalidasi lokasi user terhadap kantor aktif secara menyeluruh:
     * akurasi lalu jarak/radius. Backend adalah source of truth —
     * tidak pernah mempercayai kesimpulan "valid/invalid" dari frontend.
     *
     * @return array{distance: float, radius: int, accuracy: float, office: OfficeSetting}
     *
     * @throws ValidationException jika akurasi buruk atau di luar radius.
     */
    public function validateLocation(float $latitude, float $longitude, float $accuracy): array
    {
        if (! $this->isAccuracyAcceptable($accuracy)) {
            throw ValidationException::withMessages([
                'location' => 'Lokasi belum cukup akurat. Silakan pindah ke area terbuka atau aktifkan GPS.',
            ]);
        }

        $office = $this->getActiveOffice();

        $distance = $this->calculateDistance(
            $latitude,
            $longitude,
            (float) $office->latitude,
            (float) $office->longitude
        );

        if ($distance > $office->radius_meter) {
            throw ValidationException::withMessages([
                'location' => "Anda berada di luar area absensi. Jarak Anda: {$distance} meter (maksimal {$office->radius_meter} meter).",
            ]);
        }

        return [
            'distance' => $distance,
            'radius' => $office->radius_meter,
            'accuracy' => $accuracy,
            'office' => $office,
        ];
    }
}