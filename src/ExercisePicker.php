<?php

namespace Wod;

use Wod\Models\Exercise;
use Wod\Models\User;

/**
 * This class is used for picking the exercises and applying the rules
 * and key business logic (Can abstract the rules away)
 *
 * Class ExercisePicker
 * @package Wod
 */
class ExercisePicker
{
    /**
     * @var
     */
    private $exercises;

    /**
     * @var
     */
    private $users;

    /**
     * ExercisePicker constructor.
     * @param $exercises
     * @param array $users
     */
    public function __construct($exercises, array $users)
    {
        $this->exercises = $exercises;
        $this->users = $users;
    }

    /**
     * @param mixed $exercises
     */
    public function setExercises($exercises): void
    {
        $this->exercises = $exercises;
    }

    /**
     * Checks that the exercise in the previous workout set is not the same type
     * replacing it with a different exercise type
     *
     * @param $set
     * @param Exercise $exercise
     * @param User $user
     * @param $exerciseType
     *
     * @return Exercise
     */
    public function disallowDoubleExercisesOfType($set, Exercise $exercise, User $user, $exerciseType): Exercise
    {
        // sanity check to avoid offset issue
        if ($set > 1 && $exercise->getType() === $exerciseType) {
            // check -2 index to account for 0 array index
            $previousSetExercise = $user->getWorkout()->getWorkoutSets()[$set-2];

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
     *
     * @return Exercise
     */
    public function applyMaximumToExerciseForType
    (
        User $user,
        Exercise $chosenExercise,
        $userType = 'beginner',
        $exerciseName = 'Handstand Practice'
    ): Exercise
    {
        // Beginners cannot do handstands more than once
        if ($user->getLevel() === $userType && $chosenExercise->getName() === $exerciseName) {
            // How many times has the user done this before
            $exerciseTimesCompleted = $user->getWorkout()->getCountOfSameExercise($chosenExercise);

            // If the beginner has done hand stands once already, change exercise until not hand stand anymore
            while ($exerciseTimesCompleted >= 1 && $chosenExercise->getName() === $exerciseName) {
                $chosenExercise = $this->getRandomExercise();
            }
        }

        return $chosenExercise;
    }

    /**
     * Gets a random exercise from the exercises list
     *
     * @return Exercise
     */
    public function getRandomExercise(): Exercise
    {
        return $this->exercises[array_rand($this->exercises)];
    }


    /**
     * Applies an exercise limit to the exercise so no more of that exercise can be chosen
     *
     * @param Exercise $exercise
     * @param $currentSet
     *
     * @return Exercise
     */
    public function applyExerciseLimit(Exercise $exercise, $currentSet): Exercise
    {
        // Check the exercise has a limit of users already using the equipment
        if ($currentSet > 0 && $exercise->hasLimit()) {
            // Filter down user count to only users which have done the same exercise that was randomly chosen
            $users = array_filter($this->users, function ($user) use ($currentSet, $exercise) {
                $userExerciseList = $user->getWorkout()->getWorkoutSets();

                // Check only the users set that is the same set we are on (to see the exercise limit)
                if (!empty($userExerciseList) && count($userExerciseList) === $currentSet) {
                    if ($userExerciseList[$currentSet-1] !== null) {
                        $userCurrentExercise = $userExerciseList[$currentSet-1]->getExercise();
                        if ($exercise->getName() === $userCurrentExercise->getName()) {
                            return $user;
                        }
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
     * Picks an exercise using all of the rules required
     *
     * @param User $user
     * @param $currentSet
     *
     * @return Exercise
     */
    public function pickExercise(User $user, $currentSet): Exercise
    {
        $chosenExercise = $this->getRandomExercise();

        $chosenExercise = $this->applyMaximumToExerciseForType($user, $chosenExercise);

        $chosenExercise = $this->disallowDoubleExercisesOfType($currentSet, $chosenExercise, $user, 'cardio');

        $chosenExercise = $this->applyExerciseLimit($chosenExercise, $currentSet);

        return $chosenExercise;
    }
}
