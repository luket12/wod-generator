<?php

namespace Wod\Tests;

use Wod\Models\User;
use Wod\Models\Workout;

class UserTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {

        parent::setUp();
    }

    /**
     * @test
     * @covers \Wod\ExercisePicker::needsBreak
     */
    public function testNeedsBreak()
    {
        $workoutA = new Workout();
        $beginnerUser = new User('Test user', 'beginner', $workoutA);
        $advancedUser = new User('Test user', 'advanced', $workoutA);

        $firstSet = 1;
        $lastSet = 20;
        define('TOTALSETS', 20);

        // Check no breaks are made at the start or end for advanced
        $this->assertFalse($beginnerUser->needsBreak($firstSet, TOTALSETS));
        $this->assertFalse($beginnerUser->needsBreak($lastSet, TOTALSETS));

        // Same checks for beginner
        $this->assertFalse($advancedUser->needsBreak($firstSet, TOTALSETS));
        $this->assertFalse($advancedUser->needsBreak($lastSet, TOTALSETS));

        // Check advanced users have 4 breaks

        // Check beginner users have 2 breaks (Loop and count)
    }
}
