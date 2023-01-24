<?php function konu_mapping($u="") {
    if($u=="") {
        $u = u();
    }
    

    $alan = "say";
    if($u->alan=="Sayısal") $alan = "say";
    if($u->alan=="Sözel") $alan = "soz";
    if($u->alan=="Eşit Ağırlık") $alan = "ea";


    $konu_mapping = c("konu-onem-sirasi");
    $konu_mapping = j($konu_mapping->json);
   // print2($konu_mapping);
    /*
    [turkce_tyt_anlatim_bozuklugu_konu_sirasi] => 3
    [turkce_tyt_anlatim_bozuklugu_say_konu_onemi] => 1
    [turkce_tyt_anlatim_bozuklugu_say_iyi] => 90
    [turkce_tyt_anlatim_bozuklugu_say_orta] => 70
    [turkce_tyt_anlatim_bozuklugu_say_dusuk] => 60
    [turkce_tyt_anlatim_bozuklugu_ea_konu_onemi] => 1
    */
    $kazanimlar = kazanimlar();
    $dizi = [];
    foreach($kazanimlar AS $brans => $deger) {
        foreach($deger AS $konu => $deger2) {
            $pattern = str_slug($brans,"_") . "_" . str_slug($konu,"_") . "_" . $alan;
            $dizi[$brans][$konu]['konu_onemi'] = @$konu_mapping[$pattern . "_konu_onemi"];
            $dizi[$brans][$konu]['iyi'] = @$konu_mapping[$pattern . "_iyi"];
            $dizi[$brans][$konu]['orta'] = @$konu_mapping[$pattern . "_orta"];
            $dizi[$brans][$konu]['dusuk'] = @$konu_mapping[$pattern . "_dusuk"];
        }
    }
    return $dizi;
    
} ?>