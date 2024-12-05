<?php

$list1 = [];
$list2 = [];

while (($buffer = fgets(STDIN)) !== false) {
    [$line1, $line2] = sscanf($buffer, '%d   %d');
    $list1[] = $line1;
    $list2[] = $line2;
}

$occurrences_list2 = array_count_values($list2);

$score = array_map(
    fn($v) => $v * ($occurrences_list2[$v] ?? 0),
    $list1
);

echo array_sum($score);
