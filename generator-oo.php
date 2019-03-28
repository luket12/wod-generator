<?php
/**
 * Application entry point sets up the global data store and generates a workout
 */

use Wod\Wod;
use Wod\WorkoutGenerator;
use Wod\WorkoutStore;

require 'vendor/autoload.php';

if (!file_exists('exercises.php') || !file_exists('users.php')) {
    exit('Please check your configuration files, they should be ./users.php and ./exercises.php');
}

// Load configuration array files, (modify these how you please - add / remove / configure exercises)
require_once 'exercises.php';
require_once 'users.php';

// Use CLI args to determine whether its to tailor the output later on for browser
$isConsole = (isset($argv) && count($argv) > 0);

if (!$isConsole) {
    define('TOTALSETS', 30);
    define('SETINSECONDS', 60);
}

if (count($argv) < 3 || !is_int((int) $argv[1]) || !is_int((int) $argv[2])) {
    exit("Please enter in the following format - generator {number of sets} {set time in seconds}\n");
}
if ($argv[1] <= 0 || $argv[1] >= 50) {
    exit("Please enter a valid range of workout sets between 1 and 50\n");
} elseif ($argv[2] < 30 || $argv[2] > 120) {
    exit("Please enter a valid workout set time (seconds) between 30 and 120\n");
}

define('TOTALSETS', (int) $argv[1]);
define('SETINSECONDS', (int) $argv[2]);

// Generate a workout, from the workout data store, and simply output it
$workoutDataStore = new WorkoutStore($userData, $exerciseData);
$generatedWorkout = WorkoutGenerator::generate($workoutDataStore->getUsers(), $workoutDataStore->getExercises());
Wod::output($isConsole, $generatedWorkout);
