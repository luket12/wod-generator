<?php

namespace Wod\Models;

/**
 *
 * A model representing an exercise object
 *
 * Class Exercise
 * @package Wod\Models
 */
class Exercise
{
    /**
     * @var String
     */
    private $name;
    /**
     * @var String
     */
    private $type;
    /**
     * @var Int
     */
    private $limit;

    /**
     * Exercise constructor.
     * @param String $name
     * @param String $type
     * @param Int $limit
     */
    public function __construct(String $name, String $type, Int $limit)
    {
        $this->name = $name;
        $this->type = $type;
        $this->limit = $limit;
    }

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return Int
     */
    public function getLimit(): Int
    {
        return $this->limit;
    }

    /**
     * Returns true if the exercise has a limit
     *
     * @return bool
     */
    public function hasLimit(): bool
    {
        return $this->limit > 0;
    }

    /**
     * @param Int $limit
     */
    public function setLimit(Int $limit): void
    {
        $this->limit = $limit;
    }
}
