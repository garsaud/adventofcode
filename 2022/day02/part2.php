<?php

$input = stream_get_contents(STDIN);

$rounds = explode("\n", $input);

$scoreAgainst = [
    'A' => [
        'X' => 3 + 0,
        'Y' => 1 + 3,
        'Z' => 2 + 6,
    ],
    'B' => [
        'X' => 1 + 0,
        'Y' => 2 + 3,
        'Z' => 3 + 6,
    ],
    'C' => [
        'X' => 2 + 0,
        'Y' => 3 + 3,
        'Z' => 1 + 6,
    ],
];

$roundsScores = array_map(
    fn($round) => $scoreAgainst[$round[0]][$round[2]],
    array_filter($rounds)
);

echo array_sum($roundsScores);
