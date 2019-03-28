<?php

namespace Wod\Models;

/**
 *
 * A model representing the User
 *
 * Class User
 * @package Wod\Models
 */
class User
{
    /**
     * @var
     */
    private $name;
    /**
     * @var
     */
    private $level;
    /**
     * @var Workout
     */
    private $workout;

    public function __construct($name, $level, Workout $workout)
    {
        $this->name = $name;
        $this->level = $level;
        $this->workout = $workout;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level): void
    {
        $this->level = $level;
    }

    /**
     * Returns the number of breaks based on the user type
     *
     * @return int
     */
    public function getNumBreaks()
    {
        return ($this->level === 'beginner') ? 4 : 2;
    }

    /**
     * @return Workout
     */
    public function getWorkout(): Workout
    {
        return $this->workout;
    }

    /**
     * @param Workout $workout
     */
    public function setWorkout(Workout $workout): void
    {
        $this->workout = $workout;
    }

    /**
     * Gets the exercise sets from this workout
     *
     * @param $setNumber
     * @return bool|ExerciseSet
     */
    public function getExerciseSetFromWorkout($setNumber)
    {
        // Find the set that matches the set number we are looking for
        foreach ($this->workout->getWorkoutSets() as $set) {
            if ($set !== null && $set->getSetNumber() === $setNumber) {
                return $set;
            }
        }
        return false;
    }

    /**
     * Returns true when the current set should be a break
     *
     * @param $set
     * @return bool
     */
    public function needsBreak($set): bool
    {
        // Divides the required breaks by the number of sets
        if (($set + 1) % ((int) floor(TOTALSETS / ($this->getNumBreaks() + 1))) === 0) {
            // Reduce the factor by one to space them out and not at the end of the workout
            if ($set < (TOTALSETS - 1)) {
                return true;
            }
        }

        return false;
    }


    /**
     * Add a break for this user
     *
     */
    public function addBreakToWorkout(): void
    {
        $this->workout->addBreak();
    }

    /**
     * Adds an exercise set to the users workout
     *
     * @param Exercise $exercise
     * @param $setNumber
     */
    public function addExerciseSetToWorkout(Exercise $exercise, $setNumber): void
    {
        $this->workout->addWorkoutSet(new ExerciseSet($exercise, $setNumber));
    }
}
