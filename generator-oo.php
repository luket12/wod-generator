<?php

// todo: 0.5H unit tests
// todo: 30min Create STDOUT class to output the data

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Wod\Wod;
use Wod\WorkoutGenerator;
use Wod\WorkoutStore;

require 'vendor/autoload.php';

$exerciseData = [
    'jumpingJacks' => [
        'name' => 'Jumping Jacks',
        'type' => 'cardio',
        'limit' => 2
    ],
    'pushups' => [
        'name' => 'Push Ups',
        'type' => 'strength',
        'limit' => 2
    ],
    'frontsquats' => [
        'name' => 'Front Squats',
        'type' => 'strength',
        'limit' => 0
    ],
    'backsquats' => [
        'name' => 'Back Squats',
        'type' => 'strength',
        'limit' => 0
    ],
    'pullups' => [
        'name' => 'Pull Ups',
        'type' => 'strength',
        'limit' => 0
    ],
    'rings' => [
        'name' => 'Rings',
        'type' => 'strength',
        'limit' => 2
    ],
    'shortsprints' => [
        'name' => 'Short Sprints',
        'type' => 'cardio',
        'limit' => 0
    ],
    'handstandpractice' => [
        'name' => 'Handstand Practice',
        'type' => 'strength',
        'limit' => 0
    ],
    'jumpingrope' => [
        'name' => 'Jumping Rope',
        'type' => 'cardio',
        'limit' => 0
    ],
];

$userData = [
    'John' => [
        'name' => 'John',
        'type' => 'beginner',
        'workoutSet' => [],
        'breaks' => 0
    ],
    'Lisa' => [
        'name' => 'Lisa',
        'type' => 'beginner',
        'workoutSet' => [],
        'breaks' => 0
    ],
    'JJ' => [
        'name' => 'Ben',
        'type' => 'beginner',
        'workoutSet' => [],
        'breaks' => 0
    ],
    'Steve' => [
        'name' => 'Ben',
        'type' => 'beginner',
        'workoutSet' => [],
        'breaks' => 0
    ],
    'Ron' => [
        'name' => 'Ben',
        'type' => 'beginner',
        'workoutSet' => [],
        'breaks' => 0
    ],
];

if (isset($argv)) {
    $numSets = (int) $argv[1];
    $setTimeSeconds = (int) $argv[2];

    if (count($argv) < 3 || !is_int($numSets) || !is_int($setTimeSeconds)) {
        exit("Please enter in the following format - generator {number of sets} {set time in seconds}\n");
    }

    if ($argv[1] <= 0 || $argv[1] >= 50) {
        exit("Please enter a valid range of workout sets between 1 and 50\n");
    } elseif ($argv[2] < 30 || $argv[2] > 120) {
        exit("Please enter a valid workout set time (seconds) between 30 and 120\n");
    }
} else {
    $numSets = 30;
    $setTimeSeconds = 60;
}


$workoutStartTime = WorkoutGenerator::roundUpToMinuteInterval(Carbon::now(),  10);
$generatedWorkout = WorkoutGenerator::generate($numSets, CarbonInterval::seconds($setTimeSeconds), $workoutStartTime, new WorkoutStore($userData, $exerciseData));
Wod::output($workoutStartTime, $generatedWorkout);

