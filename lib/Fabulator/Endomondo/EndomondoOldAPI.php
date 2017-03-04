<?php

namespace Fabulator\Endomondo;

class EndomondoOldAPI extends EndomondoOldAPIBase
{

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
     * @throws EndomondoException when credentials are wrong
     */
    public function requestAuthToken($username, $password) {
        $response = parent::requestAuthToken($username, $password);

        if ($response === 'USER_UNKNOWN') {
            throw new EndomondoException('Wrong username or password.');
        }

        $this->setAccessToken($response['authToken']);
        $this->setUserId($response['userId']);

        return $response;
    }

    /**
     * Request Old Endomondo API.
     *
     * @param string $endpoint
     * @param array $options
     * @param string $body
     * @return array
     */
    public function request($endpoint, $options = [], $body = '')
    {
        if ($this->getAcessToken()) {
            $options['authToken'] = $this->getAcessToken();
        }

        $response = parent::request($endpoint, $options, gzencode($body));
        $responseBody = trim((string) $response->getBody());

        if ($response->getHeader('Content-Type')[0] === 'text/plain;charset=UTF-8') {
            $lines = explode("\n", $response->getBody());
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

        return json_decode($responseBody, true);
    }

    /**
     * Get single Endomondo workout.
     *
     * @param $id string
     * @return Workout
     */
    public function getWorkout($id)
    {
        return WorkoutFactory::parseOldEndomondoApi($this->request('/mobile/api/workout/get', [
            'fields' => 'basic,points,pictures,tagged_users,points,playlist,interval',
            'workoutId' => $id,
        ]));
    }

    /**
     * Get list of last workouts
     *
     * @param int $limit
     * @return array<Workout>
     */
    public function getWorkouts($limit = 10)
    {
        $workouts = [];
        $response = $this->request('/mobile/api/workout/list', [
            'fields' => 'basic,pictures,tagged_users,points,playlist,interval',
            'maxResults' => $limit
        ]);

        foreach ($response['data'] as $workout) {
            $workouts[] = WorkoutFactory::parseOldEndomondoApi($workout);
        }

        return $workouts;
    }

    /**
     * Create Endomondo Workout.
     *
     * @param Workout $workout
     * @return Workout
     * @throws EndomondoException when it fails to create workout
     */
    public function createWorkout(Workout $workout)
    {
        $response = $this->request('/mobile/track', [
            'duration' => $workout->getDuration(),
            'sport' => $workout->getTypeId(),
            'extendedResponse' => 'true',
            'gzip' => 'true',
        ], $workout->getPointsAsString());

        if (!isset($response['workout.id'])) {
            throw new EndomondoException('Workout create failed.');
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
        $data = [
            'duration' => $workout->getDuration(),
            'sport' => $workout->getTypeId(),
            'distance' => $workout->getDistance(),
            'extendedResponse' => 'true',
            'gzip' => 'true',
            'start_time' => $workout->getStart()
                ->setTimezone(new \DateTimeZone('UTC'))
                ->format('Y-m-d H:i:s \U\T\C'),
            'end_time' => $workout->getEnd()
                ->setTimezone(new \DateTimeZone('UTC'))
                ->format('Y-m-d H:i:s \U\T\C'),
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

        $this->request('/mobile/api/workout/post', [
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
