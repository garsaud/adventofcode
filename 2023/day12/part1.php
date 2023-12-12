<?php

$combinations = 0;

while (($line = trim(fgets(STDIN))) !== '') {
    [$springs, $scounts] = explode(' ', $line);
    $springs = strtr($springs, '.#', '01'); // replace with 0 and 1 so we can use a binary number
    if (!str_contains($springs, '?')) continue; // skip when no unknown

    $counts = explode(',', $scounts);
    $matchPattern = implode('0+', array_map(fn($c) => "1{{$c}}", $counts)); // build the spring search regex

    $unknownsCount = count_chars($springs,1)[ord('?')];
    for ($i = 0; $i < 2**$unknownsCount; $i++) {
        $filling = sprintf("%0{$unknownsCount}b", $i); // prepare the binary number
        $attempt = preg_replace(array_fill(0, $unknownsCount, '/\?/'), str_split($filling), $springs, 1); // fill question marks with the binary number
        if (preg_match("/^0*{$matchPattern}0*$/", $attempt)) { // check if it matches the requirements
            $combinations++;
        }
    }
}

echo $combinations;
