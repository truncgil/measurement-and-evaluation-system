<?php 
function sinav_olustur($dizi,$just_id=false, $konu_tarama = false) {
    $db = db("soru_bankasi");
    if($konu_tarama) {
        $db = $db->where("type","Konu Tarama Testi");
    }
    $other = explode(",","sayi,sirala");
    //print2($dizi);
    foreach($dizi AS $alan => $deger) {
        
        if(!in_array($alan,$other)) {
            if(is_array($deger)) {
                
                if($alan=="taksonomik_duzey") {
                 //   echo implode(",",$deger);
                }
                $db = $db->whereIn($alan,$deger);
            } else {
                if($alan=="konu") {
                  
                    $db = $db->where(function($query) use($deger){
                        $deger = trim($deger);
                        $query = $query->orWhere("konu","LIKE","%$deger%");
                        $query = $query->orWhere("kazanim","LIKE","%$deger%");
                    });
                } else {
                    $db = $db->where($alan,"LIKE","%$deger%");
                }
                
            }
        } else {
            if($alan=="sayi") {
                $db = $db->take($deger);
            }
            if($alan=="sirala") {
                $d = explode(":",$deger);
               // print2($d);
                $orderQuery = "CAST({$d[0]} AS DECIMAL) {$d[1]}";
                $db = $db->orderByRaw($orderQuery);
            } else {
                $db = $db->inRandomOrder();
            }
        }
        
        
    }
    $db = $db->whereNull("on_tanim");
    $db = $db->where("type","<>","İptal");
    $sonuc = $db->get();
    if($just_id) {
        $dizi = [];
        foreach($sonuc AS $s) {
            array_push($dizi,$s->id);
        }
      //  echo "ok";
        return $dizi;
    } else {
        return (Array) $sonuc;
    }
    

}

?>