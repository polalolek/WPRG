<?php

function s_push(array &$stos, $val): void {
    array_splice($stos, count($stos), 0, [$val]);
}

function s_pop(array &$stos) {
    if (count($stos) == 0) {
        throw new Exception("pusty stos");
    }
    $top = $stos[count($stos) - 1];
    array_splice($stos, -1, 1);
    return $top;
}

function s_peek(array $stos) {
    if (count($stos) == 0) {
        throw new Exception("psty stos");
    }
    return $stos[count($stos) - 1];
}

function sprawdz_nawiasy(string $napis): bool {
    $stos = [];
    $otwarte = ['(' => ')', '[' => ']', '{' => '}'];
    $zamkniete = [')', ']', '}'];

    for ($i = 0; $i < strlen($napis); $i++) {
        $ch = $napis[$i];
        if (isset($otwarte[$ch])) {
            s_push($stos, $ch);
        } elseif (in_array($ch, $zamkniete)) {
            if (count($stos) == 0) return false;
            $top = s_pop($stos);
            if ($otwarte[$top] != $ch) return false;
        }
    }
    return count($stos) == 0;
}

function oblicz_ONP(string $wyrazenie): float {
    $stos = [];
    $tokens = explode(' ', trim($wyrazenie));

    foreach ($tokens as $token) {
        if ($token === '') continue;
        if (is_numeric($token)) {
            s_push($stos, (float)$token);
        } else { // operator
            $b = s_pop($stos);
            $a = s_pop($stos);
            switch ($token) {
                case '+': s_push($stos, $a + $b); break;
                case '-': s_push($stos, $a - $b); break;
                case '*': s_push($stos, $a * $b); break;
                case '/': s_push($stos, $a / $b); break;
                default: throw new Exception("Nieznany operator $token");
            }
        }
    }

    return s_pop($stos);
}

$wyrazenia_ONP = [
    "5 2 + 3 *",         
    "15 7 1 1 + - / 3 * 2 1 1 + + -",
    "4 13 5 / +",
    "2 3 + 4 5 -",
    "100 50 25 / -",
];


$napisy_nawiasy = [
    "[({()})]",
    "((())",
    "{[()]}",
    "([)]",
    "",
];

$bufor = array_fill(0, 5, 0);
$pos = 0;

for ($i = 0; $i < count($wyrazenia_ONP); $i++) {
    $nawiasy = $napisy_nawiasy[$i];
    $onp = $wyrazenia_ONP[$i];

    $status = sprawdz_nawiasy($nawiasy) ? "OK" : "BŁĄD";

    $wynik = oblicz_ONP($onp);

    $bufor[$pos % 5] = $wynik;
    $pos++;

    printf("[%d] Nawiasy \"%s\": %-5s | ONP \"%s\" = %s\n", 
        $i+1, $nawiasy, $status, $onp, rtrim($wynik, '.0'));
}

echo "\nBufor cykliczny (ostatnie 5 wynikow): [".implode(", ", $bufor)."]\n";

?>