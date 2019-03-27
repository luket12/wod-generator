<?php

namespace Wod;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use DateTime;

/**
 * This class will generate the output for the workout of the day
 * Instantiates the users passed in
 * Performs the algorithms on those users to output a workout of the day
 *
 * Class Wod
 */
class WorkoutGenerator
{
	/**
	 * Generates the full data store, populating each user with exercises and breaks as well as set times
	 *
	 * @param $setTotal
	 * @param WorkoutStore $dataStore
	 * @return WorkoutStore
	 */
	public static function generate($setTotal, workoutStore $dataStore): WorkoutStore
	{
		// Store the needed data store pieces
		$users = $dataStore->getUsers();

		for ($currentSet = 1; $currentSet <= $setTotal; $currentSet++) {
			foreach ($users as $user) {
				$exercisePicker = new ExercisePicker($dataStore->getExercises(), $user->getLevel());

				// Check if a break is required before assigning an exercise
				if ($exercisePicker->needsBreak($currentSet, $setTotal)) {
					$dataStore->addBreakForUser($user);
				} else {
					// Pick and store an exercise for this set
					$exercise = $exercisePicker->pickExercise($user, $users, $currentSet);

					$dataStore->addExerciseSetForUser($user, $exercise, $currentSet);
				}
			}
		}

		/** @var WorkoutStore $dataStore */
		return $dataStore;
	}
}
