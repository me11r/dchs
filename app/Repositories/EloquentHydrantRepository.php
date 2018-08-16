<?php

namespace App\Repositories;

use App\Models\Hydrant;
use App\Repositories\Contracts\HydrantRepositoryInterface;

class EloquentHydrantRepository extends Repository implements HydrantRepositoryInterface
{
    /**
     * @var int
     */
    private $meridianLengthInMeters = 40075696;

    /**
     * @return mixed|string
     */
    public function model()
    {
        return Hydrant::class;
    }

    public function searchForPointByRadius($latitude, $longitude, $distance)
    {
        $degreesDistance = $this->metersToDegrees($distance);

        $maxLatitude = $latitude + $degreesDistance;
        $minLatitude = $latitude - $degreesDistance;
        $maxLongitude = $longitude + $degreesDistance;
        $minLongitude = $longitude - $degreesDistance;

        $this->model = $this->model
            ->where('lat', '>', $minLatitude)
            ->where('lat', '<', $maxLatitude)
            ->where('long', '>', $minLongitude)
            ->where('long', '<', $maxLongitude);

        return $this;
    }

    /**
     * @param $meters
     * @return float|int
     */
    private function metersToDegrees($meters)
    {
        return $meters / ($this->meridianLengthInMeters / 360);
    }
}
