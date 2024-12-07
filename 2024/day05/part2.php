<?php

$input = trim(stream_get_contents(STDIN));
[$rules, $updates] = explode("\n\n", $input);

$rules = array_map(fn($x) => explode('|', trim($x)), explode("\n", $rules));
$pages = array_map(fn($x) => explode(',', trim($x)), explode("\n", $updates));

$orders = [];
foreach ($rules as $rule) {
    [$left, $right] = $rule;
    $orders[$left]['after'][] = $right;
    $orders[$right]['before'][] = $left;
}

echo array_reduce($pages, function($sum, $page) use (&$orders) {
    foreach ($page as $i => $number) {
        $left_subset = array_slice($page, 0, $i);
        $right_subset = array_slice($page, $i + 1);

        if (
            // valid left
            ($i === 0 || empty(array_diff($left_subset, $orders[$number]['before']))) &&
            // valid right
            ($i === count($page) - 1 || empty(array_diff($right_subset, $orders[$number]['after'])))
        ) {
            continue;
        }

        usort($page, function($a, $b) use (&$orders) {
            if (in_array($a, $orders[$b]['before'])) return -1;
            if (in_array($a, $orders[$b]['after'])) return 1;
            if (in_array($b, $orders[$a]['before'])) return -1;
            if (in_array($b, $orders[$a]['after'])) return 1;
            return 0;
        });

        return $sum + $page[(int) floor((count($page) - 1) / 2)];
    }

    return $sum;
}, 0);
