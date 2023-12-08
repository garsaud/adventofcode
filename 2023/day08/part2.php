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

// By analyzing the input, we realize that each of the starting nodes make a loop,
// always going through the same nodes. We need to find the length of these loops
// and compare them with each other, calculate the Least Common Multiple, and it will
// will provide the number of steps where they will finally synchronize.

$theNodes = array_filter($nodes, fn ($k) => $k[2] === 'A', ARRAY_FILTER_USE_KEY);

$toZ = function ($node) use ($instructions) {
    for ($j = 0; $node['S'][2] !== 'Z'; $j++) {
        $node = $node[$instructions[$j % count($instructions)]];
    }
    return $j;
};

array_walk($theNodes, fn(&$node) => $node['stepsToZ'] = $toZ($node));

$stepsToZ = array_column($theNodes, 'stepsToZ');

echo array_reduce($stepsToZ, fn ($c, $stepToZ) => gmp_lcm($c, $stepToZ), $stepsToZ[0]);
