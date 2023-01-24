<?php 

//hesaplamaları sıfırlamak için
/*
db("sonuclar")
->where("sonuc_hesapla","1")
->update([
    'sonuc_hesapla' => 0
]);
*/


$sonuclar = db("sonuclar")
->where("sonuc_hesapla","0")
->get();
foreach($sonuclar AS $s) {
    $netler = [];
    $diger_sinav_id = 0;
   // print2($s->analiz);
    $analiz = j($s->analiz);
    
   // print2($analiz);
   if(!empty($analiz)) {
      //  dump($analiz);
       $guncelle = true;
        print2("-".$s->title);
        foreach($analiz AS $brans => $veri) {
            $title = slug_to_title($brans);
            $netler[$title] = $veri['net'];
        }
        dump($netler);
        if(strpos($s->title,"TYT")!==false) {
            if(!isset($netler['FELSEFE (TYT)'])) $netler['FELSEFE (TYT)'] = 0;
            if(!isset($netler['TARİH (TYT)'])) $netler['TARİH (TYT)'] = 0;
            if(!isset($netler['GEOMETRİ (TYT)'])) $netler['GEOMETRİ (TYT)'] = 0;
            if(!isset($netler['FİZİK (TYT)'])) $netler['FİZİK (TYT)'] = 0;
            if(!isset($netler['KİMYA (TYT)'])) $netler['KİMYA (TYT)'] = 0;
            if(!isset($netler['BİYOLOJİ (TYT)'])) $netler['BİYOLOJİ (TYT)'] = 0;
        }
        if(strpos($s->title,"AYT")!==false) {

            $tyt_sinav = db("sinavlar")
                ->select("bag")
                ->where("id",$s->sinav_id)
                ->first();

            if($tyt_sinav) {

                $tyt_sonuc = db("sonuclar")
                    ->select("tyt")
                    ->where("sinav_id",$tyt_sinav->bag)
                    ->where("uid",$s->uid)
                    ->first();


                if($tyt_sonuc) {
                    $netler['TYT'] = $tyt_sonuc->tyt;
                }
                
            }
            
        }
        if(strpos($s->title,"LGS")!==false) {
            print2("--LGS'ye göre değerlendiriyor");
            if(strpos($s->title,"Sayısal")!==false) {
                print2("---Sözel sonuç alınıyor");
                $diger_sonuc = db("sonuclar")->where("uid",$s->uid)
                    ->where("title","like","%Sözel%")
                    ->orderBy("id","DESC")->first();
                    if($diger_sonuc) {
                        $diger_sinav_id = $diger_sonuc->id;
                        $diger_sonuc = j($diger_sonuc->analiz); 
                        //  print2($analiz);
                          foreach($diger_sonuc AS $brans => $veri) {
                              $title = slug_to_title($brans);
                              $netler[$title] = $veri['net'];
                          }
                        
                    } else {
                        $guncelle = false;
                    }
                
                
              //  print2($diger_sonuc);
            }
            if(strpos($s->title,"Sözel")!==false) {
                print2("---Sayısal sonuç alınıyor");
                $diger_sonuc = db("sonuclar")->where("uid",$s->uid)
                    ->where("title","like","%Sayısal%")
                    ->orderBy("id","DESC")->first();
                    if($diger_sonuc) {
                        $diger_sinav_id = $diger_sonuc->id;
                        $diger_sonuc = j($diger_sonuc->analiz); 
                        // print2($analiz);
                         foreach($diger_sonuc AS $brans => $veri) {
                             $title = slug_to_title($brans);
                             $netler[$title] = $veri['net'];
                         }
                        
                    } else {
                        $guncelle = false;
                    }
               
            //    print2($diger_sonuc);
               
            }
            
        }
        
        //print2($netler);
        $puanlar = puan($netler);
        dump($puanlar);
        /*
        if(strpos($s->title,"AYT")!==false) {
            dd($puanlar);
        }
        */
       // print2($puanlar);
       /*
       dump($guncelle);
       dump($diger_sinav_id);
       dump($puanlar);
       */
       
        if($guncelle) {
            db("sonuclar")
            ->whereIn("id",[$s->id,$diger_sinav_id])
            ->update([
                "netler" => json_encode_tr($netler),
                "puanlar" => json_encode_tr($puanlar),
                "tyt" => $puanlar['tyt-puan'],
                "yks_say" => $puanlar['yks-say-puan'],
                "yks_soz" => $puanlar['yks-soz-puan'],
                "yks_ea" => $puanlar['yks-ea-puan'],
                "lgs" => $puanlar['lgs-puan'],
                "sonuc_hesapla" => 1,
                "puan" => $puanlar['puan'],
                "puan_tur" => $puanlar['puan_tur']
            ]);
            print2("----Güncellendi");
        } else {
            print2("----Güncelleme için sonuç bekliyor");
        }
        
        
   }
    
}
?>