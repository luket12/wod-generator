<?php

namespace Wod\Tests;

use Wod\ExercisePicker;
use Wod\Models\Exercise;
use Wod\Models\ExerciseSet;

/**
 * Test the exercise picker functionality
 *
 * Class ExercisePickerTest
 * @package Wod\Tests
 */
class ExercisePickerTest extends \PHPUnit\Framework\TestCase
{
	private $exercisePicker;

	public function setUp(): void
	{
		parent::setUp();

		$exercises = [
			$exerciseA = new Exercise('test', 'typeA', 0),
			$exerciseB = new Exercise('testB', 'typeA', 0),
			$exerciseC = new Exercise('testC', 'typeB', 0)
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
		$exerciseB = new Exercise('testB', 'typeB', 0);
		$exerciseC = new Exercise('testB', 'typeC', 0);

		$workOutSets = [
			new ExerciseSet($exerciseA, 1),
			new ExerciseSet($exerciseA, 2),
			new ExerciseSet($exerciseC, 3)
		];

		$set = 3;

		//$this->assertNotEquals($exerciseB, $this->exercisePicker->disallowDoubleExercisesOfType($set, $exerciseB, $workOutSets, 'typeA'));

	}
}
