<?php

namespace Wod\Models;

/**
 * Model representing a workout, containing a number of completed exercise sets
 *
 * Class Workout
 * @package Wod\Models
 */
class Workout
{
    /**
     * @var array
     */
    private $exerciseSets;

    /**
     * Workout constructor.
     * @param array $exerciseSets
     */
    public function __construct(array $exerciseSets = [])
    {
        $this->exerciseSets = $exerciseSets;
    }

    /**
     * @return array
     */
    public function getWorkoutSets(): array
    {
        return $this->exerciseSets;
    }

    /**
     * @param array $exerciseSets
     */
    public function setWorkoutSets(array $exerciseSets): void
    {
        $this->exerciseSets = $exerciseSets;
    }

    /**
     *
     * Add a completed exercise set to the workout
     *
     * @param ExerciseSet $set
     */
    public function addWorkoutSet(ExerciseSet $set): void
    {
        $this->exerciseSets[] = $set;
    }

    /**
     * Adds a break to the exercise sets array for this workout
     */
    public function addBreak(): void
    {
        $this->exerciseSets[] = null;
    }
}
