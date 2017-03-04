<?php

namespace Fabulator\Endomondo;

final class PointFactory {

    /**
     * Parse data from old Endomondo API.
     *
     * @param $source array
     * @return Point
     */
    static function parseOldEndomondoApi($source)
    {
        $point = new Point;

        if (isset($source['time'])) {
            $point->setTime(new \DateTime($source['time']));
        }

        if (isset($source['lat'])) {
            $point->setLatitude($source['lat']);
        }

        if (isset($source['lng'])) {
            $point->setLongitude($source['lng']);
        }

        if (isset($source['alt'])) {
            $point->setAltitude($source['alt']);
        }

        if (isset($source['dist'])) {
            $point->setDistance($source['dist']);
        }

        if (isset($source['speed'])) {
            $point->setSpeed($source['speed']);
        }

        if (isset($source['duration'])) {
            $point->setDuration($source['duration']);
        }

        if (isset($source['hr'])) {
            $point->setHeartRate($source['hr']);
        }

        if (isset($source['inst'])) {
            $point->setInstruction($source['inst']);
        }

        return $point;
    }
}