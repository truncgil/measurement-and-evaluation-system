<?php 

//Log::debug('Analiz Cron Çalıştı');
/**
 * Dijimind Analiz 
 * Konu Eksikliklerini yapılacaklara atayan ve KAS sınavları atayan sistem
 * @author Ümit Tunç / Trunçgil Teknoloji 
 * 
 * 
 */
$kas_sinavi_soru_sayisi = 12;
$sinavlar = table_to_array("sinavlar","id");
$kazanimlar = kazanimlar();
$taksonomi_map = taksonomi_map();
$sorumlu_dersler = sorumlu_dersler();
//dd($sorumlu_dersler);
$kazanim_to_konu = [];
foreach($kazanimlar AS $brans => $kazanim_item) {
    foreach($kazanim_item AS $konu => $kazanim) {
        foreach($kazanim AS $per_kazanim_item) {
            $per_kazanim_item_trim = trim($per_kazanim_item);
   //         $kazanim_to_konu[$brans][$per_kazanim_item_trim] = trim($konu);
    //        $kazanim_to_konu[$brans][$per_kazanim_item] = trim($konu);
            $kazanim_to_konu[$brans][str_slug($per_kazanim_item)] = trim($konu);
   //         $kazanim_to_konu[$brans][md5(trim($per_kazanim_item))] = trim($konu);
   //         $kazanim_to_konu[$brans][md5($per_kazanim_item)] = trim($konu);
        }
        
    }
}
//dd($kazanim_to_konu);
//print2($kazanim_to_konu);

/*
db("sonuclar")
        ->update(['bildirim' => 0]);
db("todo")->delete();
db("sinavlar_ogrenci")->delete();

*/


//print2($kazanim_to_konu);
//echo db("todo")->where("kid",418)->delete() . " todo delete <br>";
//echo db("sinavlar")->where("kid",418)->delete() . " sinav delete";
$sonuclar = db("sonuclar")
->whereNotNull("uid")
->where("bildirim",0)
//->where("id",418)
->take(10)
->orderBy("id","DESC")
->get();


