 <?php 
oturumAc();
$u = u();
//print2($_SESSION);
if(oturumisset("cache"))  {
    $cevaplar = $_SESSION['cache']['cevap'];
    $title = $_SESSION['title'];

    $sinavBilgi = db("sinavlar")->where("title",$title)->first();
   

    $netBolum = 4;
    if($sinavBilgi) {
        $sinavJson = j($sinavBilgi->json);
        $netBolum = $sinavJson['net'];
    }

   //dd($_SESSION);
   /*
   $isB = false;
   
    if(isset($_SESSION['sira_type'])) {
        if($_SESSION['sira_type']=="b_sira") {
            $isB = true;
        }
    }
    */
    $kitapcik = "A";
    if(oturumisset("sira_type")) {
        if($_SESSION['sira_type']=="b_sira") {
            $kitapcik = "B";
        }
    }
    
    $cevap_anahtari = array();
    if(count($_SESSION['sorular'])==0) {
        $sinav = db("sinavlar")->where("id", oturum("sinav_id"))->first();
        $dersler = j($sinav->dersler);
        $kazanimlar = j($sinav->kazanimlar);
        usort($dersler, fn($a, $b) => $a['optik'] <=> $b['optik']);
        $k = 1;
        $sorular = [];
        $sql = [];
        foreach($dersler AS $ders) {
            for($k = 1; $k<=$ders['soru']; $k++) {
                $kazanim_pattern = str_slug($ders['isim']) . "_kazanim_" . $k;
                $tak_pattern = str_slug($ders['isim']) . "_tak_" . $k;
                $cevap_pattern = str_slug($ders['isim']) . "_cevap_" . $k;
                $sorular[] = $k;
                $sql[] = (Object) [
                    'id' => $k,
                    'brans'=>$ders['isim'],
                    'a_sira' => $k,
                    'cevap' => @implode(",",$kazanimlar[$cevap_pattern]),
                    'konu' => '',
                    'paragraf_grup' => "",
                    'cover' => "",
                    'html' => "",
                    'title' => "",
                    'video' => "",
                    'konu' => "",
                    'taksonomik_duzey' => @$kazanimlar[$tak_pattern],
                    'kazanim' => @$kazanimlar[$kazanim_pattern]
                ];
            }
        }
        $sorular2 = implode(",",$sorular);
        
    } else {
        $sorular = $_SESSION['sorular'];
        
        $sorular2 = implode(",",$sorular);
        $sql = db("soru_bankasi")->whereIn("id",$sorular)
        ->orderByRaw("FIELD(id, $sorular2)")
        ->get();
    }
    
    //dd($sql);
    $k = 1;
    $analiz = array();
    foreach($sql AS $s) {
       
        $dogru_cevap = explode(",",$s->cevap);
        $cevap_anahtari[$k]['cevap'] = $dogru_cevap;
        $cevap_anahtari[$k]['kazanim'] = $s->kazanim;
        $cevap_anahtari[$k]['konu'] = $s->konu;
        $cevap_anahtari[$k]['brans'] = $s->brans;
        $cevap_anahtari[$k]['taksonomik_duzey'] = $s->taksonomik_duzey;
        $hash = str_slug($s->brans);
        if(!isset($analiz[$hash])) {
            $analiz[$hash]['dogru'] = 0;
            $analiz[$hash]['yanlis'] = 0;
            $analiz[$hash]['bos'] = 0;
            $analiz[$hash]['net'] = 0;
            $analiz[$hash]['kazanim-dogru'] = array();
            $analiz[$hash]['kazanim-yanlis'] = array();
            $analiz[$hash]['kazanim-bos'] = array();
            $analiz[$hash]['tak-dogru'] = array();
            $analiz[$hash]['tak-yanlis'] = array();
            $analiz[$hash]['tak-bos'] = array();
            $analiz[$hash]['cevaplar'] = "";
            
        }
        $ogr_cevap = @$cevaplar[$k];
        if(trim($ogr_cevap)!="") {
            if(in_array($ogr_cevap,$dogru_cevap)) {
                $analiz[$hash]['dogru']++;
                /*
                try {
                    $analiz[$hash]['net'] = $analiz[$hash]['dogru'] - $analiz[$hash]['yanlis']/$netBolum;
                } catch (\Throwable $th) {
                    //throw $th;
                    $analiz[$hash]['net'] = 0;
                }
                */
                array_push($analiz[$hash]['kazanim-dogru'],$s->kazanim);
                array_push($analiz[$hash]['tak-dogru'],$s->taksonomik_duzey);
                $analiz[$hash]['cevaplar'] .= strtoupper($ogr_cevap);
            } else {
                $analiz[$hash]['yanlis']++;
                array_push($analiz[$hash]['kazanim-yanlis'],$s->kazanim);
                array_push($analiz[$hash]['tak-yanlis'],$s->taksonomik_duzey);
                $analiz[$hash]['cevaplar'] .= strtolower($ogr_cevap);
            }
        } else {
            $analiz[$hash]['bos']++;
            array_push($analiz[$hash]['kazanim-bos'],$s->kazanim);
            array_push($analiz[$hash]['tak-bos'],$s->taksonomik_duzey);
            $analiz[$hash]['cevaplar'] .= "*";
        }
        
        
        $k++;
    }
    foreach($analiz AS $hash => $deger) {
        try {
            $analiz[$hash]['net'] = $analiz[$hash]['dogru'] - $analiz[$hash]['yanlis']/$netBolum;
        } catch (\Throwable $th) {
            $analiz[$hash]['net'] = 0;
        }
        
    }
   // dd($analiz);
    //print2($analiz);
    //exit();
    $varmi = db("sonuclar")
    ->where("type","ONLINE")
    ->where("title",$title)
    ->where("uid",$u->id)
    ->first();

    

    if(!is_null($varmi)) {

        $id = $varmi->id;
        db("sonuclar") 
        ->where("type","ONLINE")
        ->where("title",$title)
        ->where("uid",$u->id)
        ->update([
            "kid" => oturum("kid"),
            "kitapcik" =>$kitapcik,
            "sinav_id" => oturum("sinav_id"),
            "analiz" => json_encode_tr($analiz)
        ]);

    } else {
        $insertArray = [
            "title" => $title,
            "type" => "ONLINE",
            "kid" => oturum("kid"),
            "kitapcik" =>$kitapcik,
            "sinav_id" => oturum("sinav_id"),
            "sorular" => implode(",",$sorular),
            "analiz" => json_encode_tr($analiz),
        ];
       // dd($insertArray);

        $id = ekle($insertArray,"sonuclar");
       // dd($id);
    }
   
    $sinav_ogrenci = db("sinavlar_ogrenci")
        ->where("id",oturum("kid"))
        ->where("uid",$u->id)
        ->update([
            'complete'=>1,
            'sonuc_id' => $id
        ]);
    //    dd($sinav_ogrenci);

    sinav_cache_remove();

        

    yonlendir("?t=sinav-sonuc&id=$id");

} else {
    bilgi("Hatalı bir işlem yaptınız. Lütfen tekrar deneyiniz.","danger");
}

 ?>