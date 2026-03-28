<?php

function sito(int $n): array {

    $A = array_fill(0, $n + 1, true);
    $A[0] = false;
    $A[1] = false;

    for ($i = 2; $i * $i <= $n; $i++) {
        if ($A[$i]) {
            for ($j = $i * $i; $j <= $n; $j += $i) {
                $A[$j] = false;
            }
        }
    }

    $primes = [];

    for ($i = 2; $i <= $n; $i++) {
        if ($A[$i]) {
            $primes[] = $i;
        }
    }

    return $primes;
}


$primes = sito(500);


echo "liczby pierwsze [1–100] (bloki po 10):\n";

$primes100 = array_filter($primes, fn($p) => $p <= 100);

$chunks = array_chunk($primes100, 10);

foreach ($chunks as $chunk) {
    echo "[" . implode(", ", $chunk) . "]\n";
}


echo "\ngestosc liczb pierwszych:\n";

$intervals = [
    [1,100],
    [101,200],
    [201,300],
    [301,400],
    [401,500]
];

foreach ($intervals as $interval) {

    $a = $interval[0];
    $b = $interval[1];

    $count = 0;

    foreach ($primes as $p) {
        if ($p >= $a && $p <= $b) {
            $count++;
        }
    }

    $middle = ($a + $b) / 2;
    $theoretical = ($b - $a) / log($middle);

    printf(
        "przedzial [%d–%d]: %d (teoretycznie: ~%.1f)\n",
        $a, $b, $count, $theoretical
    );
}


$primeSet = array_flip($primes);

$maxPairs = 0;
$numberWithMax = 0;

$pairsFor30 = [];

for ($n = 4; $n <= 200; $n += 2) {

    $pairs = [];

    foreach ($primes as $p) {

        if ($p > $n / 2) {
            break;
        }

        $q = $n - $p;

        if (isset($primeSet[$q])) {
            $pairs[] = [$p, $q];
        }
    }

    if ($n == 30) {
        $pairsFor30 = $pairs;
    }

    if (count($pairs) > $maxPairs) {
        $maxPairs = count($pairs);
        $numberWithMax = $n;
    }
}

echo "\ngoldbach — najwicej par w [4,200]: Liczba $numberWithMax ($maxPairs par)\n";

echo "pary Goldbacha dla 30: ";

foreach ($pairsFor30 as $pair) {
    echo "[" . $pair[0] . "+" . $pair[1] . "] ";
}

echo "\n";