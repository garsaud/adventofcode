<?php

$list1 = [];
$list2 = [];

while (($buffer = fgets(STDIN)) !== false) {
    [$line1, $line2] = sscanf($buffer, '%d   %d');
    $list1[] = $line1;
    $list2[] = $line2;
}

sort($list1);
sort($list2);

$numbers = array_map(
    fn($i) => abs($list1[$i] - $list2[$i]),
    array_keys($list1)
);

echo array_sum($numbers);
