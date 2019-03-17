<?php

namespace Wod\Models;

class ExerciseSet
{
    private $exerciseName;
    private $exerciseType;
    private $setNumber;
    private $limit;

    /**
     * Set constructor.
     * @param $exerciseName
     * @param $exerciseType
     * @param $limit
     * @param $setNumber
     */
    public function __construct($exerciseName, $exerciseType, $limit, $setNumber)
    {
        $this->exerciseName = $exerciseName;
        $this->exerciseType = $exerciseType;
        $this->limit = $limit;
        $this->setNumber = $setNumber;
    }

    /**
     * @return mixed
     */
    public function getExerciseName()
    {
        return $this->exerciseName;
    }

    /**
     * @param mixed $exerciseName
     */
    public function setExerciseName($exerciseName): void
    {
        $this->exerciseName = $exerciseName;
    }

    /**
     * @return mixed
     */
    public function getExerciseType()
    {
        return $this->exerciseType;
    }

    /**
     * @param mixed $exerciseType
     */
    public function setExerciseType($exerciseType): void
    {
        $this->exerciseType = $exerciseType;
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