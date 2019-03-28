<?php

namespace Wod\Tests;

use PHPUnit\Framework\TestCase;
use Wod\WorkoutStore;

class WorkoutStoreTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     * @covers \Wod\WorkoutStore::setUpStore
     */
    public function usersConfigPopulatesUserStore()
    {
        $users = [
            'John' => [
                'name' => 'John',
                'type' => 'beginner',
                'workoutSet' => []
            ],
        ];

        $workoutStore = new WorkoutStore($users, []);
        $workoutStoreUsers = $workoutStore->getUsers();

        $this->assertNotNull($workoutStoreUsers[0]->getNumBreaks());
        $this->assertEquals($workoutStoreUsers[0]->getName(), 'John');
        $this->assertEquals($workoutStoreUsers[0]->getLevel(), 'beginner');
    }
}
