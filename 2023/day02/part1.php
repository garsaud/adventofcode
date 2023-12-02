<?php

$input = stream_get_contents(STDIN);

$games = explode("\n", trim($input));

$possibleGames = array_filter($games, function ($game) {
    preg_match_all('/(((?<count>\d+) (?<color>\w+)+),?);?/', $game, $matches);
    foreach($matches['color'] as $index => $color) {
        if ($matches['count'][$index] > match ($color) {
            'red' => 12,
            'green' => 13,
            'blue' => 14,
        }) {
            return false;
        }
    }
    return true;
});

$sumOfIds = array_reduce($possibleGames, function ($carry, $game) {
    preg_match('/^Game (?<id>\d+)/', $game, $matches);
    return $carry + $matches['id'];
});

echo $sumOfIds;
