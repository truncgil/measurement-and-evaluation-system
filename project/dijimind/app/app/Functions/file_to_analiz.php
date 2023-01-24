<?php 
function file_to_analiz($dosya) {
    try {
        return file_to_analiz_system($dosya);
    } catch (\Throwable $th) {
        //dd($th);
        bilgi("Okumasını yaptığınız dosyada bazı mantık hataları olduğundan işlem tamamlanamamıştır. Lütfen seçilen optiği, okunan optiği, kazanım ve cevap anahtarlarını kontrol ediniz."
        
    );
    //echo "Meraklısına hata ayrıntıları: <br>".  $th->getMessage();
        dump($th);
        //exit();
    }
}
function file_to_analiz_system($dosya) {
    $error = array();
    $dosya = upload2("$dosya","sonuclar");
                //echo $dosya;
                $dosya_icerik = file_get_contents($dosya);
                $sinav = db("sinavlar")->where("id",post("sinav"))->first();
                $optik = db("optik")->where("id",post("optik"))->first();
                $optik_json = j($optik->json);
                $sinav_json = j($sinav->json);
                $sinav_kazanim = j($sinav->kazanimlar);
              //  print2($sinav_kazanim); exit();
                // $fileEndEnd = iconv( 'UTF-8', 'UTF-8',$fileEndEnd);
                
               // print_r($sinav_json);
              //  $dosya_icerik = trk($dosya_icerik);
                $dosya_icerik = explode("\n",$dosya_icerik);

               // dd($dosya_icerik);
              //  exit();
                //      echo $dosya_icerik;
                /*
                $satir = $dosya_icerik[0];
                $satir_toplam =strlen($satir);
                for($k=0;$k<$satir_toplam;$k++) {
                    echo $k . " " . trk($satir[$k]) . "<br>";
                }
                */
                $satir = 1;
                $ogrenci_bilgi_dizi = array();
                $ogrenci_cevap_dizi = array();

                foreach($dosya_icerik AS $di) {
                    $ok = 0;
                   
                 //   $di = str_replace(" ","*",$di);
                
                    foreach($optik_json['alan'] AS $optik_alan) {
                        $bas = $optik_json['bas'][$ok] - 1;
                        $son = $optik_json['son'][$ok] - 1;
                       // echo "$bas - $son :: <br>" ; 
                       //echo $di;
                       if($son-$bas==0) {
                            $miktar = 1;
                        } else {
                            $miktar = $son-$bas;
                        }
                       $optik_alan = trim($optik_alan);
                       
                       $optik_alan_slug = str_slug($optik_alan);

                       if($optik_alan=="Öğrenci Adı") {
                            $ogrenci_bilgi_dizi[$satir][$optik_alan_slug] = turkce(substr($di,$bas,$miktar));

                       } else {
                           if($optik_alan_slug=="tc-kimlik-no" || $optik_alan_slug=="kitapcik") {
                                $ogrenci_bilgi_dizi[$satir][$optik_alan_slug] = substr($di,$bas,$miktar);
                           } else {
                                $ogrenci_cevap_dizi[$satir][$optik_alan_slug] = substr($di,$bas,$miktar);
                           }
                            
                       }
                        
                        $ok++;
                    }
                    //print_r($ogrenci_cevap_dizi);
                  $satir++;
                }

               // print2($optik_json);
              //  print2($sinav_json); 
               // print2($sinav_kazanim);
                $b_kitapcik_map = array();
                foreach($sinav_kazanim AS $alan=>$deger) {
                    if(strpos($alan,"b_soru_no")!==false) {
                        $sira = preg_replace('/[^0-9]/', '', $alan);
                        $key = str_replace("_b_soru_no_","",$alan);
                        $key = str_replace($sira,"",$key);
                        $b_kitapcik_map[$key][$deger] = $sira;
                      //  echo "$key $sira $deger <br>";  
                    }
                }
                $c_kitapcik_map = array();
                foreach($sinav_kazanim AS $alan=>$deger) {
                    if(strpos($alan,"c_soru_no")!==false) {
                        $sira = preg_replace('/[^0-9]/', '', $alan);
                        $key = str_replace("_c_soru_no_","",$alan);
                        $key = str_replace($sira,"",$key);
                        $c_kitapcik_map[$key][$deger] = $sira;
                      //  echo "$key $sira $deger <br>";  
                    }
                }
                $d_kitapcik_map = array();
                foreach($sinav_kazanim AS $alan=>$deger) {
                    if(strpos($alan,"d_soru_no")!==false) {
                        $sira = preg_replace('/[^0-9]/', '', $alan);
                        $key = str_replace("_d_soru_no_","",$alan);
                        $key = str_replace($sira,"",$key);
                        $c_kitapcik_map[$key][$deger] = $sira;
                      //  echo "$key $sira $deger <br>";  
                    }
                }
                //dd($b_kitapcik_map);
               // print2($b_kitapcik_map);
         /*
         if($ogrenci_bilgi_dizi[1]['kitapcik']=="") {
            echo "Kitapçık boş geldi. Lütfen optik işaretlemeyi kontrol ediniz veya txt dosyasındaki boş kayıtları düzeltiniz. Öğrencilerin bilgileri şu şekilde: ";
            
           // print2($ogrenci_bilgi_dizi);
            exit(); 
         }
         */
        
           
           
           /*
           sınav kazanım array örneği
            [fizik-tyt_kazanim_2] => Elektriksel alan
            [fizik-tyt_tak_2] => A2
            [fizik-tyt_c_soru_no_2] => 
            [fizik-tyt_d_soru_no_2] => 
            [fizik-tyt_b_soru_no_3] => 7
            [fizik-tyt_cevap_3] => Array
                (
                    [0] => C
                )

           */
          // print2($ogrenci_bilgi_dizi);
           $sonuc_analiz = array();
           $ogrenci_sira = 1;
                foreach($ogrenci_cevap_dizi AS $alan =>  $cevaplar) {
               //     print2($alan);
              // print2($ogrenci_bilgi_dizi[$ogrenci_sira]);
                    $kitapcik = trim($ogrenci_bilgi_dizi[$ogrenci_sira]['kitapcik']);
                    $ogrenci_adi = trim($ogrenci_bilgi_dizi[$ogrenci_sira]['ogrenci-adi']);
                    $tckimlik = trim($ogrenci_bilgi_dizi[$ogrenci_sira]['tc-kimlik-no']);
               //    echo $ogrenci_adi . "<br>"; 
                    
                    if(trim($tckimlik)!="") {
                        $hash = trim($tckimlik);
                    } else {
                        $hash = trim($ogrenci_adi);
                    }
                    if($hash!="") {
                        
                    
                    $sonuc_analiz[$hash]['kitapcik'] = $kitapcik;
                    $sonuc_analiz[$hash]['ogrenci-adi'] = $ogrenci_adi;
                    $sonuc_analiz[$hash]['tc-kimlik-no'] = $tckimlik;
                    
                  //  echo($hash); 
                    $netler = array();
                    //print2($cevaplar); exit();
                    foreach($cevaplar AS $cevap_alan => $cevap_str) {
                        $cevap_str = str_split($cevap_str);
                        $ders_bilgi = db("contents")->where("slug",$cevap_alan)->first();
                        $ders_title = slug_to_title($cevap_alan);
                        
                        
                     //  print2($cevap_alan);
                   //     print2($cevap_str);
                   /*
                        if(isset($onceki_ust_brans)) {
                            if($ders_bilgi->title2!=$onceki_ust_brans) {
                                $soru_no = 1;
                            }
                        } else {
                            $soru_no = 1;
                        }
                     */
                    
                    $soru_no = 1;   
                        
                        if(!isset($sonuc_analiz[$hash]['analiz'][$cevap_alan])) {
                            $sonuc_analiz[$hash]['analiz'][$cevap_alan] = array();
                        }
                            
                        
                        $bu_soru = $sonuc_analiz[$hash]['analiz'][$cevap_alan];
                        
                        if(!isset($bu_soru['dogru'])) $bu_soru['dogru'] = 0;
                        if(!isset($bu_soru['yanlis'])) $bu_soru['yanlis'] = 0;
                        if(!isset($bu_soru['bos'])) $bu_soru['bos'] = 0;
                        if(!isset($bu_soru['net'])) $bu_soru['net'] = 0;
                        if(!isset($bu_soru['kazanim-dogru'])) $bu_soru['kazanim-dogru'] = array();
                        if(!isset($bu_soru['kazanim-yanlis'])) $bu_soru['kazanim-yanlis'] = array();
                        if(!isset($bu_soru['kazanim-bos'])) $bu_soru['kazanim-bos'] = array();
                        if(!isset($bu_soru['tak-dogru'])) $bu_soru['tak-dogru'] = array();
                        if(!isset($bu_soru['tak-yanlis'])) $bu_soru['tak-yanlis'] = array();
                        if(!isset($bu_soru['tak-bos'])) $bu_soru['tak-bos'] = array();
                        if(!isset($bu_soru['cevaplar'])) $bu_soru['cevaplar'] = "";

                        foreach($cevap_str AS $ogrenci_cevap) {

                           // $onceki_ust_brans = $ders_bilgi->title2;
                         //   echo "soru_no ".$soru_no . "<br>";
                            $kitapcik_soru_no = $soru_no; // a kitapçığında soru no sırasıyla


                           
                            $brans_title = slug_to_title($cevap_alan); 
                            
                            $alt_brans_ne = array_search($brans_title,$sinav_kazanim); //alt branşın var olup olmadığını search ediyoruz. varsa o anahtar değeri dönüyor 
                            
                            
                            if(trim($kitapcik)=="") {
                               // echo ;
                                array_push($error,"$hash Kitapçık boş geldi, lütfen optik işaretlemeyi kontrol ediniz.");
                                continue;
                                //die("Kitapçık boş geldi, lütfen optik işaretlemeyi kontrol ediniz.");
                            } else {
                            //    //Log::debug($kitapcik);
                                if($kitapcik!="A") {
                                   //  echo $kitapcik;
                                      $kitapcik_slug = str_slug($kitapcik);
                                      //$soru_no_pattern = $cevap_alan."_" . $kitapcik_slug . "_soru_no_".$soru_no;  //fizik-tyt_c_soru_no_1
                                      
                                      if($kitapcik=="B") {
                                        if(isset($b_kitapcik_map[$cevap_alan][$soru_no]))
                                            $kitapcik_soru_no = $b_kitapcik_map[$cevap_alan][$soru_no];
                                      }
                                      if($kitapcik=="C") {
                                        if(isset($c_kitapcik_map[$cevap_alan][$soru_no]))
                                            $kitapcik_soru_no = $c_kitapcik_map[$cevap_alan][$soru_no];
                                      }
                                      if($kitapcik=="D") {
                                        if(isset($d_kitapcik_map[$cevap_alan][$soru_no]))
                                            $kitapcik_soru_no = $d_kitapcik_map[$cevap_alan][$soru_no];
                                      }

                                //     echo "$soru_no -> $soru_no_pattern -> $kitapcik_soru_no <br>";
                                  }
                            }
                            
                            if($alt_brans_ne!="") { // varsa o satırın cevabına erişmemiz gerekli onun için replace ediyoruz.
                              //  echo "bas alt_brans $alt_brans_ne <br>";
                                $soru_no_replace = preg_replace('/[^0-9]/', '', $alt_brans_ne);  
                           //     echo "soru_no_replace $soru_no_replace <br>";
                                $alt_brans_ne = str_replace("_".$soru_no_replace,"_".$soru_no,$alt_brans_ne);
                                
                                $soru_pattern = str_replace("_alt_brans_","_cevap_",$alt_brans_ne);
                                $kazanim_pattern = str_replace("_alt_brans_","_kazanim_",$alt_brans_ne);
                                $tak_pattern = str_replace("_alt_brans_","_tak_",$alt_brans_ne);
                             //   echo "alt_brans $alt_brans_ne <br>";
                             //   echo $soru_pattern . "<br>";
                                if($kitapcik!="A") {
                                    echo "kitapcik $kitapcik <br>";
                                    $kitapcik_slug = str_slug($kitapcik);
                                   
                                    $soru_no_pattern = str_replace("_alt_brans_","_".$kitapcik_slug."_soru_no_",$alt_brans_ne);
                                    echo "soru_no_pattern $soru_no_pattern <br>"; exit();
                                 //   $soru_no_pattern = $cevap_alan."_" . $kitapcik_slug . "_soru_no_".$soru_no; 
                                    if(isset($sinav_kazanim[$soru_no_pattern])) {
                                        $kitapcik_soru_no = $sinav_kazanim[$soru_no_pattern];
                                    } else {
                                        array_push($error,"$soru_no_pattern bulunamadı");
                                    }
                                    
                                }
                            } else {
                                
                                $soru_pattern = $cevap_alan . "_cevap_" . $kitapcik_soru_no;
                                $kazanim_pattern = $cevap_alan . "_kazanim_" . $kitapcik_soru_no; //fizik-tyt_kazanim_2
                                $tak_pattern = $cevap_alan . "_tak_" . $kitapcik_soru_no; // [fizik-tyt_tak_2] => A2
                             //  echo "$soru_pattern <br>";
                            }

                            $tak = "-";
                            //Log::debug($soru_pattern);
                            //Log::debug($sinav_kazanim);

                            if(isset($sinav_kazanim[$soru_pattern])) {
                                $dogru_cevap = $sinav_kazanim[$soru_pattern];
                                
                                $kazanim = $sinav_kazanim[$kazanim_pattern];
                                

                                if(isset($sinav_kazanim[$tak_pattern])) {
                                    $tak = $sinav_kazanim[$tak_pattern];
                                }
                                
                            } else {
                              //  print2($sinav_kazanim);
                                $text = "$soru_pattern branşı optik üzerinde bulunamadı";
                                if(!in_array($text,$error)) {
                                    array_push($error,$text);
                                }
                                
                               // continue;
                            }
                            
                          
                            
                            
                            
        //                    print2($ogrenci_cevap);
                            if(trim($ogrenci_cevap)!="") {

                                if(in_array("X", $dogru_cevap)) { //eğer soru iptal ise doğru yap
                                    
                                    array_push($bu_soru['tak-dogru'],$tak);
                                    array_push($bu_soru['kazanim-dogru'],$kazanim);
                                    $bu_soru['cevaplar'] .= $ogrenci_cevap;
                                    $bu_soru['dogru']++;
                                    
                                } else {

                                    if(in_array($ogrenci_cevap,$dogru_cevap)) {
                                   
                                        array_push($bu_soru['tak-dogru'],$tak);
                                        array_push($bu_soru['kazanim-dogru'],$kazanim);
                                        $bu_soru['cevaplar'] .= $ogrenci_cevap;
                                        $bu_soru['dogru']++;
                                    } else {
                                      //  echo "$ogrenci_cevap - " . implode(",",$dogru_cevap) . "<br>";
                                        array_push($bu_soru['tak-yanlis'],$tak);
                                        array_push($bu_soru['kazanim-yanlis'],$kazanim);
                                        $bu_soru['cevaplar'] .= strtolower($ogrenci_cevap);
                                        $bu_soru['yanlis']++;
                                    }
                                }

                                
                                
                            } else {

                                array_push($bu_soru['tak-bos'],$tak);
                                array_push($bu_soru['kazanim-bos'],$kazanim);
                                $bu_soru['cevaplar'] .= "*";
                                $bu_soru['bos']++;
                            }
                          //  echo "$soru_no arrtı <br>";
                            $soru_no++;
                        }
                        //if(!isset($sonuc_analiz[$hash][$cevap_alan][$soru_no])) $sonuc_analiz[$hash][$cevap_alan][$soru_no] = array();
                        
                        
                        
                        
                        
                        
                        
                        if($sinav_json['net']!=0) { //eğer doğru yanlışı götürmüyorsa net = doğru götürüyorsa belirlenen sayı kadar analiz yapılmalı
                            $bu_soru['net'] = $bu_soru['dogru'] - $bu_soru['yanlis']/$sinav_json['net'];
                        } else {
                            $bu_soru['net'] = $bu_soru['dogru'];
                        }
                        $netler[$ders_title] = $bu_soru['net'];
                        if($bu_soru['dogru']==0 && $bu_soru['yanlis']==0) { // bunu ne için yaptığımız hususunda bir bilgim yok ancak bir sorun oluşmu ki ondan dolayı böyle bir şey olmuş bir sorun varsa buraya bakalım tekrardan
                       //     unset($sonuc_analiz[$hash]['analiz'][$cevap_alan]);
                       
                            
                                $sonuc_analiz[$hash]['analiz'][$cevap_alan] = $bu_soru;
                        } else {
                           
                                $sonuc_analiz[$hash]['analiz'][$cevap_alan] = $bu_soru;
                           
                            
                        }
                        
                        
                    //    $soru_no++;
                        
                        
                     //   print2($sinav_kazanim[$soru_anahtar]);

                        //analiz yapalım 
                    }
                   // print2($netler);
                    try {
                        $puan = puan($netler);
                    } catch (\Throwable $th) {
                        $puan = 0;
                    }


                    /*
                    Hemen bir algoritma: Din kültürü yaptı ise otomatik din kültürü, seçmeli felsefe işaretlemiş olsa bile ypkısayılacak  hesaplamaya katılmayacak. 2. Din kültürü ve seçmeli felsefe işaretlemişse : sadece din kültürü kabul edilecek. Din kültürü boş ve seçmeli felsefe işaretlemişse ancak seçmeli felsefe hesaba katılacak.
                    */
                    $felsefe = null;
                    $din = null;

                    if(isset($sonuc_analiz[$hash]['analiz']["felsefe-secmeli-tyt"])) 
                        $felsefe = $sonuc_analiz[$hash]['analiz']["felsefe-secmeli-tyt"];
                        
                    if(isset($sonuc_analiz[$hash]['analiz']["din-kulturu-tyt"]))
                        $din = $sonuc_analiz[$hash]['analiz']["din-kulturu-tyt"];

                    if($din==null) {
                        $din = @$sonuc_analiz[$hash]['analiz']["dkab-ayt"];
                    }
                    if($felsefe==null) {
                        $felsefe = @$sonuc_analiz[$hash]['analiz']["felsefe-secmeli-ayt"];
                        if($felsefe==null) {
                            $felsefe = @$sonuc_analiz[$hash]['analiz']["felsefe-ayt"];
                        }
                    }
                    $din_isaretli = false;
                    if(!is_null($din)) {
                        if($din['dogru']!=0 || $din['yanlis']!=0) { //din işaretli ise koşulsuz şartsız din geçerli
                            $din_isaretli = true;
                            unset($sonuc_analiz[$hash]['analiz']["felsefe-secmeli-tyt"]);
                            unset($netler['FELSEFE SEÇMELİ (TYT)']);
                        }
                    }
                    
                    if(!$din_isaretli) {

                        if(!is_null($felsefe)) {

                            if($felsefe['dogru']!=0 || $felsefe['yanlis']!=0) { // din işaretli değil felsefe işaretli ise
                                unset($sonuc_analiz[$hash]['analiz']["din-kulturu-tyt"]);
                                unset($netler['DİN KÜLTÜRÜ (TYT)']);
                            } else { //din de felsefe de işaretli değil ise din geçerli
                                unset($sonuc_analiz[$hash]['analiz']["felsefe-secmeli-tyt"]);
                                unset($netler['FELSEFE SEÇMELİ (TYT)']);
                            
                            }
                        }
                        
                    }


                    $sonuc_analiz[$hash]['puan'] = $puan;
                    $sonuc_analiz[$hash]['netler'] = $netler;
                   // $ogrenci_bilgi_dizi[$ogrenci_sira]['netler'] = $netler;
                   // print2($sonuc_analiz);
                    $ogrenci_sira++;
                }
            }
           // print2($netler); exit();
                
                
                
                
             //  print2($sonuc_analiz); exit();
             if(count($error)==0) {
                 bilgi("Optikte herhangi bir hata yakalanmadan başarılı bir şekilde okunması sağlandı.");
             } else {
                 bilgi("Bir takım hatalar mevcut. Aşağıdaki hataları gözden geçiriniz. Hatalar şu şekilde: ","warning");
                foreach($error AS $er) {
                    bilgi($er,"danger");
                }
             }
             
               return $sonuc_analiz;
}
