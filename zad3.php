<?php

$dokumenty = [
    0 => "PHP jest jezykiem skryptowym uzywanym do tworzenia stron internetowych",
    1 => "Tablice w PHP moga byc indeksowane lub asocjacyjne i bardzo przydatne",
    2 => "Funkcje array_map i array_filter ulatwiaja przetwarzanie tablic w PHP",
    3 => "PHP obsluguje tablice wielowymiarowe i zagniezdzone struktury danych",
    4 => "Serwer Apache wspolpracuje z PHP do obslugi zadan HTTP i polaczen",
    5 => "Bazy danych MySQL sa czesto uzywane razem z PHP do przechowywania",
    6 => "Funkcja usort sortuje tablice w PHP wedlug roznych kryteriow i warunkow",
    7 => "JavaScript i PHP razem tworza dynamiczne aplikacje internetowe i serwisy",
    8 => "PHP posiada wbudowane funkcje do pracy z plikami tablicami i bazami",
    9 => "Bezpieczenstwo aplikacji PHP wymaga walidacji danych wejsciowych i filtrow",
];

$stopwords = ['i','w','na','do','z','sa','lub','byc','moze','jest','sie'];

$index = [];
$global = [];

foreach ($dokumenty as $doc_id => $tekst) {

    $tekst = strtolower($tekst);
    $tekst = preg_replace('/[^a-z0-9 ]/', '', $tekst);
    $slowa = explode(" ", $tekst);

    foreach ($slowa as $slowo) {

        if(strlen($slowo) < 3) continue;
        if(in_array($slowo,$stopwords)) continue;

        if(!isset($index[$slowo][$doc_id])){
            $index[$slowo][$doc_id] = 0;
        }

        $index[$slowo][$doc_id]++;

        if(!isset($global[$slowo])){
            $global[$slowo] = 0;
        }

        $global[$slowo]++;
    }
}

arsort($global);

echo "Top 5 najczestszych slow:\n";
$top = array_slice($global,0,5,true);

foreach($top as $slowo=>$ile){
    echo "$slowo: {$ile}x\n";
}

echo "\n";

function searchAND($query,$index){

    $lists = [];

    foreach($query as $q){
        if(isset($index[$q])){
            $lists[] = array_keys($index[$q]);
        }
    }

    if(empty($lists)) return [];

    $docs = call_user_func_array('array_intersect',$lists);

    $results = [];

    foreach($docs as $doc){

        $score = 0;
        $details = [];

        foreach($query as $q){

            $count = $index[$q][$doc] ?? 0;
            $score += $count;
            $details[] = "$q:$count";
        }

        $results[$doc] = [
            "score"=>$score,
            "details"=>implode(", ",$details)
        ];
    }

    uasort($results,function($a,$b){
        return $b["score"] <=> $a["score"];
    });

    return $results;
}

function searchOR($query,$index){

    $lists = [];

    foreach($query as $q){
        if(isset($index[$q])){
            $lists[] = array_keys($index[$q]);
        }
    }

    if(empty($lists)) return [];

    $docs = array_unique(call_user_func_array('array_merge',$lists));

    $results = [];

    foreach($docs as $doc){

        $score = 0;
        $details = [];

        foreach($query as $q){

            $count = $index[$q][$doc] ?? 0;

            if($count>0){
                $score += $count;
                $details[] = "$q:$count";
            }
        }

        $results[$doc] = [
            "score"=>$score,
            "details"=>implode(", ",$details)
        ];
    }

    uasort($results,function($a,$b){
        return $b["score"] <=> $a["score"];
    });

    return $results;
}

echo "Wyniki dla (php AND tablice):\n";

$and = searchAND(["php","tablice"],$index);

foreach($and as $doc=>$data){
    echo "Dokument ID:$doc | Score:{$data["score"]} ({$data["details"]})\n";
}

echo "\n";

echo "Wyniki dla (mysql OR javascript):\n";

$or = searchOR(["mysql","javascript"],$index);

foreach($or as $doc=>$data){
    echo "Dokument ID:$doc | Score:{$data["score"]} ({$data["details"]})\n";
}

?>