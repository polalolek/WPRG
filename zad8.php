<?php

$rekordy = [
    ["id"=>1,  "imie"=>"anna",    "wiek"=>"25",  "email"=>"anna@test.com",   "wynik"=>92.5],
    ["id"=>2,  "imie"=>"Bartosz", "wiek"=>"abc", "email"=>"bartosz@test.com","wynik"=>78.0],
    ["id"=>3,  "imie"=>"celina",  "wiek"=>"31",  "email"=>"celina@test.com", "wynik"=>105.0],
    ["id"=>4,  "imie"=>"Dawid",   "wiek"=>"45",  "email"=>"",               "wynik"=>66.5],
    ["id"=>5,  "imie"=>"EWA",     "wiek"=>"28",  "email"=>"ewa@test.com",    "wynik"=>88.0],
    ["id"=>6,  "imie"=>"filip",   "wiek"=>"130", "email"=>"filip@test.com",  "wynik"=>74.0],
    ["id"=>7,  "imie"=>"Grażyna", "wiek"=>"52",  "email"=>"anna@test.com",   "wynik"=>91.0],
    ["id"=>8,  "imie"=>"Henryk",  "wiek"=>"19",  "email"=>"henryk@test.com", "wynik"=>-5.0],
    ["id"=>9,  "imie"=>"irena",   "wiek"=>"37",  "email"=>"irena@test.com",  "wynik"=>83.5],
    ["id"=>10, "imie"=>"JANEK",   "wiek"=>"22",  "email"=>"janek@test.com",  "wynik"=>55.0],
    ["id"=>11, "imie"=>"Kasia",   "wiek"=>"29",  "email"=>"kasia@test.com",  "wynik"=>97.0],
    ["id"=>12, "imie"=>"Leon",    "wiek"=>"41",  "email"=>"leon@test.com",   "wynik"=>62.0],
    ["id"=>13, "imie"=>"Marta",   "wiek"=>"0",   "email"=>"marta@test.com",  "wynik"=>79.5],
    ["id"=>14, "imie"=>"norbert", "wiek"=>"33",  "email"=>"norbert@test.com","wynik"=>86.0],
    ["id"=>15, "imie"=>"Ola",     "wiek"=>"26",  "email"=>"ola@test.com",    "wynik"=>91.0],
];

function waliduj(array $dane): array {
    $valid = [];
    $rejected = [];
    $emails = [];
    foreach ($dane as $r) {
        $powody = [];

        if (!ctype_digit(strval($r['wiek'])) || (int)$r['wiek'] < 1 || (int)$r['wiek'] > 120) {
            $powody[] = "nieprawidlowy wiek '{$r['wiek']}'";
        }

        if (!is_numeric($r['wynik']) || $r['wynik'] < 0.0 || $r['wynik'] > 100.0) {
            $powody[] = "wynik poza zakresem [0–100]: {$r['wynik']}";
        }

        if (trim($r['email']) === '') {
            $powody[] = "pusty email";
        }

        $emailLower = strtolower(trim($r['email']));
        if ($emailLower !== '' && isset($emails[$emailLower])) {
            $powody[] = "duplikat email '{$r['email']}'";
        }

        if (empty($powody)) {
            $valid[] = $r;
            if ($emailLower !== '') $emails[$emailLower] = true;
        } else {
            $rejected[] = ['rekord'=>$r, 'powody'=>$powody];
        }
    }
    return ['valid'=>$valid, 'rejected'=>$rejected];
}

function transformuj(array $dane): array {
    $seen = [];
    $out = [];
    foreach ($dane as $r) {
        $emailLower = strtolower(trim($r['email']));
        if (!isset($seen[$emailLower])) {
            $r['imie'] = ucfirst(strtolower($r['imie']));
            $r['wiek'] = (int)$r['wiek'];
            $r['wynik'] = (float)$r['wynik'];
            $out[] = $r;
            $seen[$emailLower] = true;
        }
    }
    return $out;
}

function przypiszOcene(float $wynik): string {
    if ($wynik >= 90) return 'A';
    if ($wynik >= 75) return 'B';
    if ($wynik >= 60) return 'C';
    return 'D';
}

$etapE = waliduj($rekordy);
$valid = transformuj($etapE['valid']);
$rejected = $etapE['rejected'];

echo "=== Etap E: Walidacja ===\n";
echo "Odrzucone rekordy (" . count($rejected) . "):\n";
foreach ($rejected as $rej) {
    $r = $rej['rekord'];
    $powody = implode("; ", $rej['powody']);
    printf("  - ID %-2d (%-8s): %s\n", $r['id'], $r['imie'], $powody);
}

echo "\n=== Etap L: Finalna baza (" . count($valid) . " rekordow) ===\n";
printf("%-12s | %-4s | %-25s | %-5s | %-5s\n","Imia","Wiek","Email","Wynik","Ocena");
echo str_repeat("-",65)."\n";

$rozklad = ['A'=>[], 'B'=>[], 'C'=>[], 'D'=>[]];

foreach ($valid as $r) {
    $ocena = przypiszOcene($r['wynik']);
    printf("%-12s | %4d | %-25s | %5.1f | %-5s\n", $r['imie'],$r['wiek'],$r['email'],$r['wynik'],$ocena);
    $rozklad[$ocena][] = $r['wynik'];
}

echo "\nRozklad ocen:\n";
foreach ($rozklad as $ocena => $wyniki) {
    if (count($wyniki) > 0) {
        $srednia = array_sum($wyniki)/count($wyniki);
        printf("  %s: %d studentow, srednia: %.1f%%\n",$ocena,count($wyniki),$srednia);
    }
}