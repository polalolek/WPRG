<?php

$zadania = [
    ["id"=>1,  "nazwa"=>"T01", "start"=>480,  "koniec"=>600],
    ["id"=>2,  "nazwa"=>"T02", "start"=>510,  "koniec"=>720],
    ["id"=>3,  "nazwa"=>"T03", "start"=>540,  "koniec"=>660],
    ["id"=>4,  "nazwa"=>"T04", "start"=>600,  "koniec"=>690],
    ["id"=>5,  "nazwa"=>"T05", "start"=>660,  "koniec"=>780],
    ["id"=>6,  "nazwa"=>"T06", "start"=>690,  "koniec"=>840],
    ["id"=>7,  "nazwa"=>"T07", "start"=>720,  "koniec"=>810],
    ["id"=>8,  "nazwa"=>"T08", "start"=>780,  "koniec"=>900],
    ["id"=>9,  "nazwa"=>"T09", "start"=>840,  "koniec"=>960],
    ["id"=>10, "nazwa"=>"T10", "start"=>480,  "koniec"=>540],
    ["id"=>11, "nazwa"=>"T11", "start"=>570,  "koniec"=>630],
    ["id"=>12, "nazwa"=>"T12", "start"=>750,  "koniec"=>870],
    ["id"=>13, "nazwa"=>"T13", "start"=>900,  "koniec"=>990],
    ["id"=>14, "nazwa"=>"T14", "start"=>495,  "koniec"=>555],
    ["id"=>15, "nazwa"=>"T15", "start"=>870,  "koniec"=>930],
];

function minutyNaCzas(int $m): string {
    $h = intdiv($m, 60);
    $min = $m % 60;
    return sprintf("%d:%02d", $h, $min);
}

usort($zadania, fn($a,$b) => $a['koniec'] <=> $b['koniec']);

$wybrane = [];
$ostatniKoniec = -1;

foreach ($zadania as $z) {
    if ($z['start'] >= $ostatniKoniec) {
        $wybrane[] = $z;
        $ostatniKoniec = $z['koniec'];
    }
}

echo "Algorytm zachlanny (jedna sala):\n";
echo "  Wybrane zadania (" . count($wybrane) . "): " . implode(", ", array_column($wybrane, 'nazwa')) . "\n";
echo "  Kolejnosc decyzji: ";
$ciag = [];
foreach ($wybrane as $z) {
    $ciag[] = $z['nazwa'] . "(" . minutyNaCzas($z['start']) . "–" . minutyNaCzas($z['koniec']) . ")";
}
echo implode(" → ", $ciag) . "\n\n";

$kolizje = [];
foreach ($zadania as $i => $z1) {
    $c = 0;
    foreach ($zadania as $j => $z2) {
        if ($i == $j) continue;
        if (max($z1['start'], $z2['start']) < min($z1['koniec'], $z2['koniec'])) {
            $c++;
        }
    }
    $kolizje[$z1['nazwa']] = $c;
}

$maxKolizje = max($kolizje);
$mostConflict = array_keys($kolizje, $maxKolizje);

echo "Konflikty:\n";
echo "  Najbardziej konfliktowe: " . implode(", ", $mostConflict) . " ($maxKolizje kolizji z innymi zadaniami)\n\n";

usort($zadania, fn($a,$b) => $a['start'] <=> $b['start']);
$sale = [];

foreach ($zadania as $z) {
    $przypisano = false;
    foreach ($sale as $idx => $sala) {
        $ostatni = end($sala);
        if ($ostatni['koniec'] <= $z['start']) {
            $sale[$idx][] = $z;
            $przypisano = true;
            break;
        }
    }
    if (!$przypisano) {
        $sale[] = [$z];
    }
}

echo "Minimalna liczba sal: " . count($sale) . "\n";

foreach ($sale as $i => $sala) {
    echo "  Sala " . ($i+1) . ": ";
    $linie = [];
    foreach ($sala as $z) {
        $linie[] = $z['nazwa'] . "(" . minutyNaCzas($z['start']) . "–" . minutyNaCzas($z['koniec']) . ")";
    }
    echo implode(", ", $linie) . "\n";
}