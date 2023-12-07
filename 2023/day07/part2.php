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
}

function generateCombinations($chars, $count) {
    $combinations = [];

    $recursiveGenerate = function ($currentCombination)use (&$recursiveGenerate, &$combinations, $chars, $count) {
        if (count($currentCombination) == $count) {
            $combinations[] = $currentCombination;
            return;
        }

        foreach ($chars as $digit) {
            $newCombination = array_merge($currentCombination, [$digit]);
            $recursiveGenerate($newCombination);
        }
    };

    $recursiveGenerate([]);

    return $combinations;
}

function compilePossibleCards($cards) {
    if (!str_contains($cards, 'J')) { // no joker
        return [$cards];
    }
    if (count(count_chars($cards, 1)) === 1) { // only jokers
        return [$cards];
    }
    // list all characters of a string other than "J".
    // for "abbJcbJa", we would obtain ['a','b','c'].
    $otherChars = str_split(count_chars(str_replace('J', '', $cards), 3));

    $untouched = preg_replace('/J/', '', $cards, -1, $count);
    $combinations = generateCombinations($otherChars, $count);

    return array_map(fn ($c) => $untouched.implode($c), $combinations);
}

$hands = array_map(
    function ($line) {
        [$cards, $bid] = explode(' ', $line);
        // we rely on the ASCII alphabetical order
        $cmp = str_replace(
            ['J', '9', '8', '7', '6', '5', '4', '3', '2'],
            ['z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'],
            $cards
        );
        $compiledCards = compilePossibleCards($cards);
        $strongestType = min(array_map('getHandType', $compiledCards));
        return [$cards, $cmp, $bid, $strongestType];
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
