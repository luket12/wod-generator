<?php

namespace Wod;

use Wod\Models\Exercise;
use Wod\Models\User;

class ExercisePicker
{
    /**
     * @var
     */
    private $exercises;

    /**
     * ExercisePicker constructor.
     * @param $exercises
     */
    public function __construct($exercises)
    {
        $this->exercises = $exercises;
    }

    /**
     * @return mixed
     */
    public function getExercises()
    {
        return $this->exercises;
    }

    /**
     * @param mixed $exercises
     */
    public function setExercises($exercises): void
    {
        $this->exercises = $exercises;
    }

    /**
     * @param User $user
     * @param $set
     * @param $setTotal
     * @return bool
     */
    public function userNeedsBreak(User $user, $set, $setTotal, $numBreaks): bool
    {
        // Algorithm for applying breaks at a suitable time during the workout
        if (($set + 1) % ((int) floor($setTotal / ($numBreaks + 1))) === 0) {
            if ($set < ($setTotal - 1)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks that the exercise in the previous workout set is not the same type
     * replacing it with a different exercise type
     *
     * @param $set
     * @param Exercise $exercise
     * @param array $workoutSets
     * @param $exerciseType
     * @return Exercise
     */
    public function disallowDoubleExercisesOfType($set, Exercise $exercise, array $workoutSets, $exerciseType): Exercise
    {
        // sanity check to avoid offset issue
        if ($set > 1 && $exercise->getType() === $exerciseType) {
            // check -2 index to account for 0 array index
            $previousSetExercise = $workoutSets[$set-2];

            // check the previous exercise was not also the same type and choose new one
            if ($previousSetExercise !== null && $previousSetExercise->getExercise()->getType() === $exerciseType) {
                while ($exercise->getType() === $exerciseType) {
                    $exercise = $this->getRandomExercise();
                }
            }
        }

        return $exercise;
    }

    /**
     *
     * Ensures that users of a type cannot exceed an exercise type maximum
     *
     * @param User $user
     * @param Exercise $chosenExercise
     * @param $userType
     * @param $exerciseName
     * @return Exercise
     */
    public function applyMaximumToExerciseForType
    (
        User $user,
        Exercise $chosenExercise,
        $userType = 'beginner',
        $exerciseName = 'Hand Stand'
    ): Exercise
    {
        // Beginners cannot do handstands more than once
        if ($user->getLevel() === $userType && $chosenExercise->getName() === $exerciseName) {
            // How many times has the user done this before
            $exerciseTimesCompleted = $this->getCountOfSameExercise($user->getWorkout()->getWorkoutSets(), $chosenExercise);

            // If the beginner has done hand stands once already, change exercise until not hand stand anymore
            while ($exerciseTimesCompleted >= 1 && $chosenExercise->getName() === $exerciseName) {
                $chosenExercise = $this->getRandomExercise();
            }
        }

        return $chosenExercise;
    }

    /**
     * @return Exercise
     */
    public function getRandomExercise(): Exercise
    {
        return $this->exercises[array_rand($this->exercises)];
    }

    /**
     *
     * Returns a count of exercises done by the
     *
     * @param array $workoutSets
     * @param Exercise $exercise
     * @return int
     */
    public static function getCountOfSameExercise(array $workoutSets, Exercise $exercise): int
    {
        return count(array_filter($workoutSets, function($set) use ($exercise) {
            if ($set !== null) {
                return $set->getExercise()->getName() === $exercise->getName();
            }
        }));
    }

    /**
     * @param Exercise $exercise
     * @param array $userStore
     * @param $currentSet
     * @return Exercise
     */
    public function applyExerciseLimit(Exercise $exercise, array $userStore, $currentSet): Exercise
    {
        // Check the exercise has a limit of users already using the equipment
        if ($currentSet > 0 && $exercise->hasLimit()) {

            // Filter down user count to only users which have done the same exercise that was randomly chosen
            $users = array_filter($userStore, function($user) use ($currentSet, $exercise) {
                $userExerciseList = $user->getWorkout()->getWorkoutSets();
                $userExerciseCount = count($userExerciseList);

                if (!empty($userExerciseList) && $userExerciseCount === $currentSet) {
                    $userCurrentExercise = $userExerciseList[$currentSet-1]->getExercise();

                    if ($exercise->getName() === $userCurrentExercise->getName()) {
                        return $user;
                    }
                }
            });

            // Pick a new exercise to use if the limit has been exceeded for that exercise
            if (count($users) === $exercise->getLimit()) {
                $exercise = $this->getRandomExercise();
            }
        }

        return $exercise;
    }

    /**
     * @param $user
     * @param $currentSet
     * @return Exercise
     */
    public function pickExercise($user, $currentSet): Exercise
    {
        $chosenExercise = $this->getRandomExercise();

        $chosenExercise = $this->applyMaximumToExerciseForType($user, $chosenExercise);

        $chosenExercise = $this->disallowDoubleExercisesOfType($currentSet, $chosenExercise, $user->getWorkout()->getWorkoutSets(), 'cardio');

        return $chosenExercise;
    }
}