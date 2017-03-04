<?php

namespace Fabulator\Endomondo;

class Workout {

    /**
     * @var array
     */
    private $source;

    private $types = [
        WorkoutType::RUNNING  => 'Running',
        WorkoutType::CYCLING_TRANSPORT  => 'Cycling, transport',
        WorkoutType::CYCLING_SPORT  => 'Cycling, sport',
        WorkoutType::MOUNTAIN_BIKINGS  => 'Mountain biking',
        WorkoutType::SKATING  => 'Skating',
        WorkoutType::ROLLER_SKIING  => 'Roller skiing',
        WorkoutType::SKIING_CROSS_COUNTRY  => 'Skiing, cross country',
        WorkoutType::SKIING_DOWNHILL  => 'Skiing, downhill',
        WorkoutType::SNOWBOARDING  => 'Snowboarding',
        WorkoutType::KAYAKING  => 'Kayaking',
        WorkoutType::KITE_SURFING => 'Kite surfing',
        WorkoutType::ROWING => 'Rowing',
        WorkoutType::SAILING => 'Sailing',
        WorkoutType::WINDSURFING => 'Windsurfing',
        WorkoutType::FINTESS_WALKING => 'Fitness walking',
        WorkoutType::GOLFING => 'Golfing',
        WorkoutType::HIKING => 'Hiking',
        WorkoutType::ORIENTEERING => 'Orienteering',
        WorkoutType::WALKING => 'Walking',
        WorkoutType::RIDING => 'Riding',
        WorkoutType::SWIMMING => 'Swimming',
        WorkoutType::SPINNING => 'Spinning',
        WorkoutType::OTHER => 'Other',
        WorkoutType::AEROBICS => 'Aerobics',
        WorkoutType::BADMINTON => 'Badminton',
        WorkoutType::BASEBALL => 'Baseball',
        WorkoutType::BASKETBALL => 'Basketball',
        WorkoutType::BOXING => 'Boxing',
        WorkoutType::CLIMBING_STAIRS => 'Climbing stairs',
        WorkoutType::CRICKET => 'Cricket',
        WorkoutType::ELLIPTICAL_TRAINING => 'Elliptical training',
        WorkoutType::DANCING => 'Dancing',
        WorkoutType::FENCING => 'Fencing',
        WorkoutType::FOOTBALL_AMERICAN => 'Football, American',
        WorkoutType::FOOTBALL_RUGBY => 'Football, rugby',
        WorkoutType::FOOTBALL_SOCCER => 'Football, soccer',
        WorkoutType::HANDBALL => 'Handball',
        WorkoutType::HOCKEY => 'Hockey',
        WorkoutType::PILATES => 'Pilates',
        WorkoutType::POLO => 'Polo',
        WorkoutType::SCUBA_DIVING => 'Scuba diving',
        WorkoutType::SQUASH => 'Squash',
        WorkoutType::TABLE_TENIS => 'Table tennis',
        WorkoutType::TENNIS => 'Tennis',
        WorkoutType::VOLEYBALL_BEACH => 'Volleyball, beach',
        WorkoutType::VOLEYBALL_INDOOR => 'Volleyball, indoor',
        WorkoutType::WEIGHT_TRAINING => 'Weight training',
        WorkoutType::YOGA => 'Yoga',
        WorkoutType::MARTINAL_ARTS => 'Martial arts',
        WorkoutType::GYMNASTICS => 'Gymnastics',
        WorkoutType::STEP_COUNTER => 'Step counter',
        WorkoutType::CIRKUIT_TRAINING => 'Circuit Training'
    ];

    /**
     * @var integer
     */
    private $typeId;

    /**
     * @var float
     */
    private $calories;

    /**
     * @var integer
     */
    private $duration;

    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @var string
     */
    private $id;

    /**
     * @var float
     */
    private $distance;

    /**
     * @var array<Point>
     */
    private $points = [];

    /**
     * @var int
     */
    private $avgHeartRate;

    /**
     * @var int
     */
    private $maxHeartRate;

    /**
     * @var string
     */
    private $notes;

    /**
     * @var int
     */
    private $privacyMap;

    /**
     * @var int
     */
    private $privacyWorkout;

    /**
     * Create Endomondo workout.
     *
     * Workout constructor.
     */
    public function __construct() { }

    /**
     * Get workout type id.
     *
     * @return integer
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Set workout type id.
     *
     * @param $id integer
     * @return $this
     * @throws EndomondoException when workout type is unknown.
     */
    public function setTypeId($id)
    {
        if (!isset($this->types[$id])) {
            throw new EndomondoException('Unknown workout type');
        }

        $this->typeId = $id;
        return $this;
    }

