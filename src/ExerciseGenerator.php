<?php

namespace Wod;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use DateTime;
use stdClass;

/**
 * This class will generate the output for the workout of the day
 * Instantiates the users passed in
 * Performs the algorithms on those users to output a workout of the day
 *
 * Class Wod
 */
class ExerciseGenerator
{
    private $dataStore;

    public function __construct(WorkoutStore $dataStore)
    {
        $this->dataStore = $dataStore;
    }

    /**
     * Creates the output for the workout of the day based on the user input
     * @param $setTotal
     * @param $setTime
     * @return bool
     */
    public function generate($setTotal, $setTime): bool
    {
        $interval = CarbonInterval::seconds($setTime);
        $workoutSet = new stdClass();
        $programmeStartTime = $this->roundUpToMinuteInterval(Carbon::now(),  10);

        for ($currentSet = 1; $currentSet <= $setTotal; $currentSet++) {
            $workoutSet->startTime = (!isset($workoutSet->startTime)) ? $programmeStartTime : $workoutSet->endTime;
            $workoutSet->endTime = $workoutSet->startTime->copy()->add($interval);

            // construct the Output
            $dataStore = $this->getDataStore();

            foreach ($dataStore->getUsers() as $user) {
                if (ExercisePicker::isBreak($currentSet, $setTotal, $user->getLevel())) {
                    $dataStore->addBreakForUser($user);
                } else {
                    $workoutSets = $user->getWorkout()->getWorkoutSets();

                    $exercises = $dataStore->getExercises();

                    $exercise = ExercisePicker::getRandomExerciseForUser($exercises);

                    $exercise = ExercisePicker::applyHandstandRule($user, $exercise, $exercises, $workoutSets);

                    $exercise = ExercisePicker::applyCardioRule($currentSet, $exercise, $exercises, $workoutSets);

                    $exercise = ExercisePicker::applyExerciseLimit($exercise, $dataStore->getUsers(), $exercises, $currentSet);

                    $dataStore->addExerciseSetForUser($user, $exercise, $currentSet);
                }
            }
        }

        dd($dataStore);

        return true;
    }

    /**
     * @return WorkoutStore
     */
    public function getDataStore(): WorkoutStore
    {
        return $this->dataStore;
    }

    /**
     * Round up minutes to the nearest upper interval of a DateTime object.
     *
     * @param \DateTime $dateTime
     * @param int $minuteInterval
     * @return \DateTime
     */
    public function roundUpToMinuteInterval(\DateTime $dateTime, $minuteInterval = 10): DateTime
    {
        return $dateTime->setTime(
            $dateTime->format('H'),
            ceil($dateTime->format('i') / $minuteInterval) * $minuteInterval,
            0
        );
    }
}