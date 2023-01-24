<?php 
function puan($array,$type="") {
   
    $puan = c("katsayi-puan");
    $puan = json_decode($puan['json'],true);
    $sonuc = array();
    $tyt = $puan['tyt'];
    $lgs = $puan['lgs'];
    $lgs6 = $puan['lgs6'];
    $lgs7 = $puan['lgs7'];
    $yks_say = $puan['yks-say'];
    $yks_soz = $puan['yks-soz'];
    $yks_ea = $puan['yks-ea'];
    $tyt_puan = 0;
    foreach($array AS $a => $d) {
        $a = trim($a);
       // echo($a);
        //[DİN KÜLTÜRÜ (TYT)][FELSEFE SEÇMELİ (TYT)]
        if($a=="DİN KÜLTÜRÜ (TYT)") $a = "DİN FELSEFE SEÇMELİ";
      //  if($a=="FELSEFE SEÇMELİ (TYT)") $a = "DİN FELSEFE SEÇMELİ";
       // if($a=="FELSEFE (TYT)") $a = "DİN FELSEFE SEÇMELİ";
        /*
        
        if($a=="FELSEFE (TYT)") $a = "EDEBİYAT - SOSYAL";
        if($a=="DİN KÜLTÜRÜ (TYT)") $a = "EDEBİYAT - SOSYAL";
        if($a=="DİN KÜLTÜRÜ (TYT)") $a = "EDEBİYAT - SOSYAL";
        if($a=="COGRAFYA (TYT)") $a = "EDEBİYAT - SOSYAL";
        if($a=="TARİH (TYT)") $a = "EDEBİYAT - SOSYAL";
        */
        if($a=="TYT") {
            $tyt_puan = $d;
            $yks_say = str_replace("TYT", $d, $yks_say);
            $yks_soz = str_replace("TYT", $d, $yks_soz);
            $yks_ea = str_replace("TYT", $d, $yks_ea);
        }elseif(strpos($a,"TYT")!==false || $a == "DİN FELSEFE SEÇMELİ") {
            $tyt = str_replace("[$a]", $d, $tyt);
        } elseif(strpos($a,"AYT")!==false) {
            $yks_say = str_replace("[$a]", $d, $yks_say);
            $yks_soz = str_replace("[$a]", $d, $yks_soz);
            $yks_ea = str_replace("[$a]", $d, $yks_ea);
        } elseif(strpos($a,"ALTI")!==false) {
            $lgs6 = str_replace("[$a]",$d,$lgs6);
        } elseif(strpos($a,"YEDİ")!==false) {
            $lgs7 = str_replace("[$a]",$d,$lgs7);
        } else {
            $lgs = str_replace("[$a]",$d,$lgs);
        }
        
        
    }
    $puan_tur="";
    try {
        $tyt2 = @eval("return ". $tyt. ";");
        $puan_tur = "tyt";
    } catch (\Throwable $th) {
        $tyt2= 0;
    }
    try {
        $yks_say2 = @eval("return ". $yks_say. ";");
        $puan_tur = "tyt2";
    } catch (\Throwable $th) {
        $yks_say2= 0;
    }
    try {
        $yks_soz2 = @eval("return ". $yks_soz. ";");
        $puan_tur = "tyt2";
    } catch (\Throwable $th) {
        $yks_soz2= 0;
    }
    try {
        $yks_ea2 = @eval("return ". $yks_ea. ";");
        $puan_tur = "tyt2";
    } catch (\Throwable $th) {
        $yks_ea2= 0;
    }
    
    $lgs2 = 0;
    try {
        $lgs2 = @eval("return ". $lgs. ";");
        $puan_tur = "lgs";
    } catch (\Throwable $th) {
        
    }

    try {
        $lgs2 = @eval("return ". $lgs6. ";");
        $puan_tur = "lgs";
        $lgs = $lgs6;
    } catch (\Throwable $th) {
        
    }

    try {
        $lgs2 = @eval("return ". $lgs7. ";");
        $puan_tur = "lgs";
        $lgs = $lgs7;
    } catch (\Throwable $th) {
        
    }
    
    
    $sonuc['tyt-str'] = $tyt;
    $sonuc['tyt-puan'] = $tyt2;
    $sonuc['yks-say'] = $yks_say;
    $sonuc['yks-say-puan'] = $yks_say2;
    $sonuc['yks-soz'] = $yks_soz;
    $sonuc['yks-soz-puan'] = $yks_soz2;
    $sonuc['yks-ea'] = $yks_ea;
    $sonuc['yks-ea-puan'] = $yks_ea2;
    $sonuc['lgs-str'] = $lgs;
    $sonuc['lgs-puan'] = $lgs2;
    /*
    $sonuc['lgs6-str'] = $lgs6;
    $sonuc['lgs6-puan'] = $lgs62;
    $sonuc['lgs7-str'] = $lgs7;
    $sonuc['lgs7-puan'] = $lgs72;
    */
    if($puan_tur=="tyt2") {
        $puan = $tyt_puan;
    } else {
        $puan = @$sonuc[$puan_tur.'-puan'];
    }
    $sonuc['puan'] = $puan;
    $sonuc['puan_tur'] = $puan_tur;
    return $sonuc;
}