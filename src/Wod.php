<?php

namespace Wod;

use Carbon\Carbon;
use Carbon\CarbonInterval;

/**
 *
 * Outputs the workout store into a desired format i.e. STDOUT
 *
 * Class Wod
 * @package Wod
 */
class Wod
{
	/**
	 * Outputs the workout of the day for the generated workout
	 *
	 * @param $setTimeSeconds
	 * @param WorkoutStore $workout
	 */
    public static function output($setTimeSeconds, WorkoutStore $workout)
    {
		$workoutStartTime = WorkoutGenerator::roundUpToMinuteInterval(Carbon::now(),  10);

		// Output workout start string
		$workoutUsers = $workout->getUsers();
		dd($workoutUsers);
		$lastUser = end(array_keys($workoutUsers));

		echo "The programme will begin at: {$workoutStartTime->format('d-m-Y H:i:s')}\n<br>";

		for ($set = 1; $set <= $workout->getNumSets(); $set++) {
			// Programme set string open
			$programmeSetOutput = '';

			$startTime = (isset($endTime)) ? $endTime : $workoutStartTime;
			$endTime = $startTime->copy()->add(CarbonInterval::seconds($setTimeSeconds));

			// For each user
			foreach ($workoutUsers as $key => $value) {
				// Get user name
				$name = $value->getName();

				// Get the exercise they are on
				$exercise = $value->getExerciseSetFromWorkout($set);

				$exercise = ($exercise) ? $exercise->getExercise()->getName() : 'Break';

				$userSetOutput = "{$name} is on {$exercise}";

				$userSetOutput .= ($lastUser !== $key) ? " - " : '';

				$programmeSetOutput .= $userSetOutput;
			}

			echo "{$startTime->format('i:s')} to {$endTime->format('i:s')} - {$programmeSetOutput}\n<br>";
		}

    }
}
