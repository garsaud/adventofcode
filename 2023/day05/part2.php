<?php

preg_match_all('/(?<from>\d+) (?<length>\d+)/', fgets(STDIN), $seedRanges); // first line
fgets(STDIN); // skip empty line

$ranges = [];
foreach ($seedRanges[0] as $i => $_) {
    $ranges[] = [
        'from' => $seedRanges['from'][$i],
        'to' => $seedRanges['from'][$i] + $seedRanges['length'][$i] - 1,
    ];
}

$mappings = [];
while (($line = fgets(STDIN)) !== false) {
    $mappings[$line] = [];
    while (($subline = trim(fgets(STDIN))) !== '') {
        preg_match('/(?<dest>\d+) (?<source>\d+) (?<length>\d+)/', $subline, $m);
        $mappings[$line][] = [
            'sourceStart' => $m['source'],
            'sourceEnd' => $m['source'] + $m['length']-1,
            'destStart' => $m['dest'],
            'destEnd' => $m['dest'] + $m['length']-1,
            'delta' => $m['dest'] - $m['source'],
        ];
    }
}

$convertRange = function ($range, $mapping) {
    $resultRanges = [];

    foreach ($mapping as $m) {
        if ($range['to'] < $m['sourceStart'] || $range['from'] > $m['sourceEnd']) {
            continue; // no overlap found
        }
        // record overlapping part
        $mappedFrom = max($range['from'], $m['sourceStart']);
        $mappedTo = min($range['to'], $m['sourceEnd']);

        $resultRanges[] = [
            'from' => $mappedFrom + $m['delta'],
            'to' => $mappedTo + $m['delta'],
        ];
        // record non-overlapping parts if any
        if ($range['from'] < $m['sourceStart']) {
            $resultRanges[] = [
                'from' => $range['from'],
                'to' => $m['sourceStart'] - 1,
            ];
        }
        if ($range['to'] > $m['sourceEnd']) {
            $resultRanges[] = [
                'from' => $m['sourceEnd'] + 1,
                'to' => $range['to'],
            ];
        }
    }
    if (empty($resultRanges)) {
        $resultRanges[] = $range; // no match found: keep as is
    }
    return $resultRanges;
};

// pass all ranges through mappings
foreach ($mappings as $map) {
    $newRanges = [];
    foreach ($ranges as $range) {
        $convertedRanges = $convertRange($range, $map);
        $newRanges = array_merge($newRanges, $convertedRanges);
    }
    $ranges = $newRanges;
}

$lowestLocation = PHP_INT_MAX;
foreach ($ranges as $range) {
    $lowestLocation = min($lowestLocation, $range['from'], $range['to']);
}

echo $lowestLocation;
