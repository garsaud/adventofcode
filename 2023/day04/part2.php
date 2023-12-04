<?php

$input = stream_get_contents(STDIN);

$lines = explode("\n", trim($input));

$cards = array_fill(0, count($lines), ['copies' => 1]);

foreach ($lines as $i => $line) {
    [, $attempts, $winners] = preg_split("/[\:\|]/", $line);

    preg_match_all('/\d+/', $attempts, $attempts);
    preg_match_all('/\d+/', $winners, $winners);

    $inCommon = array_values(array_intersect($attempts[0], $winners[0]));
    foreach ($inCommon as $j => $_) {
        $cards[$i+$j+1]['copies'] += $cards[$i]['copies'];
    }
}

echo array_sum(array_column($cards, 'copies'));
