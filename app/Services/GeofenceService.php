<?php

namespace App\Services;

class GeofenceService
{
    private const EARTH_RADIUS_METER = 6371000;

    private static function calculateDelta(float $point1, float $point2) : float
    {
        return deg2rad($point2 - $point1);
    }

    private static function calculateChordLength(float $lat1, float $lon1, float $lat2, float $lon2, float $latDelta, float $lonDelta) : float
    {
        return
            sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) * sin($latDelta / 2);
    }

    private static function calculateAngularDistance(float $a) : float
    {
        return 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2) : float
    {
        $earthRadius = self::EARTH_RADIUS_METER;
        $latDelta = $this->calculateDelta($lat1, $lat2);
        $lonDelta = $this->calculateDelta($lon1, $lon2);
        $a = $this->calculateChordLength($lat1, $lon1, $lat2, $lon2, $latDelta, $lonDelta);
        $c = $this->calculateAngularDistance($a);

        return $earthRadius * $c;
    }
}
