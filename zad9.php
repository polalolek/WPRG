<?php

$dane = [];
$historia = [];

function print_array($arr) {
    echo "[" . implode(", ", $arr) . "]\n";
}

echo "Mini-REPL: wpisz 'help' aby zobaczyc dostępne polecenia.\n";

while (true) {
    $linia = readline(">> ");
    if ($linia === false || trim($linia) === '') continue;

    $czesci = explode(' ', trim($linia), 3);
    $polecenie = strtolower($czesci[0]);

    switch ($polecenie) {

        case 'push':
            if (!isset($czesci[1])) { echo "Brak argumentu dla push\n"; break; }
            $dane[] = $czesci[1];
            print_array($dane);
            $historia[] = $linia;
            break;

        case 'pop':
            if (count($dane) === 0) { echo "Tablica pusta\n"; break; }
            $val = array_pop($dane);
            echo $val."\n";
            print_array($dane);
            $historia[] = $linia;
            break;

        case 'insert':
            if (!isset($czesci[1]) || !isset($czesci[2])) { echo "Brak argumentu dla insert\n"; break; }
            $idx = (int)$czesci[1];
            array_splice($dane, $idx, 0, [$czesci[2]]);
            print_array($dane);
            $historia[] = $linia;
            break;

        case 'delete':
            if (!isset($czesci[1])) { echo "Brak argumentu dla delete\n"; break; }
            $idx = (int)$czesci[1];
            if (isset($dane[$idx])) { array_splice($dane, $idx,1); }
            print_array($dane);
            $historia[] = $linia;
            break;

        case 'sort':
            sort($dane);
            print_array($dane);
            $historia[] = $linia;
            break;

        case 'rsort':
            rsort($dane);
            print_array($dane);
            $historia[] = $linia;
            break;

        case 'filter':
            if (!isset($czesci[1]) || !isset($czesci[2])) { echo "Brak argumentu dla filter\n"; break; }
            $op = $czesci[1];
            $val = $czesci[2];
            $dane = array_values(array_filter($dane, function($x) use ($op,$val){
                switch($op){
                    case '>': return $x > $val;
                    case '<': return $x < $val;
                    case '>=': return $x >= $val;
                    case '<=': return $x <= $val;
                    case '==': return $x == $val;
                    default: return false;
                }
            }));
            print_array($dane);
            $historia[] = $linia;
            break;

        case 'unique':
            $dane = array_values(array_unique($dane));
            print_array($dane);
            $historia[] = $linia;
            break;

        case 'reverse':
            $dane = array_reverse($dane);
            print_array($dane);
            $historia[] = $linia;
            break;

        case 'chunk':
            if (!isset($czesci[1])) { echo "Brak argumentu dla chunk\n"; break; }
            $n = (int)$czesci[1];
            $chunks = array_chunk($dane, $n);
            foreach ($chunks as $i=>$c) {
                echo "Chunk ".($i+1).": [".implode(", ", $c)."]\n";
            }
            $historia[] = $linia;
            break;

        case 'slice':
            if (!isset($czesci[1]) || !isset($czesci[2])) { echo "Brak argumentu dla slice\n"; break; }
            $od = (int)$czesci[1];
            $ile = (int)$czesci[2];
            $sl = array_slice($dane, $od, $ile);
            print_array($sl);
            $historia[] = $linia;
            break;

        case 'stats':
            if (count($dane) === 0) { echo "Tablica pusta\n"; break; }
            $sum=0; $min=$dane[0]; $max=$dane[0];
            foreach ($dane as $v) {
                $sum+=$v;
                if ($v<$min) $min=$v;
                if ($v>$max) $max=$v;
            }
            $avg = $sum/count($dane);
            echo "Suma: $sum | Średnia: $avg | Min: $min | Max: $max\n";
            $historia[] = $linia;
            break;

        case 'show':
            print_array($dane);
            $historia[] = $linia;
            break;

        case 'reset':
            $dane = [];
            print_array($dane);
            $historia[] = $linia;
            break;

        case 'save':
            echo json_encode(['dane'=>$dane])."\n";
            $historia[] = $linia;
            break;

        case 'history':
            $last = array_slice($historia,-10);
            $i=1;
            foreach($last as $cmd) { echo "$i: $cmd\n"; $i++; }
            break;

        case 'help':
            echo "Dostępne polecenia:\n";
            echo "push <v>, pop, insert <idx> <v>, delete <idx>, sort, rsort, filter <op> <v>\n";
            echo "unique, reverse, chunk <n>, slice <od> <ile>, stats, show, reset, save, history, help, exit\n";
            break;

        case 'exit':
            echo "do widzenia\n";
            exit;

        default:
            echo "Nieznane polecenie $polecenie\n";
            break;
    }
    $historia = array_slice($historia,-10);
}