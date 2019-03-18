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
     * Round up minutes to the nearest upper interval of a DateTime object.
     *
     * @param \DateTime $dateTime
     * @param int $minuteInterval
     * @return \DateTime
     */
    public static function roundUpToMinuteInterval(\DateTime $dateTime, $minuteInterval = 10): DateTime
    {
        return $dateTime->setTime(
            $dateTime->format('H'),
            ceil($dateTime->format('i') / $minuteInterval) * $minuteInterval,
            0
        );
    }

	/**
	 * Generates the full data store, populating each user with exercises and breaks as well as set times
	 *
	 * @param $setTotal
	 * @param WorkoutStore $dataStore
	 * @return WorkoutStore
	 */
	public static function generate($setTotal, workoutStore $dataStore): WorkoutStore
	{
		for ($currentSet = 1; $currentSet <= $setTotal; $currentSet++) {


			foreach ($dataStore->getUsers() as $user) {
				$exercisePicker = new ExercisePicker($dataStore->getExercises());
				$numBreaks = ($user->getLevel() === 'beginner') ? 4 : 2;

				if ($exercisePicker->userNeedsbreak($user, $currentSet, $setTotal, $numBreaks)) {
					$dataStore->addBreakForUser($user);
				} else {
					$exercise = $exercisePicker->pickExercise($user, $currentSet);

					$exercise = $exercisePicker->applyExerciseLimit($exercise, $dataStore->getUsers(), $currentSet);

					$dataStore->addExerciseSetForUser($user, $exercise, $currentSet);
				}
			}
		}

		/** @var WorkoutStore $dataStore */
		return $dataStore;
	}
}
