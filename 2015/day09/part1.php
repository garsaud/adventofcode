<?php

$start = microtime(true);

$nodes = [];
while ($line = fgets(STDIN)) {
    //      0        1     2    3 4
    // AlphaCentauri to Snowdin = 66
    [$place1, , $place2, , $distance] = explode(' ', trim($line));
    $nodes[$place1][$place2] = $nodes[$place2][$place1] = (int)$distance;
}

function getPossibleRoutes($places, &$result, $perms = [])
{
    if (empty($places)) {
        $result[] = $perms;
        return;
    }
    foreach ($places as $i => $_) {
        $otherPlaces = $places;
        [$currentPlace] = array_splice($otherPlaces, $i, 1);
        getPossibleRoutes($otherPlaces, $result, [...$perms, $currentPlace]);
    }
}

getPossibleRoutes(array_keys($nodes), $possibleRoutes);
$result = PHP_INT_MAX;
foreach ($possibleRoutes as $route) {
    $distance = 0;
    for ($i = 0; $i < count($route) - 1; $i++) {
        $distance += $nodes[$route[$i]][$route[$i + 1]];
        if ($distance > $result) continue 2;
    }
    $result = $distance;
}

$end = microtime(true);

echo "Result : {$result}\n";
echo "Timing : ".($end-$start);
