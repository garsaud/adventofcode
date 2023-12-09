<?php

$input = stream_get_contents(STDIN);

$lines = array_map(
    fn($line) => explode(' ', trim($line)),
    explode("\n", trim($input))
);

$subtract = function ($numbers) {
    $result = $numbers;
    array_shift($result);
    foreach ($result as $i => &$r) {
        $r -= $numbers[$i];
    }
    return $result;
};

$expandEnd = function ($numbersUp, $numbersDown) {
    $numbersUp[] = end($numbersUp) + end($numbersDown);
    return $numbersUp;
};

$sum = 0;
foreach ($lines as $numbers) {
    $currentLevel = $numbers;
    $levels = [$currentLevel];

    // dig down until it’s all zeroes
    do {
        $currentLevel = $subtract($currentLevel);
        $levels[] = $currentLevel;
    } while ($currentLevel !== array_fill(0, count($currentLevel), 0));

    // expand right part from bottom to top
    foreach (array_reverse($levels, true) as $i => $level) {
        if (!$i) {
            break;
        }
        $levels[$i-1] = $expandEnd($levels[$i-1], $levels[$i]);
    }

    $sum += end($levels[0]);
}

echo $sum;