$mail_text = "";
foreach($sonuclar AS $s) {
    //Log::debug("{$s->id} Sonuç Analiz Ediliyor...");
     ?>
     <h1>{{$s->id}} Analiz Ediliyor...</h1>

     <?php 
    try {
        $ogrenci = u2($s->uid);
        $j = j($s->analiz);
        $sinav_id = $s->id;
        $dizi = array();
        $dizi2 = array();
        $dizi3 = [];
        $dizi4 = [];
        $total = 0;
        $kazanim_array = [];
        $taksonomi_array = [];
        $yuzdelik_array = [];
        $net_bolumu = 4;
        if(isset($sinavlar[$s->sinav_id])) {
            $sinav = $sinavlar[$s->sinav_id];
            $js = j($sinav->json);
            $net_bolumu = $js['net'];
        }
        if(strpos($s->title,"LGS")!==false) {
            $net_bolumu = 3;
        }
    
    //kazanımları konuya dönüştürmek için böyle bir şey yapıyoruz.
    //print2($kazanim_to_konu);
    /*
    foreach($j AS $alan => $deger) {
        $title = slug_to_title($alan);
        foreach($deger['kazanim-dogru'] AS $kazanim) {
         //   $kazanim = trim($kazanim);
            if(isset($kazanim_to_konu[$title][$kazanim])) {
             //   $kazanim = $kazanim_to_konu[$title][$kazanim];
                $j[$alan][$kazanim] = ($kazanim_to_konu[$title][$kazanim]);
            }
        }
        foreach($deger['kazanim-yanlis'] AS $kazanim) {
           // $kazanim = trim($kazanim);
            if(isset($kazanim_to_konu[$title][$kazanim])) {
            //    $kazanim = $kazanim_to_konu[$title][$kazanim];
                $j[$alan][$kazanim] = ($kazanim_to_konu[$title][$kazanim]);
            }
        }
        foreach($deger['kazanim-bos'] AS $kazanim) {
          //  $kazanim = trim($kazanim);
            if(isset($kazanim_to_konu[$title][$kazanim])) {
            //    $kazanim = $kazanim_to_konu[$title][$kazanim];
                $j[$alan][$kazanim] = ($kazanim_to_konu[$title][$kazanim]);
            }
        }
        
    }
    */
    
    //dd($j);
    //print2($j); exit();
        if(is_array($j)) {
            foreach($j AS $alan => $deger) {
                foreach($deger['tak-bos'] AS $tak) {
                    if(!isset($taksonomi_array[$alan][$tak]['dogru'])) $taksonomi_array[$alan][$tak]['dogru'] = 0;
                    if(!isset($taksonomi_array[$alan][$tak]['bos'])) $taksonomi_array[$alan][$tak]['bos'] = 0;
                    if(!isset($taksonomi_array[$alan][$tak]['yanlis'])) $taksonomi_array[$alan][$tak]['yanlis'] = 0;
                    if(!isset($taksonomi_array[$alan][$tak]['toplam'])) $taksonomi_array[$alan][$tak]['toplam'] = 0;
                    if(!isset($taksonomi_array[$alan][$tak]['percent'])) $taksonomi_array[$alan][$tak]['percent'] = 0;
                    $taksonomi_array[$alan][$tak]['bos']++;
                    $taksonomi_array[$alan][$tak]['toplam']++;
                    
                }
                foreach($deger['tak-dogru'] AS $tak) {
                    if(!isset($taksonomi_array[$alan][$tak]['dogru'])) $taksonomi_array[$alan][$tak]['dogru'] = 0;
                    if(!isset($taksonomi_array[$alan][$tak]['bos'])) $taksonomi_array[$alan][$tak]['bos'] = 0;
                    if(!isset($taksonomi_array[$alan][$tak]['yanlis'])) $taksonomi_array[$alan][$tak]['yanlis'] = 0;
                    if(!isset($taksonomi_array[$alan][$tak]['toplam'])) $taksonomi_array[$alan][$tak]['toplam'] = 0;
                    if(!isset($taksonomi_array[$alan][$tak]['percent'])) $taksonomi_array[$alan][$tak]['percent'] = 0;
                    $taksonomi_array[$alan][$tak]['dogru']++;
                    $taksonomi_array[$alan][$tak]['toplam']++;
                    
                }
                foreach($deger['tak-yanlis'] AS $tak) {
                    if(!isset($taksonomi_array[$alan][$tak]['dogru'])) $taksonomi_array[$alan][$tak]['dogru'] = 0;
                    if(!isset($taksonomi_array[$alan][$tak]['bos'])) $taksonomi_array[$alan][$tak]['bos'] = 0;
                    if(!isset($taksonomi_array[$alan][$tak]['yanlis'])) $taksonomi_array[$alan][$tak]['yanlis'] = 0;
                    if(!isset($taksonomi_array[$alan][$tak]['toplam'])) $taksonomi_array[$alan][$tak]['toplam'] = 0;
                    if(!isset($taksonomi_array[$alan][$tak]['percent'])) $taksonomi_array[$alan][$tak]['percent'] = 0;
                    $taksonomi_array[$alan][$tak]['yanlis']++;
                    $taksonomi_array[$alan][$tak]['toplam']++;
                    
                }
            

                $title = slug_to_title($alan);
                $brans_title = $title;
                foreach($deger['kazanim-dogru'] AS $kazanim) {
                
                    if(!isset($kazanim_array[$alan][$kazanim]['dogru'])) $kazanim_array[$alan][$kazanim]['dogru'] = 0;
                    if(!isset($kazanim_array[$alan][$kazanim]['bos'])) $kazanim_array[$alan][$kazanim]['bos'] = 0;
                    if(!isset($kazanim_array[$alan][$kazanim]['yanlis'])) $kazanim_array[$alan][$kazanim]['yanlis'] = 0;
                    if(!isset($kazanim_array[$alan][$kazanim]['toplam'])) $kazanim_array[$alan][$kazanim]['toplam'] = 0;
                    if(!isset($kazanim_array[$alan][$kazanim]['percent'])) $kazanim_array[$alan][$kazanim]['percent'] = 0;
                    $kazanim_array[$alan][$kazanim]['dogru']++;
                    $kazanim_array[$alan][$kazanim]['toplam']++;
                    
                }
                foreach($deger['kazanim-yanlis'] AS $kazanim) {
                    
                    if(!isset($kazanim_array[$alan][$kazanim]['dogru'])) $kazanim_array[$alan][$kazanim]['dogru'] = 0;
                    if(!isset($kazanim_array[$alan][$kazanim]['bos'])) $kazanim_array[$alan][$kazanim]['bos'] = 0;
                    if(!isset($kazanim_array[$alan][$kazanim]['yanlis'])) $kazanim_array[$alan][$kazanim]['yanlis'] = 0;
                    if(!isset($kazanim_array[$alan][$kazanim]['toplam'])) $kazanim_array[$alan][$kazanim]['toplam'] = 0;
                    if(!isset($kazanim_array[$alan][$kazanim]['percent'])) $kazanim_array[$alan][$kazanim]['percent'] = 0;
                    $kazanim_array[$alan][$kazanim]['yanlis']++;
                    $kazanim_array[$alan][$kazanim]['toplam']++;
                }
                foreach($deger['kazanim-bos'] AS $kazanim) {
                    
                    if(!isset($kazanim_array[$alan][$kazanim]['dogru'])) $kazanim_array[$alan][$kazanim]['dogru'] = 0;
                    if(!isset($kazanim_array[$alan][$kazanim]['bos'])) $kazanim_array[$alan][$kazanim]['bos'] = 0;
                    if(!isset($kazanim_array[$alan][$kazanim]['yanlis'])) $kazanim_array[$alan][$kazanim]['yanlis'] = 0;
                    if(!isset($kazanim_array[$alan][$kazanim]['toplam'])) $kazanim_array[$alan][$kazanim]['toplam'] = 0;
                    if(!isset($kazanim_array[$alan][$kazanim]['percent'])) $kazanim_array[$alan][$kazanim]['percent'] = 0;
                    $kazanim_array[$alan][$kazanim]['bos']++;
                    $kazanim_array[$alan][$kazanim]['toplam']++;
                }
                echo "<h1>$alan</h1>";
                //Log::debug("$alan");
            //  print2($deger);
            // echo $kazanim_to_konu['MATEMATİK (TYT)']['Asal Sayılar Ve Aralarında Asal Sayılar'];
            //   print2($kazanim_to_konu);
            //  print2($deger);
                
                foreach($kazanim_array[$alan] AS $kazanim => $sayi) {
                    
                //   $kazanim_index = $kazanim;
                    try {
                        $bu_net = $kazanim_array[$alan][$kazanim]['dogru'] - $kazanim_array[$alan][$kazanim]['yanlis'] / $net_bolumu;
                    } catch (\Throwable $th) {
                        $bu_net = 0;
                    }
                    $kazanim_array[$alan][$kazanim]['net'] = $bu_net;
                    $kazanim_array[$alan][$kazanim]['percent'] = round($bu_net * 100 / $kazanim_array[$alan][$kazanim]['toplam'],0);
                }
                
            // print2($kazanim_array); exit();
                $k = 0;
                foreach($deger['tak-dogru'] AS $tak) {
                    $level = taksonomi_to_level($tak);
                    if(trim($level)=="") $level = "Junior";
                    $konu = $deger['kazanim-dogru'][$k];
                    
                    if(isset($kazanim_to_konu[$title][str_slug($deger['kazanim-dogru'][$k])])) {
                        $konu = $kazanim_to_konu[$title][str_slug($deger['kazanim-dogru'][$k])];
                    } else {

                        print2("$title {$deger['kazanim-dogru'][$k]} bulunamadı");
                        //Log::debug("$title $konu bulunamadı");
                    }
                
                        
                    if(!isset($dizi[$level])) $dizi[$level] = 0;
                    if(!isset($dizi2[$alan][$level])) $dizi2[$alan][$level] = 0;
                    if(!isset($dizi2[$alan]["Toplam"])) $dizi2[$alan]["Toplam"] = 0;
                    if(!isset($dizi3[$alan][$level]["Doğru"])) $dizi3[$alan][$level]["Doğru"] = 0;
                    if(!isset($dizi4[$alan][$konu][$level]["Doğru"])) $dizi4[$alan][$konu][$level]["Doğru"] = 0;
                    if(!isset($dizi4[$alan][$konu][$level]["Boş"])) $dizi4[$alan][$konu][$level]["Boş"] = 0;
                    if(!isset($dizi4[$alan][$konu][$level]["Yanlış"])) $dizi4[$alan][$konu][$level]["Yanlış"] = 0;
                    if(!isset($dizi4[$alan][$konu][$level]["Toplam"])) $dizi4[$alan][$konu][$level]["Toplam"] = 0;
                    if(!isset($dizi4[$alan][$konu][$level]["Percent"])) $dizi4[$alan][$konu][$level]["Percent"] = 0;
                    
                    
                //    if(!isset($dizi3[$alan][$level]["Boş"])) $dizi3[$alan][$level]["Boş"] = 0;
                    $dizi[$level]++;
                    $dizi2[$alan][$level]++;
                    $dizi2[$alan]["Toplam"]++;
                    $dizi3[$alan][$level]["Doğru"]++;
                    $dizi4[$alan][$konu][$level]["Doğru"]++;
                    $dizi4[$alan][$konu][$level]["Toplam"]++;
                    $dizi4[$alan][$konu][$level]["Percent"] = $dizi4[$alan][$konu][$level]["Doğru"] *100/ $dizi4[$alan][$konu][$level]["Toplam"];
                
                    
                    $total++;
                    $k++;
                }
                $k = 0;
                foreach($deger['tak-yanlis'] AS $tak) {
                $level = taksonomi_to_level($tak);
                if(trim($level)=="") $level = "Junior";
                $konu = $deger['kazanim-yanlis'][$k];
                
                    if(isset($kazanim_to_konu[$title][str_slug($deger['kazanim-yanlis'][$k])])) {
                        $konu = $kazanim_to_konu[$title][str_slug($deger['kazanim-yanlis'][$k])];
                    } else {
                        print2("$title {$deger['kazanim-yanlis'][$k]} bulunamadı");
                        //Log::debug("$title $konu bulunamadı");
                    }
                
                if(!isset($dizi3[$alan][$level]["Yanlış"])) $dizi3[$alan][$level]["Yanlış"] = 0;
                if(!isset($dizi4[$alan][$konu][$level]["Doğru"])) $dizi4[$alan][$konu][$level]["Doğru"] = 0;
                    if(!isset($dizi4[$alan][$konu][$level]["Boş"])) $dizi4[$alan][$konu][$level]["Boş"] = 0;
                    if(!isset($dizi4[$alan][$konu][$level]["Yanlış"])) $dizi4[$alan][$konu][$level]["Yanlış"] = 0;
                    if(!isset($dizi4[$alan][$konu][$level]["Percent"])) $dizi4[$alan][$konu][$level]["Percent"] = 0;
                    if(!isset($dizi4[$alan][$konu][$level]["Toplam"])) $dizi4[$alan][$konu][$level]["Toplam"] = 0;
                $dizi3[$alan][$level]["Yanlış"]++;
                $dizi4[$alan][$konu][$level]["Yanlış"]++;
                $dizi4[$alan][$konu][$level]["Toplam"]++;
                $dizi4[$alan][$konu][$level]["Percent"] = $dizi4[$alan][$konu][$level]["Doğru"] *100/ $dizi4[$alan][$konu][$level]["Toplam"];
                $k++;
                }
                $k = 0;
                foreach($deger['tak-bos'] AS $tak) {
                $level = taksonomi_to_level($tak);
                if(trim($level)=="") $level = "Junior";
                $konu = $deger['kazanim-bos'][$k];
                
                    if(isset($kazanim_to_konu[$title][str_slug($deger['kazanim-bos'][$k])])) {
                        $konu = $kazanim_to_konu[$title][str_slug($deger['kazanim-bos'][$k])];
                    } else {
                        print2("$title {$deger['kazanim-bos'][$k]} bulunamadı");
                        //Log::debug("$title $konu bulunamadı");
                    }
                
                    if(!isset($dizi3[$alan][$level]["Boş"])) $dizi3[$alan][$level]["Boş"] = 0;
                    if(!isset($dizi4[$alan][$konu][$level]["Doğru"])) $dizi4[$alan][$konu][$level]["Doğru"] = 0;
                    if(!isset($dizi4[$alan][$konu][$level]["Boş"])) $dizi4[$alan][$konu][$level]["Boş"] = 0;
                    if(!isset($dizi4[$alan][$konu][$level]["Yanlış"])) $dizi4[$alan][$konu][$level]["Yanlış"] = 0;
                    if(!isset($dizi4[$alan][$konu][$level]["Percent"])) $dizi4[$alan][$konu][$level]["Percent"] = 0;
                    if(!isset($dizi4[$alan][$konu][$level]["Toplam"])) $dizi4[$alan][$konu][$level]["Toplam"] = 0;
                $dizi3[$alan][$level]["Boş"]++;
                $dizi4[$alan][$konu][$level]["Boş"]++;
                $dizi4[$alan][$konu][$level]["Toplam"]++;
                $dizi4[$alan][$konu][$level]["Percent"] = $dizi4[$alan][$konu][$level]["Doğru"] *100/ $dizi4[$alan][$konu][$level]["Toplam"];
                $k++;
                }
            }

     //   dd($dizi4);
   
    // print2($dizi4);
        //exit();
        
    
    //  print2($kazanim_array); exit();
        $junior_percent = 0;
        $pratisyen_percent = 0;
        $master_percent = 0;
        if(isset($dizi['Junior'])) $junior_percent = round($dizi['Junior'] * 100 / $total,0);
        if(isset($dizi['Pratisyen'])) $pratisyen_percent = round($dizi['Pratisyen'] * 100 / $total,0);
        if(isset($dizi['Master'])) $master_percent = round($dizi['Master'] * 100 / $total,0);
    // print2($kazanim_array); exit();
        $percent_array = [];
    //  print2($j);
    //dd($j);

        foreach($j AS $alan => $deger)  { 
            $title = slug_to_title($alan);
            if(isset($dizi2[$alan]['Toplam'])) 
            {
                $total = $dizi2[$alan]['Toplam'];
                $junior_percent = 0;
                $pratisyen_percent = 0;
                $master_percent = 0;
                if(isset($dizi2[$alan]['Junior'])) $junior_percent = round($dizi2[$alan]['Junior'] * 100 / $total,0);
                if(isset($dizi2[$alan]['Pratisyen'])) $pratisyen_percent = round($dizi2[$alan]['Pratisyen'] * 100 / $total,0);
                if(isset($dizi2[$alan]['Master'])) $master_percent = round($dizi2[$alan]['Master'] * 100 / $total,0);
            
                $percent_array[$alan]['Junior'] = $junior_percent;
                $percent_array[$alan]['Pratisyen'] = $pratisyen_percent;
                $percent_array[$alan]['Master'] = $master_percent;
            } else {
                $percent_array[$alan]['Junior'] = 0;
                $percent_array[$alan]['Pratisyen'] = 0;
                $percent_array[$alan]['Master'] = 0;
            }
        }
        foreach($dizi3[$alan] AS $tur => $sonuclar)  { 
            $sonuc_toplam = 0;
            foreach($sonuclar AS $so_alan => $so_deger) {
                $sonuc_toplam+=$so_deger;
            }
        }
        
        $konu_mapping = c("konu-onem-sirasi");
        $konu_mapping = j($konu_mapping->json);
        //print2($konu_mapping);
        //öğrencinin alanına ve seviyesine göre hedef taksiminde bulunacağız. 
        $okul_alan = "say";
        $okul_alan2 = "say";
        if(isset($ogrenci)) {
            if($ogrenci->alan=="Sayısal") {
                $okul_alan = "say";
                $okul_alan2 = "sayisal";
            }
            if($ogrenci->alan=="Eşit Ağırlık") {
                $okul_alan = "ea";
                $okul_alan2 = "esit-agirlik";
            }
            if($ogrenci->alan=="Sözel") {
                $okul_alan = "soz";
                $okul_alan2 = "sozel";
            }
            if($ogrenci->alan=="lgs" || $ogrenci->alan=="LGS") {
                $okul_alan = "lgs";
                $okul_alan2 = "lgs";
            }
            if($ogrenci->alan=="") {
                //Log::error("{$ogrenci->id} {$ogrenci->name} {$ogrenci->surname} için okul alanı girilmelidir");
                $okul_alan = "yok";
                $okul_alan2 = "yok";
             //   dump($ogrenci);
                echo "{$ogrenci->id} {$ogrenci->name} {$ogrenci->surname} için okul alanı girilmelidir";
                continue;
            }
        } else {
            echo "{$s->uid} bulunamadı";
            //Log::debug("{$s->uid} bulunamadı");
            db("sonuclar")->where("id",$s->id)->update([
                'bildirim' => '-1'
            ]);
            adminSend(
                    "Dijimind: Sınavı pasife aldım",
                    "Sayın Admin, 
                    {$s->uid} numaralı öğrenci bulunamadığından {$s->title} sınavı pasife aldım");

            continue;
        }
        print2("okul alanı: $okul_alan");
        //Log::debug("okul alanı: $okul_alan");
        //Log::debug("okul alanı 2: $okul_alan2");
        print2($okul_alan2);
    //    print2($ogrenci);

    // print2($dizi);
    // print2($dizi2);
        //$esik_deger = [90 => 'iyi' => ,"orta" => 70, "dusuk" => 60];
        //print2($konu_mapping);
            //sonuçları analiz et
            /*
            [turkce_tyt_anlatim_bozuklugu_konu_sirasi] => 12
            [turkce_tyt_anlatim_bozuklugu_say_konu_onemi] => 22 
            [turkce_tyt_anlatim_bozuklugu_say_iyi] => 36
            [turkce_tyt_anlatim_bozuklugu_say_orta] => 4
            */
            //print2($kazanim_array);
            $gorevler = [];
            $levels = [];
            $tak_soru_array = []; 
            $gorev_konu_listesi = [];
            $levels = [];
            print2($dizi4);
        
        
        
        // print2($kazanim_to_konu);
            $gorevler = [];
            foreach($dizi4 AS $brans => $brans_veri) {
                $title = slug_to_title($brans);
                print2($title);
                $star = 0;
                if($title == "TÜRKÇE (TYT)") $star = 1;
                if($title == "MATEMATİK (TYT)") $star = 1;
                if($title == "TÜRKÇE (LGS)") $star = 1;
                if($title == "MATEMATİK (LGS)") $star = 1;
                if($title == "GEOMETRİ (TYT)") $star = 1;
                foreach($brans_veri AS $konu => $konu_veri) {
                    print2($konu);
                    //Log::debug($konu);
                    
            
                    foreach($konu_veri AS $duzey => $veri) {
                    // print2($duzey);
                        $konu_mapping_brans = str_slug($title,"_");
                        //$levels[$title][$konu] = $duzey;
                        $master_gorev_tanimlari = ['Konunun diğer konular ile ilişkisine dikkat et⚠️','Üst düzey sorulardan 15 soru çöz 👍'];
                        $master_soru_sayisi= 15;
                        $pratisyen_gorev_tanimlari = ['Konuyu tekrar et 🔁','Orta düzeyde 20 soru çöz 🌟'];
                        $pratisyen_soru_sayisi= 20;
                        $junior_gorev_tanimlari = ['Konuyu iyice çalış🐜',"Temel düzeyde 10 soru çöz 🤏"];
                        $junior_soru_sayisi= 10;
                        
                        $hash2 = $konu_mapping_brans."_".str_slug($konu,"_") . "_" . $okul_alan . "_konu_onemi"; // konu önem 
                        //print2($hash);
                        //print2($hash2);
                        $konu_sira = 1;
                        if(isset($konu_mapping[$hash2])) {
                            $konu_sira =(int) $konu_mapping[$hash2];
                        }
                        
                        $konu_mapping_duzey  = "dusuk";
                        if($duzey=="Master") {
                            $konu_mapping_duzey  = "iyi";
                            $konu_sira += 3; //-Aynı sınav sonucuna göre her üç düzeyden de göre ataması olacaksa öğrenciye öncelik sırasını belirtmemiz gerekir
                            
                        }
                        if($duzey=="Pratisyen") {
                            $konu_mapping_duzey  = "orta";
                            $konu_sira += 2; //-Aynı sınav sonucuna göre her üç düzeyden de göre ataması olacaksa öğrenciye öncelik sırasını belirtmemiz gerekir
                            
                        }
                        if($duzey=="Junior") {
                            $konu_mapping_duzey  = "dusuk";
                            print2($konu_sira);
                        //   exit();
                        //   $konu_sira += 1; //-Aynı sınav sonucuna göre her üç düzeyden de göre ataması olacaksa öğrenciye öncelik sırasını belirtmemiz gerekir
                            
                        }
                        $hash = $konu_mapping_brans."_".str_slug($konu,"_") . "_" . $okul_alan . "_" . $konu_mapping_duzey;
                        $beklenen_basari = 100;
                        if(isset($konu_mapping[$hash])) {
                            $beklenen_basari =(int) $konu_mapping[$hash];
                        }
                        $alan_onemi = $konu_mapping_brans.'_'.$okul_alan2.'_onem';
                        //dd($konu_mapping[$alan_onemi]);
                        
                        
                        if(isset($konu_mapping[$alan_onemi])) {
                            if($konu_mapping[$alan_onemi] !="") {
                            //  echo $konu_sira;
                                $konu_sira = $konu_sira * $konu_mapping[$alan_onemi];
                                if($konu_mapping[$alan_onemi]>2) {
                            //     echo $konu_mapping_brans;
                            //     echo $okul_alan2;
                                // dd($konu_sira);
                                }
                            }
                        }
                        
                    
                        //dd($konu_sira);
                        print2("sira:".$konu_sira);
                        print2("beklenen_basari:". $beklenen_basari);

                        $basari_farki = $beklenen_basari - $veri['Percent']; //map de istediğimiz düzey ile olması gereken düzeyin arasındaki farkı hesaplıyoruz
                        print2("başarı farkı $basari_farki");
                        $gorev_tanimlari = [];
                        print2("Düzey $duzey");

                        //Log::debug("sira:".$konu_sira);
                        //Log::debug("beklenen_basari:". $beklenen_basari);
                        //Log::debug("Düzey $duzey");

                        if($basari_farki>30) {  //eğer fark 30dan büyükse 
                            if($duzey=="Master") {
                                $gorev_tanimlari = [
                                    "Master" => $master_gorev_tanimlari,
                                    "Pratisyen" => $pratisyen_gorev_tanimlari,
                                    "Junior" => $junior_gorev_tanimlari
                                ];
                                $sorumlu_dersler_pattern = str_slug($title.' '. $okul_alan2);
                                print2("sorumlu_dersler_pattern $sorumlu_dersler_pattern");
                                //Log::debug("sorumlu_dersler_pattern $sorumlu_dersler_pattern");

                                if(isset($sorumlu_dersler[$sorumlu_dersler_pattern])) {
                                    foreach($gorev_tanimlari AS $this_duzey =>  $this_gorev_tanimi) {
                                        foreach($this_gorev_tanimi AS $ge) {
                                            $gorev = [
                                                "title" => $ge,
                                                "uid" => $s->uid, // öğrenciye ait unique 
                                                "brans" => $title,
                                                "star" => $star,
                                                "s" => $konu_sira, //konu önem sırasını belirtiyoruz.
                                                "kid" => $s->id, //hangi sınava ait bir görev olduğunu bilmek için önemli
                                                "complete" => 0,
                                                "level" => $this_duzey,
                                                "basari" => $veri['Percent'],
                                                "basari_farki" => $basari_farki,
                                                "beklenen_basari" => $beklenen_basari,
                                                "konu" => $konu,
                                                "datetime" => date("Y-m-d H:i:s",strtotime("+7 days"))
                                            ];
                                            array_push($gorevler,$gorev);
                                        }
                                        
                                    } 
                                }
                            }
                            if($duzey=="Pratisyen") {
                                $gorev_tanimlari = [
                                    //"Master" => $master_gorev_tanimlari,
                                    "Pratisyen" => $pratisyen_gorev_tanimlari,
                                    "Junior" => $junior_gorev_tanimlari
                                ];
                                $sorumlu_dersler_pattern = str_slug($title.' '. $okul_alan2);
                                if(isset($sorumlu_dersler[$sorumlu_dersler_pattern])) {
                                    foreach($gorev_tanimlari AS $this_duzey =>  $this_gorev_tanimi) {
                                        foreach($this_gorev_tanimi AS $ge) {
                                            
                                                $gorev = [
                                                    "title" => $ge,
                                                    "uid" => $s->uid, // öğrenciye ait unique 
                                                    "brans" => $title,
                                                    "star" => $star,
                                                    "s" => $konu_sira, //konu önem sırasını belirtiyoruz.
                                                    "kid" => $s->id, //hangi sınava ait bir görev olduğunu bilmek için önemli
                                                    "complete" => 0,
                                                    "level" => $this_duzey,
                                                    "basari" => $veri['Percent'],
                                                    "basari_farki" => $basari_farki,
                                                    "beklenen_basari" => $beklenen_basari,
                                                    "konu" => $konu,
                                                    "datetime" => date("Y-m-d H:i:s",strtotime("+7 days"))
                                                ];
                                                array_push($gorevler,$gorev);
                                            }
                                    } 
                                }
                            }
                            if($duzey=="Junior") {
                                $gorev_tanimlari = [
                                    "Junior" => $junior_gorev_tanimlari
                                ];
                                $sorumlu_dersler_pattern = str_slug($title.' '. $okul_alan2);
                                if(isset($sorumlu_dersler[$sorumlu_dersler_pattern])) {
                                    foreach($gorev_tanimlari AS $this_duzey =>  $this_gorev_tanimi) {
                                        foreach($this_gorev_tanimi AS $ge) {
                                            $gorev = [
                                                "title" => $ge,
                                                "uid" => $s->uid, // öğrenciye ait unique 
                                                "brans" => $title,
                                                "star" => $star,
                                                "s" => $konu_sira, //konu önem sırasını belirtiyoruz.
                                                "kid" => $s->id, //hangi sınava ait bir görev olduğunu bilmek için önemli
                                                "complete" => 0,
                                                "level" => $this_duzey,
                                                "basari" => $veri['Percent'],
                                                "basari_farki" => $basari_farki,
                                                "beklenen_basari" => $beklenen_basari,
                                                "konu" => $konu,
                                                "datetime" => date("Y-m-d H:i:s",strtotime("+7 days"))
                                            ];
                                            array_push($gorevler,$gorev);
                                        }
                                        
                                    } 
                                } 
                            }
                        } elseif($basari_farki>=10 && $basari_farki<=30) {
                            if($duzey=="Master") {
                                $gorev_tanimlari = [
                                    "Pratisyen" => $pratisyen_gorev_tanimlari
                                ];
                                $sorumlu_dersler_pattern = str_slug($title.' '. $okul_alan2);
                                if(isset($sorumlu_dersler[$sorumlu_dersler_pattern])) {
                                    foreach($gorev_tanimlari AS $this_duzey =>  $this_gorev_tanimi) {
                                        foreach($this_gorev_tanimi AS $ge) {
                                            $gorev = [
                                                "title" => $ge,
                                                "uid" => $s->uid, // öğrenciye ait unique 
                                                "brans" => $title,
                                                "star" => $star,
                                                "s" => $konu_sira, //konu önem sırasını belirtiyoruz.
                                                "kid" => $s->id, //hangi sınava ait bir görev olduğunu bilmek için önemli
                                                "complete" => 0,
                                                "level" => $this_duzey,
                                                "basari" => $veri['Percent'],
                                                "basari_farki" => $basari_farki,
                                                "beklenen_basari" => $beklenen_basari,
                                                "konu" => $konu,
                                                "datetime" => date("Y-m-d H:i:s",strtotime("+7 days"))
                                            ];
                                            array_push($gorevler,$gorev);
                                        }
                                        
                                    } 
                                } 
                            }
                            if($duzey=="Pratisyen") {
                                
                                $gorev_tanimlari = [
                                    "Junior" => $junior_gorev_tanimlari,
                                    "Pratisyen" => $pratisyen_gorev_tanimlari
                                ];
                                $sorumlu_dersler_pattern = str_slug($title.' '. $okul_alan2);
                                if(isset($sorumlu_dersler[$sorumlu_dersler_pattern])) {
                                    foreach($gorev_tanimlari AS $this_duzey =>  $this_gorev_tanimi) {
                                        foreach($this_gorev_tanimi AS $ge) {
                                            $gorev = [
                                                "title" => $ge,
                                                "uid" => $s->uid, // öğrenciye ait unique 
                                                "brans" => $title,
                                                "star" => $star,
                                                "s" => $konu_sira, //konu önem sırasını belirtiyoruz.
                                                "kid" => $s->id, //hangi sınava ait bir görev olduğunu bilmek için önemli
                                                "complete" => 0,
                                                "level" => $this_duzey,
                                                "basari" => $veri['Percent'],
                                                "basari_farki" => $basari_farki,
                                                "beklenen_basari" => $beklenen_basari,
                                                "konu" => $konu,
                                                "datetime" => date("Y-m-d H:i:s",strtotime("+7 days"))
                                            ];
                                            array_push($gorevler,$gorev);
                                        }
                                        
                                    } 
                                } 
                            }
                            if($duzey=="Junior") {
                            
                                $gorev_tanimlari = [
                                    "Junior" => $junior_gorev_tanimlari
                                ];
                                $sorumlu_dersler_pattern = str_slug($title.' '. $okul_alan2);
                                if(isset($sorumlu_dersler[$sorumlu_dersler_pattern])) {
                                    foreach($gorev_tanimlari AS $this_duzey =>  $this_gorev_tanimi) {
                                        foreach($this_gorev_tanimi AS $ge) {
                                            $gorev = [
                                                "title" => $ge,
                                                "uid" => $s->uid, // öğrenciye ait unique 
                                                "brans" => $title,
                                                "star" => $star,
                                                "s" => $konu_sira, //konu önem sırasını belirtiyoruz.
                                                "kid" => $s->id, //hangi sınava ait bir görev olduğunu bilmek için önemli
                                                "complete" => 0,
                                                "level" => $this_duzey,
                                                "basari" => $veri['Percent'],
                                                "basari_farki" => $basari_farki,
                                                "beklenen_basari" => $beklenen_basari,
                                                "konu" => $konu,
                                                "datetime" => date("Y-m-d H:i:s",strtotime("+7 days"))
                                            ];
                                            array_push($gorevler,$gorev);
                                        }
                                        
                                    } 
                                } 
                            }
                } elseif($basari_farki<=0) { 
                    print2("Mükemmel başarı");
                    //Log::debug("Mükemmel başarı");
                } else {
                            if($duzey=="Master") {
                                $gorev_tanimlari = [
                                    "Master" => $master_gorev_tanimlari
                                ];
                                $sorumlu_dersler_pattern = str_slug($title.' '. $okul_alan2);
                                if(isset($sorumlu_dersler[$sorumlu_dersler_pattern])) {
                                    foreach($gorev_tanimlari AS $this_duzey =>  $this_gorev_tanimi) {
                                        foreach($this_gorev_tanimi AS $ge) {
                                            $gorev = [
                                                "title" => $ge,
                                                "uid" => $s->uid, // öğrenciye ait unique 
                                                "brans" => $title,
                                                "star" => $star,
                                                "s" => $konu_sira, //konu önem sırasını belirtiyoruz.
                                                "kid" => $s->id, //hangi sınava ait bir görev olduğunu bilmek için önemli
                                                "complete" => 0,
                                                "level" => $this_duzey,
                                                "basari" => $veri['Percent'],
                                                "basari_farki" => $basari_farki,
                                                "beklenen_basari" => $beklenen_basari,
                                                "konu" => $konu,
                                                "datetime" => date("Y-m-d H:i:s",strtotime("+7 days"))
                                            ];
                                            array_push($gorevler,$gorev);
                                        }
                                        
                                    } 
                                } 
                            }
                            if($duzey=="Pratisyen") {
                                $gorev_tanimlari = [
                                    "Pratisyen" => $pratisyen_gorev_tanimlari
                                ];
                                $sorumlu_dersler_pattern = str_slug($title.' '. $okul_alan2);
                                if(isset($sorumlu_dersler[$sorumlu_dersler_pattern])) {
                                    foreach($gorev_tanimlari AS $this_duzey =>  $this_gorev_tanimi) {
                                        foreach($this_gorev_tanimi AS $ge) {
                                            $gorev = [
                                                "title" => $ge,
                                                "uid" => $s->uid, // öğrenciye ait unique 
                                                "brans" => $title,
                                                "star" => $star,
                                                "s" => $konu_sira, //konu önem sırasını belirtiyoruz.
                                                "kid" => $s->id, //hangi sınava ait bir görev olduğunu bilmek için önemli
                                                "complete" => 0,
                                                "level" => $this_duzey,
                                                "basari" => $veri['Percent'],
                                                "basari_farki" => $basari_farki,
                                                "beklenen_basari" => $beklenen_basari,
                                                "konu" => $konu,
                                                "datetime" => date("Y-m-d H:i:s",strtotime("+7 days"))
                                            ];
                                            array_push($gorevler,$gorev);
                                        }
                                        
                                    } 
                                } 
                            }
                            if($duzey=="Junior") {
                                $gorev_tanimlari = [
                                    "Junior" => $junior_gorev_tanimlari
                                ];
                                $sorumlu_dersler_pattern = str_slug($title.' '. $okul_alan2);
                                if(isset($sorumlu_dersler[$sorumlu_dersler_pattern])) {
                                    foreach($gorev_tanimlari AS $this_duzey =>  $this_gorev_tanimi) {
                                        foreach($this_gorev_tanimi AS $ge) {
                                            $gorev = [
                                                "title" => $ge,
                                                "uid" => $s->uid, // öğrenciye ait unique 
                                                "brans" => $title,
                                                "star" => $star,
                                                "s" => $konu_sira, //konu önem sırasını belirtiyoruz.
                                                "kid" => $s->id, //hangi sınava ait bir görev olduğunu bilmek için önemli
                                                "complete" => 0,
                                                "level" => $this_duzey,
                                                "basari" => $veri['Percent'],
                                                "basari_farki" => $basari_farki,
                                                "beklenen_basari" => $beklenen_basari,
                                                "konu" => $konu,
                                                "datetime" => date("Y-m-d H:i:s",strtotime("+7 days"))
                                            ];
                                            array_push($gorevler,$gorev);
                                        }
                                        
                                    } 
                                } 
                            }
                        }
                        //print2($gorev_tanimlari);
                        /* 
                        foreach($gorev_tanimlari AS $ge) {
                            $gorev = [
                                "title" => $ge,
                                "uid" => $s->uid, // öğrenciye ait unique 
                                "brans" => $title,
                                "star" => $star,
                                "s" => $konu_sira, //konu önem sırasını belirtiyoruz.
                                "kid" => $s->id, //hangi sınava ait bir görev olduğunu bilmek için önemli
                                "complete" => 0,
                                "level" => $levels[$title][$kazanim_adi],
                                "konu" => $kazanim_adi,
                                "datetime" => date("Y-m-d H:i:s",strtotime("+7 days"))
                            ];
                            array_push($gorevler,$gorev);
                        } 
                        */
                        
                        
                    }
                    
                }
                
            }
        // dd($gorevler);
        // print2($gorevler);
        // exit();
            
            //görevleri ekle
        // print2($levels);
        //   print2($gorevler);
            $gorev_say=0;
            $sinav_say = 0;
            foreach($gorevler AS $gorev) {
                
                $gorev_eklendi_mi = setGorev($gorev);
                    if($gorev_eklendi_mi!=null) {
                        $gorev_say++;
                    }
                    
                    //KAS sınavlarını oluştur online sınavlar şekilde oluşturulacak
                    $kas = $gorev;
                    $kas['json'] = json_encode_tr([
                        "sorular" => $taksonomi_map[$gorev['level']],
                        "adet" => $kas_sinavi_soru_sayisi
                    ]);
                
                $kas_eklendi_mi = setKas($kas);
                if($kas_eklendi_mi!=null) {
                    $sinav_say++;
                }
                
            }
        
            
    
        
        
        
        
    
        
        
        // exit();
        
            //bildirim yapıldı olarak işaretle
            //bildirimi yap
                $subject = "Yeni görevleriniz oluşturuldu";
                $text = "Sayın {$ogrenci->name} {$ogrenci->surname}, son girmiş olduğunuz sınava göre sizin için $gorev_say yeni görev ve $sinav_say KAS sınavı oluşturulmuştur.";
                echo "$text <br>";
            // print2($ogrenci);
                //exit();
                add_log($subject,"$gorev_say yeni görev ve $sinav_say KAS sınavı oluşturuldu.",$ogrenci->id);
                bildirim2($subject,$text,$ogrenci->email,$ogrenci->phone);
                $text2 = "$sinav_id ile ilgili bildirim yapıldı <br>\n";
                echo $text;
                $mail_text .= $text . " " .$text2;

            } else {
                Log::error('Analiz sonuç bilgisi bulunamadı. Sonuç -2 e alındı');
                db("sonuclar")->where("id",$s->id)->update([
                    'bildirim' => '-2'
                ]);
            } //j is array
 
                

            } catch (\Throwable $th) {
                throw $th;
                $text = "$sinav_id ile ilgili herhangi bir işlem yapılamadi \n";
                echo $text;
                $mail_text .= $text;
        
            }
            db("sonuclar")->where("id",$sinav_id)
                    ->update(['bildirim' => 1]);
            
            
    
}
print2("Analiz Cronu Çalıştı");
if($mail_text!="") {
    adminSend("Dijimind Yapay Zekası Çalıştı","Sayın Admin,
    bir takım çalışmalar yapmış bulunmaktayım ve bunların bildirimlerini aynı şu şekilde 
    öğrenciye ve veliye bilgilendirdim. Senin de haberin olsun.
    $mail_text");
}
 ?>