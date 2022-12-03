<?php

$input = stream_get_contents(STDIN);

$elvesFoods = explode("\n\n", $input);

$elvesCalories = array_map(
    fn($elfFoods) => array_sum(explode("\n", $elfFoods)),
    $elvesFoods
);

sort($elvesCalories);
$top3 = array_slice($elvesCalories, -3);

echo array_sum($top3);
