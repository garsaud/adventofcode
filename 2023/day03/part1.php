<?php

$input = stream_get_contents(STDIN);

$matrix = array_map(
    fn($line) => str_split($line),
    explode("\n", trim($input))
);

$lookupNumber = function ($x, $y) use (&$matrix) {
    if (!preg_match('/\d/', $matrix[$y][$x])) {
        // no adjacent number
        return 0;
    }

    $l = 1;
    $m = $matrix; // make a copy, because we will alter the original
    $matrix[$y][$x] = '.'; // clear from matrix

    // grow left
    do {
        $x--;
        $chunk = array_slice($m[$y], $x, $l); // resize chunk
        $matrix[$y][$x] = '.'; // clear from matrix
    } while (isset($m[$y][$x]) && preg_match('/\d/', $chunk[0]));
    
    // grow right
    do {
        $l++;
        $chunk = array_slice($m[$y], $x+1, $l); // resize chunk
        $matrix[$y][$x+$l] = '.'; // clear from matrix
    } while (isset($m[$y][$x+$l+1]) && preg_match('/\d/', end($chunk)));
    
    return intval(implode('', $chunk));
};

$total = 0;
foreach ($matrix as $y => $line) {
    foreach ($line as $x => $char) {
        if (!preg_match('/[^\d\.]/', $char)) {
            continue;
        }
        $total +=
            $lookupNumber($x-1,$y-1) + $lookupNumber($x,$y-1) + $lookupNumber($x+1,$y-1)
          + $lookupNumber($x-1,$y)                            + $lookupNumber($x+1,$y)
          + $lookupNumber($x-1,$y+1) + $lookupNumber($x,$y+1) + $lookupNumber($x+1,$y+1);
    }
}

echo $total;
