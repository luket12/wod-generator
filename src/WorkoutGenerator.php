<?php

namespace Wod;

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
     * @param $setTotal
     * @param array $users
     * @param array $exercises
     * @return array $users The array containing users and their workout
     */
    public static function generate($setTotal, array $users, array $exercises): array
    {
        $exercisePicker = new ExercisePicker($exercises);

        for ($currentSet = 1; $currentSet <= $setTotal; $currentSet++) {
            /** @var User $user */
            foreach ($users as $user) {
                // Check if a break is required before assigning an exercise
                if ($user->needsBreak($currentSet, $setTotal)) {
                    $user->addBreakToWorkout();
                } else {
                    // Pick and store an exercise for this set
                    $exercise = $exercisePicker->pickExercise($user, $users, $currentSet);

                    $user->addExerciseSetToWorkout($exercise, $currentSet);
                }
            }
        }

        return $users;
    }
}
