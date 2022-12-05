<?php

$input = stream_get_contents(STDIN);

[$inputStacks, $inputMoves] = explode("\n\n", $input);

$stacksLines = array_map('str_split', explode("\n", $inputStacks));
$stacksColumns = array_filter(
    // transpose lines into columns
    array_map(null, ...$stacksLines),
    // remove gaps and brackets
    fn($line) => preg_match('/\w/', implode($line))
);
$stacks = array_map(
    // remove empty blocks
    fn($column) => str_split(trim(implode($column))),
    array_values($stacksColumns)
);

$moves = array_filter(explode("\n", $inputMoves));
foreach ($moves as $line) {
    preg_match('/move (\d+) from (\d+) to (\d+)/', $line, $instructions);
    [, $amount, $from, $to] = $instructions;
    do {
        $box = array_shift($stacks[$from-1]);
        array_unshift($stacks[$to-1], $box);
    } while (--$amount);
}

echo implode(array_column($stacks, 0));
