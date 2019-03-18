<?php

namespace Wod;

use Wod\Models\Exercise;
use Wod\Models\User;

/**
 * This class is used for picking the exercises and applying the rules
 * and key business logic
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
	 * Returns true when the current set should be a break
	 *
	 * @param $set
	 * @param $setTotal
	 * @param $numBreaks
	 *
	 * @return bool
	 */
    public function needsBreak($set, $setTotal, $numBreaks): bool
    {
        // Divides the required breaks by the number of sets
        if (($set + 1) % ((int) floor($setTotal / ($numBreaks + 1))) === 0) {
			// Reduce the factor by one to space them out and not at the end of the workout
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
	 *
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
            $exerciseTimesCompleted = $this->getCountOfSameExercise($user->getWorkout()->getWorkoutSets(), $chosenExercise);

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
     * Returns a count of same exercises done in this workout
     *
     * @param array $workoutSets
     * @param Exercise $exercise
     * @return int
     */
    public static function getCountOfSameExercise(array $workoutSets, Exercise $exercise): int
    {
        return count(array_filter($workoutSets, function($set) use ($exercise) {
        	// Dont check if the set is a break (null)
            if ($set !== null) {
            	// return the matching exercise
                return $set->getExercise()->getName() === $exercise->getName();
            }
        }));
    }

    /**
	 * Applies an exercise limit to the exercise so no more of that exercise can be chosen
	 *
     * @param Exercise $exercise
     * @param array $userStore
     * @param $currentSet
	 *
     * @return Exercise
     */
    public function applyExerciseLimit(Exercise $exercise, array $userStore, $currentSet): Exercise
    {
        // Check the exercise has a limit of users already using the equipment
        if ($currentSet > 0 && $exercise->hasLimit()) {
            // Filter down user count to only users which have done the same exercise that was randomly chosen
            $users = array_filter($userStore, function($user) use ($currentSet, $exercise) {
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
	 * @param $user
	 * @param $users
	 * @param $currentSet
	 *
	 * @return Exercise
	 */
    public function pickExercise($user, $users, $currentSet): Exercise
    {
        $chosenExercise = $this->getRandomExercise();

        $chosenExercise = $this->applyMaximumToExerciseForType($user, $chosenExercise);

        $chosenExercise = $this->disallowDoubleExercisesOfType($currentSet, $chosenExercise, $user->getWorkout()->getWorkoutSets(), 'cardio');

		$chosenExercise = $this->applyExerciseLimit($chosenExercise, $users, $currentSet);

        return $chosenExercise;
    }
}
