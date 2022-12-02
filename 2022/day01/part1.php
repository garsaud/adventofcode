<?php

$input = stream_get_contents(STDIN);

$elvesFoods = explode("\n\n", $input);

$elvesCalories = array_map(
    fn($elfFoods) => array_sum(explode("\n", $elfFoods)),
    $elvesFoods
);

echo max(...$elvesCalories);
