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
        $totalSetsA = 20;

        // Check no breaks are made at the start or end for advanced
        $this->assertFalse($beginnerUser->needsBreak($firstSet, $totalSetsA));
        $this->assertFalse($beginnerUser->needsBreak($lastSet, $totalSetsA));

        // Same checks for beginner
        $this->assertFalse($advancedUser->needsBreak($firstSet, $totalSetsA));
        $this->assertFalse($advancedUser->needsBreak($lastSet, $totalSetsA));

        // Check advanced users have 4 breaks

        // Check beginner users have 2 breaks (Loop and count)
    }
}
