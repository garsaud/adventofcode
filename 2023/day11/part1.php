<?php

$input = stream_get_contents(STDIN);
$input = strtr($input, '.#', '01'); // using zero/one makes it easier to filter

$matrix = [];
$galaxies = [];
$emptyrows = [];
$emptycolumns = [];
foreach (explode("\n", trim($input)) as $y => $line) {
    $matrix[$y] = [];
    foreach (str_split($line) as $x => $char) {
        $matrix[$y][$x] = [
            'x' => $x,
            'y' => $y,
            'char' => $char,
        ];
        if ($char) {
            $galaxies[] = &$matrix[$y][$x];
        }
    }
    if (empty(array_filter(array_column($matrix[$y], 'char')))) {
        $emptyrows[$y] = true;
    }
}
foreach ($matrix[0] as $x => $_) {
    if (empty(array_filter($galaxies, fn($g) => $g['x'] === $x))) {
        $emptycolumns[$x] = true;
    }
}

$getDistance = function ($a, $b) use (&$emptycolumns, &$emptyrows) {
    $distance = abs($b['x']-$a['x']) + abs($b['y']-$a['y']);
    // add some distance each time an empty row/column is in between the two points
    foreach($emptyrows as $y => $_) {
        if (in_array($y, range($a['y'], $b['y']))) $distance++;
    }
    foreach($emptycolumns as $x => $_) {
        if (in_array($x, range($a['x'], $b['x']))) $distance++;
    }
    return $distance;
};

$distances = [];
foreach ($galaxies as $i => $a) {
    foreach ($galaxies as $j => $b) {
        if ($a === $b) continue;
        $key = [$i, $j]; sort($key);
        $key = implode(',', $key); // concatenate galaxy ids for unicity
        if (isset($distances[$key])) continue;
        $distances[$key] = $getDistance($a, $b);
    }
}

echo array_sum($distances);
