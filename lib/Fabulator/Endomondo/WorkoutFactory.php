<?php

namespace Fabulator\Endomondo;

final class WorkoutFactory {

    /**
     * Get Endomondo workout from old API
     *
     * @param $source array
     * @return Workout
     */
    static function parseOldEndomondoApi($source)
    {
        $workout = new Workout();

        $workout
            ->setSource($source)
            ->setTypeId($source['sport'])
            ->setDuration($source['duration'])
            ->setStart(new \DateTime($source['start_time']))
            ->setDistance($source['distance'])
            ->setNotes($source['notes'])
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
                $points[] = PointFactory::parseOldEndomondoApi($point);
            }
            $workout->setPoints($points);
        }

        if (isset($source['heart_rate_avg'])) {
            $workout->setAvgHeartRate($source['heart_rate_avg']);
        }

        if (isset($source['heart_rate_max'])) {
            $workout->setMaxHeartRate($source['heart_rate_max']);
        }

        return $workout;
    }
}