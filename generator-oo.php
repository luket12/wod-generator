<?php

// todo: 15min interface for exercise logic (swap out implementation easily)
// todo: Add set start and end time to the users workout set
// todo: 10min namespaces
// todo: 1H unit tests
// todo: 30min sanity checks, type hinting
// todo: 15min Code standards, return types
// todo: 1H refactor
// todo: 30min Abstract away the logger

use Wod\WorkoutGenerator;
use Wod\WorkoutStore;

require 'vendor/autoload.php';

$exerciseData = [
    'jumpingJacks' => [
        'name' => 'Jumping Jacks',
        'type' => 'cardio',
        'limit' => 2
    ],
    'pullups' => [
        'name' => 'Pull Ups',
        'type' => 'strength',
        'limit' => 2
    ],
    'rows' => [
        'name' => 'Rows',
        'type' => 'strength',
        'limit' => 0
    ],
    'ring' => [
        'name' => 'Ring',
        'type' => 'strength',
        'limit' => 2
    ]
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

$rules = [
    [
        'exercise' => 'pullups',
        'userLevel' => 'beginner',
        'behaviour' => [
            'maximum' => 2,
        ]
    ],
    [
        'exercise' => 'handstand',
        'userLevel' => 'beginner',
        'behaviour' => [
            'minimum' => 2,
        ]
    ]
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

$dataStore = new WorkoutStore($userData, $exerciseData);
$workoutOfTheDay = new WorkoutGenerator($dataStore);
$workoutOfTheDay->generate($numSets, $setTimeSeconds);

