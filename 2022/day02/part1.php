<?php

$input = stream_get_contents(STDIN);

$rounds = explode("\n", $input);

$scoreAgainst = [
    'A' => [
        'X' => 1 + 3,
        'Y' => 2 + 6,
        'Z' => 3 + 0,
    ],
    'B' => [
        'X' => 1 + 0,
        'Y' => 2 + 3,
        'Z' => 3 + 6,
    ],
    'C' => [
        'X' => 1 + 6,
        'Y' => 2 + 0,
        'Z' => 3 + 3,
    ],
];

$roundsScores = array_map(
    fn($round) => $scoreAgainst[$round[0]][$round[2]],
    array_filter($rounds)
);

echo array_sum($roundsScores);
