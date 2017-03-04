<?php

namespace Fabulator\Endomondo;

class Point {

    /**
     * @var \DateTime
     */
    private $time;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var integer
     */
    private $altitude;

    /**
     * @var float
     */
    private $distance;

    /**
     * @var integer
     */
    private $duration;

    /**
     * @var integer
     */
    private $heartRate;

    /**
     * @var float
     */
    private $speed;

    /**
     * @var integer
     */
    private $instruction;

    public function __construct()
    {
    }

    /**
     * @param \DateTime $time
     * @return $this
     */
    public function setTime(\DateTime $time) {
        $this->time = $time;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param $latitude float
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param $longitude float
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param $altitude integer
     * @return $this
     */
    public function setAltitude($altitude)
    {
        $this->altitude = $altitude;
        return $this;
    }

    /**
     * @return int
     */
    public function getAltitude()
    {
        return $this->altitude;
    }

    /**
     * @param $distance float
     * @return $this
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
        return $this;
    }

    /**
     * @return float
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param $duration integer
     * @return $this
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param $heartRate integer
     * @return $this
     */
    public function setHeartRate($heartRate)
    {
        $this->heartRate = $heartRate;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeartRate()
    {
        return $this->heartRate;
    }

    /**
     * @param $speed float
     * @return $this
     */
    public function setSpeed($speed)
    {
        $this->speed = $speed;
        return $this;
    }

    /**
     * @return float
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @param $instruction int
     * @return $this
     */
    public function setInstruction($instruction)
    {
        $this->instruction = $instruction;
        return $this;
    }

    /**
     * @return int
     */
    public function getInstruction()
    {
        return $this->instruction;
    }

    /**
     * @return string
     */
    public function toString()
    {
        $time = clone $this->getTime();
        $time = $time->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s \U\T\C');
        return $time . ';' .
            ($this->getInstruction() ?: '2') . ';' .
            $this->getLatitude() . ';' .
            $this->getLongitude() . ';' .
            $this->getDistance() . ';;' .
            $this->getAltitude() . ';' .
            ($this->getHeartRate() ?: '') .
            ';;' . "\n" . '';
    }
}