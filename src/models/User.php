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
}
