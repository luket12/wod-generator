<?php

namespace Wod;

use Wod\Models\Exercise;
use Wod\Models\ExerciseSet;
use Wod\Models\User;
use Wod\Models\Workout;

/**
 * The global data store for users, exercises and their attached workouts, workout sets
 *
 * Class WorkoutStore
 */
class WorkoutStore
{
    private $users;
    private $exercises;

    /**
     * WorkoutStore constructor.
     * @param $users
     * @param $exercises
     */
    public function __construct($users, $exercises)
    {
        $this->fillStore($users, $exercises);
    }

    /**
     * @return array
     */
    public function getExercises(): array
    {
        return $this->exercises;
    }

    /**
     * @return array
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @param array $userData
     * @param array $exerciseData
     */
    public function fillStore(array $userData, array $exerciseData): void
    {
        $users = [];
        foreach ($userData as $user) {
            $users[] = new User($user['name'], $user['type'], new Workout());
        }

        $this->users = $users;

        $exercises = [];
        foreach ($exerciseData as $exercise) {
            $exercises[] = new Exercise($exercise['name'], $exercise['type'], $exercise['limit']);
        }

        $this->exercises = $exercises;
    }

    /**
     * @param User $user
     * @param Exercise $exercise
     * @param $setNumber
     */
    public function addExerciseSetForUser(User $user, Exercise $exercise, $setNumber): void
    {
        $userWorkout = $user->getWorkout();

        // todo: pass in an exercise object instead
        $userWorkout->addWorkoutSet(new ExerciseSet($exercise->getName(), $exercise->getType(), $exercise->getLimit(), $setNumber));
    }

    /**
     * @param User $user
     */
    public function addBreakForUser(User $user): void
    {
        $userWorkout = $user->getWorkout();

        $userWorkout->addBreak();
    }
}