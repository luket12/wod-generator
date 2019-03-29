<?php

namespace Wod\Tests;

use Wod\ExercisePicker;
use Wod\Models\Exercise;
use Wod\Models\ExerciseSet;
use Wod\Models\User;
use Wod\Models\Workout;

/**
 * Test the exercise picker functionality
 *
 * Class ExercisePickerTest
 * @package Wod\Tests
 */
class ExercisePickerTest extends \PHPUnit\Framework\TestCase
{
    protected $exercises;

    public function setUp(): void
    {
        parent::setUp();

        $this->exercises = [
            $exerciseA = new Exercise('test', 'typeA', 1),
            $exerciseB = new Exercise('testB', 'typeA', 0),
            $differentExercise = new Exercise('differentType', 'typeB', 0)
        ];
    }

    /**
     * @test
     * @covers \Wod\ExercisePicker::disallowDoubleExercisesOfType
     */
    public function testDisallowDoubleExercisesOfType()
    {
        $exercisePicker = new ExercisePicker($this->exercises, []);

        $exerciseOfTypeA = new Exercise('test', 'typeA', 0);
        $differentExercise = new Exercise('differentType', 'typeB', 0);


        $workOutSets = [
            new ExerciseSet($exerciseOfTypeA, 1),
            new ExerciseSet($exerciseOfTypeA, 2),
        ];

        $workout = new Workout($workOutSets);

        $user = new User('testUser', 'beginner', $workout);

        // check the following exercise has chosen the other type of exercise
        $this->assertNotEquals($exerciseOfTypeA, $exercisePicker->disallowDoubleExercisesOfType(3, $exerciseOfTypeA, $user, 'typeA'));
        $this->assertEquals($differentExercise, $exercisePicker->disallowDoubleExercisesOfType(3, $differentExercise, $user, 'typeA'));
    }

    /**
     * @test
     * @covers \Wod\ExercisePicker::applyMaximumToExerciseForType
     */
    public function testApplyMaximumToExerciseType()
    {
        $exercisePicker = new ExercisePicker($this->exercises, []);

        $exercisePicker->setExercises([
            $exerciseA = new Exercise('test', 'typeA', 2),
            $differentExercise = new Exercise('differentExercise', 'typeB', 0)
        ]);

        $workOutSets = [
            new ExerciseSet($exerciseA, 1),
            new ExerciseSet($exerciseA, 2),
        ];

        $userWorkoutA = new Workout($workOutSets);

        $userA = new User('testName', 'beginner', $userWorkoutA);

        $workoutSetsB = [
            new ExerciseSet($exerciseA, 1),
            new ExerciseSet($differentExercise, 2),
        ];

        $userWorkoutB = new Workout($workoutSetsB);

        $userB = new User('testNameB', 'beginner', $userWorkoutB);

        $this->assertEquals($differentExercise, $exercisePicker->applyMaximumToExerciseForType($userA, $exerciseA, 'beginner', 'test'));
        $this->assertNotEquals($exerciseA, $exercisePicker->applyMaximumToExerciseForType($userB, $differentExercise, 'beginner', 'test'));
    }
}
