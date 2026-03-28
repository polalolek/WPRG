zad 7 <?php

$oceny = [
    "Anna"    => [5, 4, null, 2, null, 3, 4, 5],
    "Bartek"  => [4, 5, 3, null, 2, 4, null, 4],
    "Celina"  => [5, 3, null, 3, null, 4, 5, null],
    "Dawid"   => [2, null, 4, 5, 3, null, 2, 3],
    "Ewa"     => [null, 4, 3, null, 5, 3, 4, 2],
    "Filip"   => [3, 5, 4, 2, null, 5, null, 4],
    "Grazzyna" => [5, null, 2, 4, 3, 2, 5, null],
];

$produkty = ["Laptop","Monitor","Klawiatura","Mysz","Sluchawki","Kamera","Tablet","Glosnik"];

function pearson($a, $b) {
    $wspolne = [];
    for ($i = 0; $i < count($a); $i++) {
        if ($a[$i] !== null && $b[$i] !== null) {
            $wspolne[] = $i;
        }
    }
    if (count($wspolne) < 2) return 0;

    $meanA = 0; $meanB = 0;
    foreach ($wspolne as $i) {
        $meanA += $a[$i];
        $meanB += $b[$i];
    }
    $n = count($wspolne);
    $meanA /= $n;
    $meanB /= $n;

    $num = 0; $denA = 0; $denB = 0;
    foreach ($wspolne as $i) {
        $diffA = $a[$i] - $meanA;
        $diffB = $b[$i] - $meanB;
        $num += $diffA * $diffB;
        $denA += $diffA ** 2;
        $denB += $diffB ** 2;
    }
    $den = sqrt($denA * $denB);
    return $den == 0 ? 0 : $num / $den;
}

$sim = [];
foreach ($oceny as $user => $r) {
    if ($user != "Anna") {
        $sim[$user] = pearson($oceny["Anna"], $r);
    }
}
arsort($sim);

echo "Podobienstwo Pearsona dla Anny:\n";
foreach ($sim as $user => $val) {
    printf("  %-7s %6.4f\n", $user, $val);
}

$k = 3;
$kNN = array_slice($sim, 0, $k, true);
echo "\nk=3 sasiedzi Anny: ";
$kNN_str = [];
foreach ($kNN as $user => $val) {
    $kNN_str[] = $user."(".number_format($val,4).")";
}
echo implode(", ", $kNN_str) . "\n";

$pred = [];
foreach ($produkty as $i => $prod) {
    if ($oceny["Anna"][$i] === null) {
        $num = 0; $den = 0;
        foreach ($kNN as $user => $val) {
            if ($oceny[$user][$i] !== null) {
                $num += $val * $oceny[$user][$i];
                $den += abs($val);
            }
        }
        if ($den != 0) {
            $pred[$prod] = $num / $den;
        }
    }
}
arsort($pred);

echo "\nRekomendacje dla Anny (produkty nieocenione):\n\n";
foreach ($pred as $prod => $val) {
    printf("%-12s — przewidywana ocena: %.2f\n", $prod, $val);
}

$oceny["Hania"] = [4,null,null,null,null,null,null,null];

$simHania = [];
foreach ($oceny as $user => $r) {
    if ($user != "Hania") {
        $simHania[$user] = pearson($oceny["Hania"], $r);
    }
}

$maxSim = max($simHania);

echo "\nZimny start (Hania, 1 ocena):\n";
echo "  Za malo wspolnych ocen z innymi uzytkownikami — brak wiarygodnych korelacji\n";
echo "  Strategia: rekomenduj najpopularniejsze produkty (najwyzsza srednia ocen wsrod wszystkich)\n";