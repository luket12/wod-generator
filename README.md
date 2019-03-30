# Workout of the Day Generator


## Instructions


```sh
$ Git clone https://github.com/luket12/wod-generator.git
```
```sh
cd wod-generator/ && composer install
```
```sh
run “php generator-oo {numSets} {secondsPerSet}”
```
Or set up a VHost in nginx / Apache and view output in a browser

## Notes

The exercises and users can be modified easily, they are just an array store currently found in the root.
1. Exercises = './exercises.php'
2. Users = './users.php'

## What would I have done further?

Created a rule based system on an abstraction of the Exercise Picker with Interface.

Added more Exception handling

Finish the PHPUnit tests, on all vital areas

Completely rewrite the WOD outputter (spent least amount of time on this)

