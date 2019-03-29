<?php

namespace Wod;

use Wod\Interfaces\StoresWorkout;
use Wod\Models\User;

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
     * @param StoresWorkout $store
     * @return StoresWorkout $users The array containing users and their workout
     */
    public static function generate(StoresWorkout $store): StoresWorkout
    {
        $exercisePicker = new ExercisePicker($store->getExercises(), $store->getUsers());

        for ($currentSet = 1; $currentSet <= TOTALSETS; $currentSet++) {
            /** @var User $user */
            foreach ($store->getUsers() as $user) {
                // Check if a break is required before assigning an exercise
                if ($user->needsBreak($currentSet)) {
                    $user->addBreakToWorkout();
                } else {
                    // Pick and store an exercise for this set
                    $exercise = $exercisePicker->pickExercise($user, $currentSet);

                    $user->addExerciseSetToWorkout($exercise, $currentSet);
                }
            }
        }

        return $store;
    }
}
