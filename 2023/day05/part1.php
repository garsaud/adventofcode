<?php

preg_match_all('/(\d+)/', fgets(STDIN), $seeds); // first line
fgets(STDIN); // skip empty line

$mappings = [];
while (($line = fgets(STDIN)) !== false) {
    $mappings[$line] = [];
    while (($subline = trim(fgets(STDIN))) !== '') {
        preg_match('/(?<destination>\d+) (?<source>\d+) (?<length>\d+)/', $subline, $m);
        $mappings[$line][] = $m;
    }
}

$locations = [];
foreach ($seeds[0] as $id) {
    foreach ($mappings as $mapping) {
        foreach ($mapping as $m) {
            if ($id >= $m['source'] && $id < $m['source'] + $m['length']) {
                $id = $id - $m['source'] + $m['destination'];
                break;
            }
        }
    }
    $locations[] = $id;
}

echo min($locations);
