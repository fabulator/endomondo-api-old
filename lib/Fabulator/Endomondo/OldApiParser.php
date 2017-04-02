<?php

namespace Fabulator\Endomondo;

final class OldApiParser {

    /**
     * Parse point data from old Endomondo API.
     *
     * @param $source array
     * @return Point
     */
    static function parsePoint($source)
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

    /**
     * Get Endomondo workout from old API
     *
     * @param $source array
     * @return Workout
     */
    static function parseWorkout($source)
    {
        $workout = new Workout();

        $workout
            ->setSource($source)
            ->setTypeId($source['sport'])
            ->setDuration($source['duration'])
            ->setStart(new \DateTime($source['start_time']))
            ->setDistance($source['distance'])
            ->setMapPrivacy($source['privacy_map'])
            ->setWorkoutPrivacy($source['privacy_workout']);

        if ($source['id']) {
            $workout->setId($source['id']);
        }

        if ($source['calories']) {
            $workout->setCalories($source['calories']);
        }

        if ($source['points']) {
            $points = [];
            foreach ($source['points'] as $point) {
                $points[] = OldApiParser::parsePoint($point);
            }
            $workout->setPoints($points);
        }

        if (isset($source['heart_rate_avg'])) {
            $workout->setAvgHeartRate($source['heart_rate_avg']);
        }

        if (isset($source['heart_rate_max'])) {
            $workout->setMaxHeartRate($source['heart_rate_max']);
        }

        if (isset($source['notes'])) {
            $workout->setNotes($source['notes']);
        }

        return $workout;
    }
}