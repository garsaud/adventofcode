<?php

$total = 0;

while (($buffer = fgets(STDIN)) !== false) {
    preg_match('/(\d+)x(\d+)x(\d+)/', $buffer, $m);
    unset($m[0]);
    sort($m);
    $total += ($m[0]+$m[0]+$m[1]+$m[1])
        + $m[0]*$m[1]*$m[2];
}

echo $total;
