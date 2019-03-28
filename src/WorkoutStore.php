<?php

namespace Wod;

use DateTime;
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
    /**
     * @var
     */
    private $users;
    /**
     * @var
     */
    private $exercises;

    /**
     * WorkoutStore constructor.
     * @param $users
     * @param $exercises
     */
    public function __construct($users, $exercises)
    {
        $this->setUpStore($users, $exercises);
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
     * Fills the data store with the pre-filled array data
     *
     * @param array $userData
     * @param array $exerciseData
     */
    public function setUpStore(array $userData, array $exerciseData): void
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
}
