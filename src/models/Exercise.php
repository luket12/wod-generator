<?php

namespace Wod\Models;

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