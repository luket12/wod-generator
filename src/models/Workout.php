<?php

namespace Wod\Models;

class Workout
{
    private $exerciseSets;

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

    public function addWorkoutSet(ExerciseSet $set)
    {
        $this->exerciseSets[] = $set;
    }

    public function addBreak()
    {
        $this->exerciseSets[] = null;
    }
}