<?php

$time = preg_replace('/[^\d]/', '', fgets(STDIN));
$recordedDistance = preg_replace('/[^\d]/', '', fgets(STDIN));

/**
 * bruteforce sure works, but quadratic equations are much more interesting :)
 *
 * 0 = wait * -2 + time * wait - distance
 *
 * using quadratic formula:
 * boundaries = (-time ± √(time² - 4 * d)) / 2
 *
 * @see https://en.wikipedia.org/wiki/Quadratic_equation
 */

$lower = ceil($time - sqrt($time**2 - 4 * $recordedDistance)) / 2;
$higher = floor($time + sqrt($time**2 - 4 * $recordedDistance)) / 2;

echo $higher - $lower;
