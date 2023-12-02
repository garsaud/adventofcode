<?php

$input = stream_get_contents(STDIN);

$games = explode("\n", trim($input));

$powers = array_map(function ($game) {
    preg_match_all('/(((?<count>\d+) (?<color>\w+)+),?);?/', $game, $matches);

    $getBiggest = fn($color) => max(
        array_filter(
            $matches['count'],
            fn($index) => $matches['color'][$index] === $color,
            ARRAY_FILTER_USE_KEY
        )
    );

    return $getBiggest('red') * $getBiggest('green') * $getBiggest('blue');
}, $games);

echo array_sum(array_values($powers));
