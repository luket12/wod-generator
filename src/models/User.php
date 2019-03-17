<?php

namespace Wod\Models;

class User
{
    private $name;
    private $level;
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
}