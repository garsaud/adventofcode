<?php

$input = trim(stream_get_contents(STDIN));

$motions = explode("\n", $input);

$head = $tail = [0, 0];
$tailVisited = [];

foreach ($motions as $motion) {
    [$direction, $steps] = explode(' ', $motion);
    $vector = match($direction) {
        'U' => [0, 1],
        'R' => [1, 0],
        'D' => [0, -1],
        'L' => [-1, 0],
    };
    while ($steps--) {
        $newHead = [$head[0]+$vector[0], $head[1]+$vector[1]];
        $distance = hypot($newHead[0]-$tail[0], $newHead[1]-$tail[1]);
        if ($distance >= 2) {
            $tail = $head;
        }
        $head = $newHead;
        $tailVisited[serialize($tail)] = true;
    }
}

echo count($tailVisited);
