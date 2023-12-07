<?php

$input = stream_get_contents(STDIN);

function getHandType ($cards) {
    if ($cards === str_repeat($cards[0], 5)) {
        return 1; // five
    }
    $cards = str_split($cards);
    sort($cards);
    $cards = implode($cards);
    if (preg_match('/(.)\1{3}/', $cards)) {
        return 2; // four
    }
    if (preg_match('/(.)\1{2}(.)\2{1}/', $cards) || preg_match('/(.)\1{1}(.)\2{2}/', $cards)) {
        return 3; // three and two
    }
    if (preg_match('/(.)\1{2}/', $cards)) {
        return 4; // three
    }
    if (preg_match('/(.)\1{1}.*(.)\2{1}/', $cards)) {
        return 5; // two and two
    }
    if (preg_match('/(.)\1{1}/', $cards)) {
        return 6; // two
    }
    return 9;
};

$hands = array_map(
    function ($line) {
        [$cards, $bid] = explode(' ', $line);
        // we rely on the ASCII alphabetical order
        $cmp = str_replace(
            ['J', '9', '8', '7', '6', '5', '4', '3', '2'],
            ['R', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'],
            $cards
        );
        return [$cards, $cmp, $bid, getHandType($cards)];
    },
    explode("\n", trim($input))
);

usort($hands, function ($a, $b) {
    [, $aCmp,, $aType] = $a;
    [, $bCmp,, $bType] = $b;
    return ($bType <=> $aType) ?: ($bCmp <=> $aCmp);
});

$totalWinnings = array_reduce(
    array_keys($hands),
    fn ($total, $i) => $total + $hands[$i][2] * ($i+1),
);

echo $totalWinnings;
