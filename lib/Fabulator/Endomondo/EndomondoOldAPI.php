<?php

namespace Fabulator\Endomondo;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\ClientException;

/**
 * Class EndomondoOldAPI
 * @package Fabulator\Endomondo
 */
class EndomondoOldAPI extends EndomondoOldAPIBase
{

    const GET_WORKOUT_ENDPOINT = '/mobile/api/workout/get';
    const GET_WORKOUTS_ENDPOINT = '/mobile/api/workout/list';
    const CREATE_WORKOUT_ENDPOINT = '/mobile/track';
    const UPDATE_WORKOUT_ENDPOINT = '/mobile/api/workout/post';
    const DEFAULT_WORKOUT_FIELDS = [
        'basic',
        'points',
        'pictures',
        'tagged_users',
        'points',
        'playlist',
        'interval',
    ];

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $userId;

    /**
     * Request auth token and other base user information.
     *
     * @param string $username endomondo username
     * @param string $password password for user
     * @return array Data that contain action, authToken, measure, displayName, userId, facebookConnected and secureToken
     * @throws EndomondoOldApiException when credentials are wrong
     */
    public function requestAuthToken($username, $password) {
        $response = parent::requestAuthToken($username, $password);
        $data = $this->decodeResponse($response);

        if (count($data) === 0) {
            throw new EndomondoOldApiException('Wrong username or password.');
        }

        $this->setAccessToken($data['authToken']);
        $this->setUserId($data['userId']);

        return $data;
    }

    /**
     * Decode response from Endomondo and convert it to array.
     *
     * @param ResponseInterface $response
     * @return array response from endomondo
     * @throws EndomondoOldApiException when request to endomondo fail
     */
    public function decodeResponse(ResponseInterface $response)
    {
        $responseBody = trim((string) $response->getBody());

        if ($response->getHeader('Content-Type')[0] === 'text/plain;charset=UTF-8') {
            $lines = explode("\n", $responseBody);
            $data = [];

            // parse endomondo text response
            foreach ($lines as $line) {
                $items = explode('=', $line);
                if (count($items) === 2) {
                    $data[$items[0]] = $items[1];
                }
            }

            return $data;
        }

        $data = json_decode($responseBody, true);

        if (isset($data['error'])) {
            throw new EndomondoOldApiException('Api error: ' . $data['error']['type']);
        }

        return $data;
    }

    /**
     * Request Old Endomondo API.
     *
     * @param string $endpoint
     * @param array $options
     * @param string $body
     * @return array
     * @throws EndomondoOldApiException when api request fail
     */
    public function request($endpoint, $options = [], $body = '')
    {
        if ($this->getAcessToken()) {
            $options['authToken'] = $this->getAcessToken();
        }

        try {
            return $this->decodeResponse(parent::send($endpoint, $options, gzencode($body)));
        } catch(ClientException $e) {
            throw new EndomondoOldApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get single Endomondo workout.
     *
     * @param $id string
     * @param array $fields list of requested fields
     * @return Workout
     */
    public function getWorkout($id, $fields = self::DEFAULT_WORKOUT_FIELDS)
    {
        return OldApiParser::parseWorkout($this->request(self::GET_WORKOUT_ENDPOINT, [
            'fields' => join($fields, ','),
            'workoutId' => $id,
        ]));
    }

    /**
     * Get list of last workouts
     *
     * @param int $limit
     * @param array $fields list of requested fields
     * @return Workout[]
     */
    public function getWorkouts($limit = 10, $fields = self::DEFAULT_WORKOUT_FIELDS)
    {
        $workouts = [];
        $response = $this->request(self::GET_WORKOUTS_ENDPOINT, [
            'fields' => join(',', $fields),
            'maxResults' => $limit
        ]);

        foreach ($response['data'] as $workout) {
            $workouts[] = OldApiParser::parseWorkout($workout);
        }

        return $workouts;
    }

    /**
     * Create Endomondo Workout.
     *
     * @param Workout $workout
     * @return Workout
     * @throws EndomondoOldApiException when it fails to create workout
     */
    public function createWorkout(Workout $workout)
    {
        $response = $this->request(self::CREATE_WORKOUT_ENDPOINT, [
            'workoutId' => '-' . $this->bigRandomNumber(16),
            'duration' => $workout->getDuration(),
            'sport' => $workout->getTypeId(),
            'extendedResponse' => 'true',
            'gzip' => 'true',
        ], $workout->getPointsAsString());

        if (!isset($response['workout.id'])) {
            throw new EndomondoOldApiException('Workout create failed.');
        }

        $workout->setId($response['workout.id']);

        return $this->updateWorkout($workout);
    }

    /**
     * Update existing endomondo Workout.
     *
     * @param Workout $workout
     * @return Workout
     */
    public function updateWorkout(Workout $workout)
    {
        $utc = new \DateTimeZone('UTC');
        $timeFormat = 'Y-m-d H:i:s \U\T\C';
        $start = (clone $workout->getStart())->setTimezone($utc)->format($timeFormat);
        $end = (clone $workout->getEnd())->setTimezone($utc)->format($timeFormat);

        $data = [
            'duration' => $workout->getDuration(),
            'sport' => $workout->getTypeId(),
            'distance' => $workout->getDistance(),
            'start_time' => $start,
            'end_time' => $end,
            'extendedResponse' => 'true',
            'gzip' => 'true',
        ];

        if ($workout->getCalories() !== null) {
            $data['calories'] = $workout->getCalories();
        }

        if ($workout->getNotes() !== null) {
            $data['notes'] = $workout->getNotes();
        }

        if ($workout->getMapPrivacy() !== null) {
            $data['privacy_map'] = $workout->getMapPrivacy();
        }

        if ($workout->getWorkoutPrivacy() !== null) {
            $data['privacy_workout'] = $workout->getWorkoutPrivacy();
        }

        $this->request(self::UPDATE_WORKOUT_ENDPOINT, [
            'workoutId' => $workout->getId(),
            'userId' => $this->getUserId(),
            'gzip' => 'true',
        ], json_encode($data));

        return $this->getWorkout($workout->getId());
    }

    /**
     * Generate really long number.
     *
     * @param  int $randNumberLength
     * @return string
     */
    private function bigRandomNumber($randNumberLength)
    {
        $randNumber = null;

        for ($i = 0; $i < $randNumberLength; $i++) {
            $randNumber .= rand(0, 9);
        }

        return $randNumber;
    }

    /**
     * @param $token string
     */
    public function setAccessToken($token)
    {
        $this->accessToken = $token;
    }

    /**
     * @return string
     */
    public function getAcessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param $id string
     */
    public function setUserId($id)
    {
        $this->userId = $id;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }
}