    /**
     * Get human readable workout type.
     *
     * @return string
     */
    public function getTypeName()
    {
        return $this->types[$this->getTypeId()];
    }

    /**
     * Set number of calories.
     *
     * @param $calories float
     * @return $this
     */
    public function setCalories($calories)
    {
        $this->calories = $calories;
        return $this;
    }

    /**
     * Get number of calories.
     *
     * @return float
     */
    public function getCalories()
    {
        return $this->calories;
    }

    /**
     * Get duration in seconds.
     *
     * @param $duration integer
     * @return $this
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * Get duration in seconds.
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Get workout start time.
     *
     * @param \DateTime $start
     * @return $this
     */
    public function setStart(\DateTime $start)
    {
        $this->start = $start;
        return $this;
    }

    /**
     * Get start time.
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end time.
     *
     * @param \DateTime $end
     * @return $this
     */
    public function setEnd(\DateTime $end)
    {
        $this->end = $end;
        return $this;
    }

    /**
     * Get time of end of workout. It can be manual set or it is counted based on start and duration.
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        if ($this->end) {
            return $this->end;
        }

        $end = clone $this->getStart();

        return $end->add(new \DateInterval('PT' . $this->getDuration() . 'S'));
    }

    /**
     * Set workout Id.
     *
     * @param $id string
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get workout id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set distance in kilometres.
     *
     * @param $distance float
     * @return $this
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
        return $this;
    }

    /**
     * Get distance in kilometres.
     *
     * @return float
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Set points.
     *
     * @param $points array<Point>
     */
    public function setPoints($points) {
        $this->points = $points;
        return $this;
    }

    /**
     * Get points.
     *
     * @return array<Point>
     */
    public function getPoints()
    {
        return $this->points;
    }

    public function getPointsAsString()
    {
        $points = '';

        foreach ($this->getPoints() as $point) {
            $points .= $point->toString();
        }

        return $points;
    }

    /**
     * @param $hr int
     * @return $this
     */
    public function setAvgHeartRate($hr)
    {
        $this->avgHeartRate = $hr;
        return $this;
    }

    /**
     * @return int
     */
    public function getAvgHeartRate()
    {
        return $this->avgHeartRate;
    }

    /**
     * @param $hr int
     * @return $this
     */
    public function setMaxHeartRate($hr)
    {
        $this->maxHeartRate = $hr;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxHeartRate()
    {
        return $this->maxHeartRate;
    }

    /**
     * Set workout Endomondo source
     *
     * @param $source array
     * @return $this
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Get workout Endomondo source
     *
     * @return array
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param $notes string
     * @return $this
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param $privacy int
     * @return $this
     */
    public function setMapPrivacy($privacy)
    {
        $this->privacyMap = $privacy;
        return $this;
    }

    /**
     * @return int
     */
    public function getMapPrivacy()
    {
        return $this->privacyMap;
    }

    /**
     * @param $privacy int
     * @return $this
     */
    public function setWorkoutPrivacy($privacy)
    {
        $this->privacyWorkout = $privacy;
        return $this;
    }

    /**
     * @return int
     */
    public function getWorkoutPrivacy()
    {
        return $this->privacyWorkout;
    }

    /**
     * Get GPX of workout.
     *
     * @return string
     */
    public function getGPX()
    {
        $xml = new \SimpleXMLElement(
            '<gpx xmlns="http://www.topografix.com/GPX/1/1"'
            . 'xmlns:gpxtpx="http://www.garmin.com/xmlschemas/TrackPointExtension/v1"'
            . '/>'
        );
        $trk = $xml->addChild('trk');
        $trk->addChild('type', str_replace(', ', '_', strtoupper($this->getTypeName())));
        $trkseg = $trk->addChild('trkseg');

        foreach ($this->getPoints() as $point) {
            $trkpt = $trkseg->addChild('trkpt');
            $trkpt->addChild('time', $point->getTime()->format('Y-m-d\TH:i:s\Z'));
            $trkpt->addAttribute('lat', $point->getLatitude());
            $trkpt->addAttribute('lon', $point->getLongitude());

            if ($point->getAltitude() !== null) {
                $trkpt->addChild('ele', $point->getAltitude());
            }

            if ($point->getHeartRate() !== null) {
                $ext = $trkpt->addChild('extensions');
                $trackPoint = $ext->addChild('gpxtpx:TrackPointExtension', '', 'gpxtpx');
                $trackPoint->addChild('gpxtpx:hr', $point->getHeartRate(), 'gpxtpx');
            }
        }

        return $xml->asXML();
    }
}