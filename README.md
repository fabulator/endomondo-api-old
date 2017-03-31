Endomondo API Old
============

This is extensioin of basic wrapper for Endomondo API old. It is unofficial and everything you do with this library is full on your responsibility.

This Old API is based on API from mobile app and have limited functionality. On the other side it is only API which can create new workouts with GPS points.

##Auth

You can auth to API by your login and password.

```php
$endomondo = new \Fabulator\Endomondo\EndomondoApiOld();
$endomondo->requestAuthToken(ENDOMONDO_LOGIN, ENDOMONDO_PASSWORD);
```

### Getting workouts

You can fetch single workout:
```php
$workout = $endomondo->getWorkout('771832456');
echo $workout->toString();
```

or list of last workouts:
```php
foreach($endomondo->getWorkouts(3) as $workout) {
    echo $workout->toString() . "\n";
}
```

### Creating workouts

You can also create new workouts including GPS:

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

When you want to edit workout use method updateWorkout. Remember that GPS cannot be update in this method.