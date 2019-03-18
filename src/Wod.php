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

		$workoutUsers = $workout->getUsers();
		$workoutStartTime = WorkoutGenerator::roundUpToMinuteInterval(Carbon::now(),  10);

		echo "<p>The programme will begin at: {$workoutStartTime->format('d-m-Y H:i:s')}\n</p>";
		for ($setNumber = 1; $setNumber <= $workout->getNumSets(); $setNumber++) {
			$exerciseSet = '';

			$startTime = (isset($endTime)) ? $endTime : $workoutStartTime;
			$endTime = $startTime->copy()->add(CarbonInterval::seconds($setTimeSeconds));

			// For each user
			foreach ($workoutUsers as $key => $user) {
				$exercise = $user->getExerciseSetFromWorkout($setNumber);

				$exercise = ($exercise !== false) ? $exercise->getExercise()->getName() : 'Break';

				$exerciseSet .= "{$user->getName()} is on {$exercise}" . self::addUserDividerToOutput($workoutUsers, $key);
			}

			echo "<p>{$startTime->format('i:s')} to {$endTime->format('i:s')} - {$exerciseSet}\n</p>";
		}

    }

	/**
	 * Adds a divider between the user output string only when not the last user element
	 *
	 * @param $workoutUsers
	 * @param $currentKey
	 *
	 * @return string
	 */
    public static function addUserDividerToOutput($workoutUsers, $currentKey)
	{
		$workoutUsersTmp = array_keys($workoutUsers);

		$lastUser = end($workoutUsersTmp);

		return  ($lastUser !== $currentKey) ? " - " : '';
	}
}
