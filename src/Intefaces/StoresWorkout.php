<?php

namespace Wod\Interfaces;

interface StoresWorkout
{
    public function getUsers();

    public function getExercises();

    public function setUpStore($users, $exercises);
}
