<?php

$transakcje = [
    ["id"=>1,  "data"=>"2024-01-15","kategoria"=>"Elektronika","kwota"=>1200.00],
    ["id"=>2,  "data"=>"2024-01-22","kategoria"=>"Dom",        "kwota"=>350.00],
    ["id"=>3,  "data"=>"2024-02-03","kategoria"=>"Elektronika","kwota"=>800.00],
    ["id"=>4,  "data"=>"2024-02-14","kategoria"=>"Odzież",     "kwota"=>250.00],
    ["id"=>5,  "data"=>"2024-02-28","kategoria"=>"Dom",        "kwota"=>420.00],
    ["id"=>6,  "data"=>"2024-03-05","kategoria"=>"Elektronika","kwota"=>1500.00],
    ["id"=>7,  "data"=>"2024-03-12","kategoria"=>"Odziez",     "kwota"=>180.00],
    ["id"=>8,  "data"=>"2024-03-19","kategoria"=>"Dom",        "kwota"=>290.00],
    ["id"=>9,  "data"=>"2024-01-08","kategoria"=>"Odziez",     "kwota"=>310.00],
    ["id"=>10, "data"=>"2024-01-30","kategoria"=>"Elektronika","kwota"=>950.00],
    ["id"=>11, "data"=>"2024-02-10","kategoria"=>"Dom",        "kwota"=>600.00],
    ["id"=>12, "data"=>"2024-03-25","kategoria"=>"Odziez",     "kwota"=>430.00],
    ["id"=>13, "data"=>"2024-01-18","kategoria"=>"Elektronika","kwota"=>2100.00],
    ["id"=>14, "data"=>"2024-02-22","kategoria"=>"Dom",        "kwota"=>175.00],
    ["id"=>15, "data"=>"2024-03-08","kategoria"=>"Elektronika","kwota"=>670.00],
    ["id"=>16, "data"=>"2024-01-25","kategoria"=>"Odziez",     "kwota"=>520.00],
    ["id"=>17, "data"=>"2024-02-17","kategoria"=>"Elektronika","kwota"=>1350.00],
    ["id"=>18, "data"=>"2024-03-14","kategoria"=>"Dom",        "kwota"=>480.00],
    ["id"=>19, "data"=>"2024-01-12","kategoria"=>"Dom",        "kwota"=>230.00],
    ["id"=>20, "data"=>"2024-02-05","kategoria"=>"Odziez",     "kwota"=>390.00],
];

$pivot = [];
$kwoty_kategorie = []; 

$months = ['2024-01'=>'Styczen', '2024-02'=>'Luty', '2024-03'=>'Marzec'];

foreach($transakcje as $t){
    $miesiac = substr($t['data'],0,7);
    $kat = $t['kategoria'];
    $kw = $t['kwota'];

    if(!isset($pivot[$kat])) $pivot[$kat] = [];
    if(!isset($pivot[$kat][$miesiac])) $pivot[$kat][$miesiac] = 0;
    $pivot[$kat][$miesiac] += $kw;

    if(!isset($kwoty_kategorie[$kat])) $kwoty_kategorie[$kat] = [];
    $kwoty_kategorie[$kat][] = $kw;
}

function sigma($arr){
    $n = count($arr);
    if($n == 0) return 0;
    $avg = array_sum($arr)/$n;
    $s = 0;
    foreach($arr as $x){
        $s += pow($x-$avg,2);
    }
    return sqrt($s/$n);
}

printf("%-14s | %8s | %8s | %8s\n","Kategoria","Styczen","Luty","Marzec");
echo str_repeat("-",45)."\n";

foreach($pivot as $kat=>$miesiace){
    printf("%-14s | %8.2f | %8.2f | %8.2f\n",
        $kat,
        $miesiace['2024-01'] ?? 0,
        $miesiace['2024-02'] ?? 0,
        $miesiace['2024-03'] ?? 0
    );
}

echo "\nOdchylenia standardowe (\xCF\x83):\n";

$max_sigma = 0;
$kat_max = "";
foreach($kwoty_kategorie as $kat=>$kwoty){
    $n = count($kwoty);
    $avg = array_sum($kwoty)/$n;
    $s = sigma($kwoty);
    printf("  %-12s: σ=%.2f (n=%d, avg=%.2f z%c)\n",$kat,$s,$n,$avg,0x82);
    if($s>$max_sigma){
        $max_sigma = $s;
        $kat_max = $kat;
    }
}

echo "\nKategoria o najwiekszej zmiennosci: $kat_max (σ=".number_format($max_sigma,2).")\n";

?>