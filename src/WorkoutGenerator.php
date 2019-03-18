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
     * @param $setTime
     * @param $dataStore
     * @return WorkoutStore
     */
    public static function generate($setTotal, $setTime, WorkoutStore $dataStore): WorkoutStore
    {
        $interval = CarbonInterval::seconds($setTime);
        $programmeStartTime = self::roundUpToMinuteInterval(Carbon::now(),  10);

        for ($currentSet = 1; $currentSet <= $setTotal; $currentSet++) {
            $startTime = (isset($endTime)) ? $endTime : $programmeStartTime;
            $endTime = $startTime->copy()->add($interval);

            foreach ($dataStore->getUsers() as $user) {
                $exercisePicker = new ExercisePicker($dataStore->getExercises());
                $numBreaks = ($user->getLevel() === 'beginner') ? 4 : 2;

                if ($exercisePicker->userNeedsbreak($user, $currentSet, $setTotal, $numBreaks)) {
                    $dataStore->addBreakForUser($user);
                } else {
                    $exercise = $exercisePicker->pickExercise($user, $currentSet);

                    $exercise = $exercisePicker->applyExerciseLimit($exercise, $dataStore->getUsers(), $currentSet);

                    $dataStore->addExerciseSetForUser($user, $exercise, $currentSet, $startTime, $endTime);
                }
            }
        }

        /** @var WorkoutStore $dataStore */
        return $dataStore;
    }

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
}
