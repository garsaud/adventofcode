<?php

$time = preg_replace('/[^\d]/', '', fgets(STDIN));
$recordedDistance = preg_replace('/[^\d]/', '', fgets(STDIN));

$findDistance = fn ($time, $wait) => $wait * ($time - $wait);
$findPossiblePauses = fn ($time) => range(1, $time-1);

$betterPauses = array_filter(
    $findPossiblePauses($time),
    fn ($wait) => $findDistance($time, $wait) >= $recordedDistance
);

echo count($betterPauses);
