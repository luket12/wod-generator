<?php

namespace Wod\Models;

use DateTime;

/**
 *
 * A model representing an Exercise set, such as the exercise and its exercise set times
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
    private $startTime;
    /**
     * @var DateTime
     */
    private $endTime;

    /**
     * ExerciseSet constructor.
     * @param Exercise $exercise
     * @param $setNumber
     * @param DateTime $startTime
     * @param DateTime $endTime
     */
    public function __construct(Exercise $exercise, $setNumber, DateTime $startTime, DateTime $endTime)
    {
        $this->exercise = $exercise;
        $this->setNumber = $setNumber;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
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
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param mixed $startTime
     */
    public function setStartTime($startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * @return mixed
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @param mixed $endTime
     */
    public function setEndTime($endTime): void
    {
        $this->endTime = $endTime;
    }
}