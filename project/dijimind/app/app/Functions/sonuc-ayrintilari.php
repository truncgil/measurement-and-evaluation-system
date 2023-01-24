<?php function sonuc_ayrintilari($analiz) {
    $analiz = j($analiz->analiz);
  //  print2($analiz);
    $sonuc = [];
    foreach($analiz AS $a => $deger) {
        $sonuc[$a] = [];
        foreach($deger['kazanim-dogru'] AS $konu) {
            if(!isset($sonuc[$a][$konu]['dogru'])) $sonuc[$a][$konu]['dogru'] = 0;
            if(!isset($sonuc[$a][$konu]['yanlis'])) $sonuc[$a][$konu]['yanlis'] = 0;
            if(!isset($sonuc[$a][$konu]['bos'])) $sonuc[$a][$konu]['bos'] = 0;

            $sonuc[$a][$konu]['dogru']++;
        }
        foreach($deger['kazanim-yanlis'] AS $konu) {
            if(!isset($sonuc[$a][$konu]['dogru'])) $sonuc[$a][$konu]['dogru'] = 0;
            if(!isset($sonuc[$a][$konu]['yanlis'])) $sonuc[$a][$konu]['yanlis'] = 0;
            if(!isset($sonuc[$a][$konu]['bos'])) $sonuc[$a][$konu]['bos'] = 0;
            $sonuc[$a][$konu]['yanlis']++;
        }
        foreach($deger['kazanim-bos'] AS $konu) {
            if(!isset($sonuc[$a][$konu]['dogru'])) $sonuc[$a][$konu]['dogru'] = 0;
            if(!isset($sonuc[$a][$konu]['yanlis'])) $sonuc[$a][$konu]['yanlis'] = 0;
            if(!isset($sonuc[$a][$konu]['bos'])) $sonuc[$a][$konu]['bos'] = 0;
            $sonuc[$a][$konu]['bos']++;
        }
       // print2($deger);
        
    }
   return $sonuc;
} ?>