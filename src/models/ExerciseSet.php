<?php

namespace Wod\Models;

use DateTime;

/**
 *
 * A model representing an Exercise set
 *
 * Class ExerciseSet
 * @package Wod\Models
 */
class ExerciseSet
{
    /**
     * @var Exercise
     */
    private $exercise;
    /**
     * @var
     */
    private $setNumber;
    /**
     * @var DateTime
     */

    /**
     * ExerciseSet constructor.
     * @param Exercise $exercise
     * @param $setNumber
     */
    public function __construct(Exercise $exercise, $setNumber)
    {
        $this->exercise = $exercise;
        $this->setNumber = $setNumber;
    }

    /**
     * @return Exercise
     */
    public function getExercise(): Exercise
    {
        return $this->exercise;
    }

    /**
     * @param Exercise $exercise
     */
    public function setExercise(Exercise $exercise): void
    {
        $this->exercise = $exercise;
    }

    /**
     * @return mixed
     */
    public function getSetNumber()
    {
        return $this->setNumber;
    }

    /**
     * @param mixed $setNumber
     */
    public function setSetNumber($setNumber): void
    {
        $this->setNumber = $setNumber;
    }
}
