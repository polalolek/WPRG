<?php

function mergeSort($arr, &$comparisons) {
    if (count($arr) <= 1) {
        return $arr;
    }

    $mid = (int)(count($arr) / 2);

    $left = array_slice($arr, 0, $mid);
    $right = array_slice($arr, $mid);

    $left = mergeSort($left, $comparisons);
    $right = mergeSort($right, $comparisons);

    return merge($left, $right, $comparisons);
}

function merge($left, $right, &$comparisons) {
    $result = [];
    $i = 0;
    $j = 0;

    while ($i < count($left) && $j < count($right)) {
        $comparisons++;

        if ($left[$i] <= $right[$j]) {
            $result[] = $left[$i];
            $i++;
        } else {
            $result[] = $right[$j];
            $j++;
        }
    }

    while ($i < count($left)) {
        $result[] = $left[$i];
        $i++;
    }

    while ($j < count($right)) {
        $result[] = $right[$j];
        $j++;
    }

    return $result;
}

$tablice = [
    [5, 3, 8, 1, 9, 2],
    [38, 27, 43, 3, 9, 82, 10, 15],
    [64, 25, 12, 22, 11, 90, 3, 47, 71, 38, 55, 8],
    [25, 24, 23, 22, 21, 20, 19, 18, 17, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1],
];

foreach ($tablice as $arr) {

    $comparisons = 0;
    $n = count($arr);

    $sorted = mergeSort($arr, $comparisons);

    $k = $comparisons / ($n * log($n, 2));

    echo "n=$n | Wejscie: [" . implode(", ", $arr) . "]\n";
    echo "     | Wyjscie: [" . implode(", ", $sorted) . "]\n";
    echo "     | Poownania: $comparisons | K: " . number_format($k, 3) . "\n\n";
}

$test = $tablice[0];
$comparisons = 0;

$mergeSorted = mergeSort($test, $comparisons);

$phpSorted = $test;
sort($phpSorted);

if ($mergeSorted === $phpSorted) {
    echo "Weryfikacja z sort() zgodna\n";
} else {
    echo "Weryfikacja z sort() niezgodna\n";
}