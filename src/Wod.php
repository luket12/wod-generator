<?php

namespace Wod;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use DateTime;

/**
 *
 * Outputs the workout store into a desired format i.e. STDOUT
 *
 * Class Wod
 * @package Wod
 */
class Wod
{
    /**
     * Outputs the workout of the day for the generated workout
     *
     * @param int $setTimeSeconds
     * @param int $numSets
     * @param $isConsole bool
     * @param array $workout
     */
    public static function output(int $setTimeSeconds, int $numSets, bool $isConsole, array $workoutUsers)
    {
        $workoutStartTime = self::roundUpToMinuteInterval(Carbon::now(), 10);

        // Notify the workout start time which is starting at the nearest even 10 min interval
        $workoutOutput = "<p>The programme will begin at: {$workoutStartTime->format('d-m-Y H:i:s')}\n</p>";

        for ($setNumber = 1; $setNumber <= $numSets; $setNumber++) {
            $usersExercisesForSet = '';

            // Get the timescales for this set
            $startTime = (isset($endTime)) ? $endTime : $workoutStartTime;
            $endTime = $startTime->copy()->add(CarbonInterval::seconds($setTimeSeconds));

            // Build the user exercise workout string for this set and append it to the main string
            foreach ($workoutUsers as $key => $user) {
                $exercise = $user->getExerciseSetFromWorkout($setNumber);

                $exercise = ($exercise !== false) ? $exercise->getExercise()->getName() : 'Break';

                $usersExercisesForSet .= "{$user->getName()} is on {$exercise}" . self::addUserDividerToOutput($workoutUsers, $key);
            }

            // Output the exercises for this set
            $workoutOutput .= "<p>{$startTime->format('i:s')} to {$endTime->format('i:s')} - {$usersExercisesForSet}\n</p>";
        }

        echo ($isConsole) ? strip_tags($workoutOutput) : $workoutOutput;
    }

    /**
     * Adds a divider between the user output string only when not the last user element
     *
     * @param $workoutUsers
     * @param $currentKey
     *
     * @return string
     */
    public static function addUserDividerToOutput($workoutUsers, $currentKey)
    {
        $workoutUsersTmp = array_keys($workoutUsers);

        $lastUser = end($workoutUsersTmp);

        return ($lastUser !== $currentKey) ? " - " : '';
    }


    /**
     * Round up minutes to the nearest upper interval of a DateTime object.
     *
     * @param \DateTime $dateTime
     * @param int $minuteInterval
     * @return DateTime
     */
    public static function roundUpToMinuteInterval(\DateTime $dateTime, $minuteInterval = 10): DateTime
    {
        return $dateTime->setTime(
            $dateTime->format('H'),
            ceil($dateTime->format('i') / $minuteInterval) * $minuteInterval,
            0
        );
    }
}
