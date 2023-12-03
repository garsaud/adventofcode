<?php

$total = 0;

while (($buffer = fgets(STDIN)) !== false) {
    preg_match('/(?<l>\d+)x(?<w>\d+)x(?<h>\d+)/', $buffer, $m);
    $total += 2*$m['l']*$m['w'] + 2*$m['w']*$m['h'] + 2*$m['h']*$m['l']
        + min($m['l']*$m['w'], $m['w']*$m['h'], $m['h']*$m['l']);
}

echo $total;
