<?php

$input = stream_get_contents(STDIN);

$cards = explode("\n", trim($input));

$total = array_reduce($cards, function ($total, $card) {
    [, $attempts, $winners] = preg_split("/[\:\|]/", $card);

    preg_match_all('/\d+/', $attempts, $attempts);
    preg_match_all('/\d+/', $winners, $winners);

    $inCommon = array_intersect($attempts[0], $winners[0]);
    if (empty($inCommon)) {
        return $total;
    }

    return $total + 2 ** (count($inCommon) - 1);
});

echo $total;
