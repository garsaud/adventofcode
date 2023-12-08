<?php

$instructions = str_split(trim(fgets(STDIN)));
fgets(STDIN); // skip empty line

$steps = [];
while ($line = trim(fgets(STDIN))) {
    preg_match('/(?<S>\w+) = \((?<L>\w+), (?<R>\w+)\)/', $line, $matches);
    $steps[$matches['S']] = $matches;
}

for (
    $currentStep = array_key_first($steps), $i = 0, $j = 0;
    $currentStep != 'ZZZ';
    $j++, $i++, $i%=count($instructions)
) {
    $direction = $instructions[$i];
    $currentStep = $steps[$currentStep][$direction];
}

echo $j;
