<?php

$input = stream_get_contents(STDIN);

$matrix = [];
$start = null;
foreach (explode("\n", trim($input)) as $y => $line) {
    $matrix[$y] = [];
    foreach (str_split($line) as $x => $char) {
        $matrix[$y][$x] = [
            'x' => $x,
            'y' => $y,
            'shape' => $char,
        ];
        if ($char === 'S') {
            $start = $matrix[$y][$x];
        }
    }
}

class StartingPointFound extends Exception {}
class NotAPipe extends Exception {}

$getEnds = function ($pipe) use (&$matrix) {
    [$v1, $v2] = match ($pipe['shape']) {
        //      [x,y], [x,y]
        '|' => [[0,1], [0,-1]],
        '-' => [[-1,0], [1,0]],
        'L' => [[0,-1], [1,0]],
        'J' => [[0,-1], [-1,0]],
        '7' => [[-1,0], [0,1]],
        'F' => [[1,0], [0,1]],
        '.' => throw new NotAPipe,
        'S' => throw new StartingPointFound,
    };
    $end1 = &$matrix[$pipe['y'] + $v1[1]][$pipe['x'] + $v1[0]] ?? null;
    $end2 = &$matrix[$pipe['y'] + $v2[1]][$pipe['x'] + $v2[0]] ?? null;
    return [$end1, $end2];
};

$exploreNextPipe = function ($currentPipe, $previousPipe) use ($getEnds, &$matrix) {
    $newPipes = $getEnds($currentPipe);
    return $newPipes[0] !== $previousPipe
        ? $newPipes[0]
        : $newPipes[1];
};

$total = 0;
$possiblePipes = [
    $matrix[$start['y']-1][$start['x']],
    $matrix[$start['y']][$start['x']+1],
    $matrix[$start['y']+1][$start['x']],
    $matrix[$start['y']][$start['x']-1],
];
$previousPipe = &$start;
$currentPipe = null;
foreach ($possiblePipes as $pipe) { // find where to start
    try {
        $ends = $getEnds($pipe);
    } catch (NotAPipe) {
        continue;
    }
    if (empty($ends[0]) || empty($ends[1])) {
        continue;
    }
    if (in_array($start, $ends)) {
        $currentPipe = &$pipe;
        break;
    }
}
for ($distance = 0;; $distance++) { // walk through next pipe
    try {
        $newPipe = $exploreNextPipe($currentPipe, $previousPipe);
    } catch (StartingPointFound) {
        break;
    }
    $previousPipe = $currentPipe;
    $currentPipe = $newPipe;
}

echo (int)round($distance/2);
