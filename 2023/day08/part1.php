<?php

$instructions = str_split(trim(fgets(STDIN)));
fgets(STDIN); // skip empty line

$nodes = [];
while ($line = trim(fgets(STDIN))) {
    preg_match('/(?<S>\w+) = \((?<L>\w+), (?<R>\w+)\)/', $line, $matches);
    $nodes[$matches['S']] = $matches;
}

foreach ($nodes as &$node) {
    $node['L'] = &$nodes[$node['L']];
    $node['R'] = &$nodes[$node['R']];
}

for ($j = 0, $theNode = reset($nodes); $theNode['S'] !== 'ZZZ'; $j++) {
    $theNode = $theNode[$instructions[$j % count($instructions)]];
}

echo $j;
