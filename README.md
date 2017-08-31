Endomondo API Old
============

This is extension of basic wrapper for Endomondo API old. It is unofficial and everything you do with this library is full on your responsibility.

This Old API is based on API from mobile app and have limited functionality. On the other side it is only API which can create new workouts with GPS points.

#Auth

You can login to API by your login and password.

```php
$endomondo = new \Fabulator\Endomondo\EndomondoApiOld();
$endomondo->requestAuthToken(ENDOMONDO_LOGIN, ENDOMONDO_PASSWORD);
```

# Getting workouts

You can fetch single workout:
```php
$workout = $endomondo->getWorkout('771832456');

// as simple string
echo $workout->toString();

// export as GPX
echo $workout->getGPX();
```

or list of last workouts:
```php
foreach($endomondo->getWorkouts(3) as $workout) {
    echo $workout->toString() . "\n";
}
```

# Creating workouts

You can also create new workouts

```php
$workout = new \Fabulator\Endomondo\Workout();
$workout
    ->setTypeId(\Fabulator\Endomondo\WorkoutType::RUNNING)
    ->setDistance(10)
    ->setDuration(60)
    ->setStart(new DateTime('2017-03-01 20:21:32 Europe/Prague'))
    ->setCalories(444);

$endomondo->createWorkout($workout);
```

And new workouts with GPS:

```php
$workout = new \Fabulator\Endomondo\Workout();
$workout
    ->setTypeId(\Fabulator\Endomondo\WorkoutType::RUNNING)
    ->setDistance(10)
    ->setDuration(60)
    ->setStart(new DateTime('2017-08-30 20:21:32 Europe/Prague'))
    ->setPoints([
        (new Fabulator\Endomondo\Point())
            ->setTime(new DateTime('2017-08-30 20:21:32 Europe/Prague'))
            ->setLatitude(50.02957153)
            ->setLongitude(14.51805568),
        (new Fabulator\Endomondo\Point())
            ->setTime(new DateTime('2017-08-30 20:22:32 Europe/Prague'))
            ->setLatitude(50.03057153)
            ->setLongitude(14.52205568),
        (new Fabulator\Endomondo\Point())
            ->setTime(new DateTime('2017-08-30 20:23:32 Europe/Prague'))
            ->setLatitude(50.03357153)
            ->setLongitude(14.53805568),
    ]);
$workout = $endomondo->createWorkout($workout);
echo $workout->getId();
```

When you want to edit workout use method updateWorkout. Remember that GPS cannot be update in this method.