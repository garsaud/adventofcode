<?php

preg_match_all('/\d+/', fgets(STDIN), $times);
preg_match_all('/\d+/', fgets(STDIN), $distances);
$races = array_combine($times[0], $distances[0]);

$findDistance = fn ($time, $wait) => $wait * ($time - $wait);
$findPossiblePauses = fn ($time) => range(1, $time-1);

$marginOfError = 1;
foreach ($races as $time => $recordedDistance) {
    $betterPauses = array_filter(
        $findPossiblePauses($time),
        fn ($wait) => $findDistance($time, $wait) >= $recordedDistance
    );
    $marginOfError *= count($betterPauses);
}

echo $marginOfError;
