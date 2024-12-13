<?php

$input = trim(stream_get_contents(STDIN));
$claw_machines = explode("\n\n", $input);

echo array_reduce($claw_machines, function ($sum, $claw_machine) {
    preg_match(
        '/Button A: X\+(\d+), Y\+(\d+)\nButton B: X\+(\d+), Y\+(\d+)\nPrize: X=(\d+), Y=(\d+)/',
        $claw_machine,
        $matches
    );
    [, $a_x, $a_y, $b_x, $b_y, $target_x, $target_y] = $matches;

    $target_x += 10000000000000;
    $target_y += 10000000000000;

    $common_scale = (int)gmp_lcm($a_x, $a_y);
    
    $x_factor = $common_scale / $a_x;
    $y_factor = $common_scale / $a_y;
    
    // target_x = a * a_x + b_pushes * b_x
    // target_y = a * a_y + b_pushes * b_y
    // => b_pushes = (target_x - a * a_x) / b_x
    $b_pushes = ($x_factor*$target_x - $y_factor*$target_y) / ($x_factor*$b_x - $y_factor*$b_y);
    $a_pushes = ($target_x - $b_pushes * $b_x) / $a_x;
    
    if ($a_pushes == (int)$a_pushes) {
        $sum += ($a_pushes * 3) + $b_pushes;
    }
    return $sum;
});
