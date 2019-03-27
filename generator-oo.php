<?php
/**
 * Application entry point,
 * sets up the global data store and generates a workout
 */

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
        'workoutSet' => []
    ],
    'Lisa' => [
        'name' => 'Lisa',
        'type' => 'beginner',
        'workoutSet' => []
    ],
    'Ronald' => [
        'name' => 'Ronald',
        'type' => 'advanced',
        'workoutSet' => []
    ],
    'Steve' => [
        'name' => 'Steve',
        'type' => 'advanced',
        'workoutSet' => []
    ],
    'Ron' => [
        'name' => 'Ron',
        'type' => 'advanced',
        'workoutSet' => []
    ],
];

// Use CLI args to determine whether its to tailor the output later on for browser
$isConsole = (isset($argv) && count($argv) > 0);

if ($isConsole) {
    if (count($argv) < 3 || !is_int((int) $argv[1]) || !is_int((int) $argv[2])) {
        exit("Please enter in the following format - generator {number of sets} {set time in seconds}\n");
    }
    if ($argv[1] <= 0 || $argv[1] >= 50) {
        exit("Please enter a valid range of workout sets between 1 and 50\n");
    } elseif ($argv[2] < 30 || $argv[2] > 120) {
        exit("Please enter a valid workout set time (seconds) between 30 and 120\n");
    }

	$numSets = (int) $argv[1];
	$setTimeSeconds = (int) $argv[2];
} else {
    $numSets = 30;
    $setTimeSeconds = 60;
}

// Generate a workout, from the workout data store, and simply output it
$generatedWorkout = WorkoutGenerator::generate($numSets, new WorkoutStore($userData, $exerciseData, $numSets));
Wod::output($setTimeSeconds, $isConsole, $generatedWorkout);

