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
	public function setUp(): void
	{
		parent::setUp();

		$exercises = [
			$exerciseA = new Exercise('test', 'typeA', 1),
			$exerciseB = new Exercise('testB', 'typeA', 0),
			$differentExercise = new Exercise('differentType', 'typeB', 0)
		];

		$this->exercisePicker = new ExercisePicker($exercises);
	}

	/**
	 * @test
	 * @covers \Wod\ExercisePicker::needsBreak
	 */
	public function testNeedsBreak()
	{
		$setTotalA = 10;
		$numBreaksA = 5;
		$setA = 2;

		$setTotalB = 20;
		$numBreaksB = 2;
		$setB = 11;

		$this->assertTrue($this->exercisePicker->needsBreak($setA, $setTotalA, $numBreaksA));
		$this->assertTrue($this->exercisePicker->needsBreak($setB, $setTotalB, $numBreaksB));
	}

	/**
	 * @test
	 * @covers \Wod\ExercisePicker::disallowDoubleExercisesOfType
	 */
	public function testDisallowDoubleExercisesOfType()
	{
		$exerciseA = new Exercise('test', 'typeA', 0);
		$differentExercise = new Exercise('differentType', 'typeB', 0);

		$workOutSets = [
			new ExerciseSet($exerciseA, 1),
			new ExerciseSet($exerciseA, 2),
		];

		$set = 3;

		$this->assertNotEquals($exerciseA, $this->exercisePicker->disallowDoubleExercisesOfType($set, $exerciseA, $workOutSets, 'typeA'));
		$this->assertEquals($differentExercise, $this->exercisePicker->disallowDoubleExercisesOfType($set, $exerciseA, $workOutSets, 'typeA'));
	}

	/**
	 * @test
	 * @covers \Wod\ExercisePicker::applyMaximumToExerciseForType
	 */
	public function testApplyMaximumToExerciseType()
	{
		$this->exercisePicker->setExercises([
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

		$this->assertEquals($differentExercise, $this->exercisePicker->applyMaximumToExerciseForType($userA, $exerciseA, 'beginner', 'test'));
		$this->assertNotEquals($exerciseA, $this->exercisePicker->applyMaximumToExerciseForType($userB, $differentExercise, 'beginner', 'test'));
	}
}
