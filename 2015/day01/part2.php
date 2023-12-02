<?php

for ($position = $i = 0; $position >= 0; $i++) { 
    $instruction = fgetc(STDIN);
    $position += match ($instruction) {
        '(' => +1,
        ')' => -1,
    };
}

echo $i;
