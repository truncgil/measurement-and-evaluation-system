<?php 
function koc_to_ogrenci() {
    $ogrenci = kurum_users("Öğrenci");
    $dizi = [];
    foreach($ogrenci AS $o) {
        if($o->ust=="") $o->ust = "Atanmamış";
        $dizi[$o->ust][] = $o;
    }
    return $dizi;

}
function kurum_info() {
    $u = u();
    if($u->alias!="") {
        $logo = c($u->alias);
        $k = db("users")->where("alias",$u->alias)->where("level","Kurum")->first();
        $this_logo = "";
        if($logo) {
            $this_logo = $logo->cover;
        }
        $k->logo = $this_logo;
        return $k;
    } else {
        return null;
    }
    
}
function kurum_users($seviye="") {
    $u = u();
    $db = db("users")->where("alias",$u->alias);
    if($seviye!="") {
        $db = $db->where("level",$seviye);
    }
    $db = $db->get();
    return dbArray($db,"id");
} 
function kurum_sonuclar() {
    $u = u();
    $db = db("sonuclar")->whereIn("uid",$u->alias_ids)->get();
    return $db;
} 
function kurum_siniflar() {
    $users = kurum_users();
    $sinif = []; 
    foreach($users AS $u) {
        $sinif_isim = trim($u->sinif . " " . $u->sube);
        if(!in_array($sinif_isim,$sinif)) {
            if($sinif_isim!="") {
                array_push($sinif,$sinif_isim);
            }
            
        }
    }
    sort($sinif);
    return $sinif;
}
function kurum_siniflar_detayli() {
    $users = kurum_users();
    $sinif = []; 
    $ogrenciler =  [];
    foreach($users AS $u) {
        $sinif_isim = trim($u->sinif . " " . $u->sube);
        if($sinif_isim=="") $sinif_isim = "Sınıfsız";
        if(!isset($ogrenciler[$sinif_isim])) $ogrenciler[$sinif_isim] = [];
        
        $ogrenciler[$sinif_isim][] = $u->id;
    }
    ksort($ogrenciler);
    
    return $ogrenciler;
}
function sinif_ogrencileri($sinif) {
    //bir sınıfa ait öğrenciler sinif array olarak geldi
    if(!is_array($sinif)) $sinif = [$sinif];
    $users = kurum_users("Öğrenci");
    $ogrenciler = [];
    foreach($users AS $u) {
        $this_sinif = $u->sinif . " " . $u->sube;
        if(in_array($this_sinif,$sinif)) {
            array_push($ogrenciler,$u->id);
        }
        
    }
    return $ogrenciler;
}
function kurum_plani() {
    $u = u();
    $alias = $u->alias;
    $kurum = db("users")->where("level","Kurum")
    ->where("alias",$alias)
    ->first();
    $dizi = [];
    $dizi['max_ogrenci'] = 0;
    $dizi['exp_date'] = "";
    $dizi['kalan_gun'] = 0;
    $dizi['mevcut_ogrenci'] = count(kurum_users("Öğrenci"));
    
    if($kurum) {
       // print2($kurum);
        $kurum_id = $kurum->id;
      //  echo $kurum_id;
        $paketler = db("kurum_paketleri")
        ->where("kid",$kurum_id)
        ->where("durum","Ödeme Onaylandı")
        ->get();
        
        if($paketler) {
           // print2($paketler);
            
            foreach($paketler AS $paket) {
                //print2($paket);
                $tarih1= new DateTime();
                $tarih2= new DateTime($paket->date);
                $interval= $tarih1->diff($tarih2);
                $dizi['max_ogrenci'] += $paket->max;
                $dizi['exp_date'] = $paket->date;
                $dizi['kalan_gun'] = $interval->format('%a');

            }
            $dizi['kalan_ogrenci'] = $dizi['max_ogrenci'] - $dizi['mevcut_ogrenci'];

            return $dizi;
            
        } else {
            return false;
        }
    } else {
        return false;
    }
    
}
?>