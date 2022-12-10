<?php

$input = trim(stream_get_contents(STDIN));

$motions = explode("\n", $input);

$head = [0, 0];
$tails = array_fill(1, 9, [0, 0]);
$tail9Visited = [];

function follow(&$tail, $head) {
    $distance = hypot($head[0]-$tail[0], $head[1]-$tail[1]);
    if ($distance >= 2) {
        $tail = [$tail[0] + ($head[0]<=>$tail[0]), $tail[1] + ($head[1]<=>$tail[1])];
    }
}

foreach ($motions as $a => $motion) {
    [$direction, $steps] = explode(' ', $motion);
    $vector = match($direction) {
        'U' => [0, 1],
        'R' => [1, 0],
        'D' => [0, -1],
        'L' => [-1, 0],
    };
    while ($steps--) {
        $newHead = [$head[0]+$vector[0], $head[1]+$vector[1]];
        foreach ($tails as $i => &$tail) {
            follow($tail, $tails[$i-1] ?? $newHead);
        }
        $head = $newHead;
        $tail9Visited[serialize($tails[9])] = true;
    }
}

echo count($tail9Visited);
