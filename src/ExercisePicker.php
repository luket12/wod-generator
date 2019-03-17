<?php

namespace Wod;

use Wod\Models\Exercise;
use Wod\Models\User;

class ExercisePicker
{
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
     * @param $set
     * @param $setTotal
     * @param $userType
     * @return bool
     */
    public static function isBreak($set, $setTotal, $userType)
    {
        $numBreaks = ($userType === 'beginner') ? 4 : 2;

        // Algorithm for applying breaks at a suitable time during the workout
        if (($set + 1) % ((int) floor($setTotal / ($numBreaks + 1))) === 0) {
            if ($set < ($setTotal - 1)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $exercises
     * @return Exercise
     */
    public static function getRandomExerciseForUser(array $exercises): Exercise
    {
        $exercise = $exercises[array_rand($exercises)];

        return $exercise;
    }

    /**
     * @param $set
     * @param Exercise $exercise
     * @param array $exercises
     * @param array $workoutSets
     * @param $exerciseType
     * @return Exercise
     */
    public function disallowDoubleExercisesOfType($set, Exercise $exercise, array $workoutSets, $exerciseType): Exercise
    {
        // exercises of this type cannot precede one another
        if ($set > 1 && $exercise->getType() === $exerciseType) {
            // two less index as the array index starts at 0
            $previousSetExercise = $workoutSets[$set-2];

            // check the previous exercise was not also cardio and choose new one until its not cardio
            if ($previousSetExercise !== null && $previousSetExercise->getExercise()->getType() === $exerciseType) {
                while ($exercise->getType() === $exerciseType) {
                    $exercise = $this->exercises[array_rand($this->exercises)];
                }
            }
        }

        return $exercise;
    }

    public function applyHandstandRule(User $user, Exercise $exercise, array $workoutSets): Exercise
    {
        // Beginners cannot do handstands more than once
        if ($user->getLevel() === 'beginner' && $exercise->getName() ==='Hand Stand') {
            // How many times has the user done this before
            $handStandTotal = self::getCountOfSameExercise($workoutSets, $exercise);

            // If the beginner has done hand stands once already, change exercise until not hand stand anymore
            while ($handStandTotal >= 1 && $exercise->getName() === 'Hand Stand') {
                $exercise = $this->exercises[array_rand($this->exercises)];
            }
        }

        return $exercise;
    }

    /**
     * @param array $workoutSets
     * @param Exercise $exercise
     * @return int
     */
    public static function getCountOfSameExercise(array $workoutSets, Exercise $exercise): int
    {
        return count(array_filter($workoutSets, function($set) use ($exercise) {
            if ($set !== null) {
                return $set->getExerciseName() === $exercise->getName();
            }
        }));
    }

    /**
     * @param Exercise $exercise
     * @param array $exercises
     * @param array $userStore
     * @param $currentSet
     * @return Exercise
     */
    public static function applyExerciseLimit(Exercise $exercise, array $userStore, array $exercises, $currentSet): Exercise
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
                $exercise = $exercises[array_rand($exercises)];
            }
        }

        return $exercise;
    }

    public function pickExercise($user, $currentSet)
    {
        $usersWorkout = $user->getWorkout()->getWorkoutSets();

        $exercise = $this->getRandomExerciseForUser($this->exercises);

        $exercise = $this->applyHandstandRule($user, $exercise, $usersWorkout);

        $exercise = $this->disallowDoubleExercisesOfType($currentSet, $exercise, $usersWorkout, 'cardio');

        return $exercise;
    }
}