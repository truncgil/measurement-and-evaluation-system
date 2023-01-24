<?php 
function avg($array) {
    $sum = 0;
    $say = count($array);
    foreach($array AS $a) {
        $sum += $a;
    }
    if($say!=0) {
        return $sum/$say;
    } else {
        return $sum;
    }
    
}
function soru_sayisi($ids="") {
    if($ids=="") {
        $sorgu = db("sonuclar")->where("uid",u()->id)->get();
    } else {
        $sorgu = db("sonuclar")->whereIn("uid",$ids)->get();
    }
    
    $dizi = [];
    $dizi['dogru'] = 0;
    $dizi['yanlis'] = 0;
    $dizi['bos'] = 0;
    $dizi['toplam'] = 0;
    $dizi['Junior'] = 0;
    $dizi['Pratisyen'] = 0;
    $dizi['Master'] = 0;
    $dizi['ay_dogru'] = [];
    $dizi['ay_yanlis'] = [];
    $dizi['ay_bos'] = [];
    $dizi['puanlar'] = [];
    $dizi['puanlar_tyt'] = [];
    $dizi['puanlar_ayt_say'] = [];
    $dizi['puanlar_ayt_soz'] = [];
    $dizi['puanlar_ayt_ea'] = [];
    $dizi['puanlar_lgs'] = [];
    $dizi['siralama_tyt'] = [];
    $dizi['siralama_ayt_say'] = [];
    $dizi['siralama_ayt_soz'] = [];
    $dizi['siralama_ayt_ea'] = [];
    $dizi['siralama_lgs'] = [];
    $dizi['siralama'] = [];
    $dizi['puan_ort'] = 0;
    $dizi['son_siralama'] = 0;
    foreach($sorgu AS $s) {
        $j = j($s->analiz);
        //print2($j);
        foreach($j AS $alan) {
            $dizi['dogru'] += $alan['dogru'];
            $dizi['yanlis'] += $alan['yanlis'];
            $dizi['bos'] += $alan['bos'];
            $dizi['toplam'] += $alan['dogru'] + $alan['yanlis'] + $alan['bos'];
            if($s->tyt!="") {
                $dizi['puanlar'][$s->title] = $s->tyt;
                $dizi['puanlar_tyt'][$s->title] = $s->tyt;
                $dizi['siralama'][$s->title] = siralama($s->tyt);
                $dizi['siralama_tyt'][$s->title] = siralama($s->tyt);

                $dizi['son_siralama'] = siralama($s->tyt);
              //  echo count($dizi['puanlar']);
              if(count($dizi['puanlar'])>0) {
                $dizi['puan_ort'] += $s->tyt / count($dizi['puanlar']); 
              }
                
            } elseif($s->lgs!="") {
                $dizi['puanlar'][$s->title] = $s->lgs;
                $dizi['puanlar_lgs'][$s->title] = $s->lgs;
                $dizi['siralama'][$s->title] = siralama($s->lgs);
                $dizi['siralama_lgs'][$s->title] = siralama($s->lgs);
                $dizi['son_siralama'] = siralama($s->lgs);
                //  echo count($dizi['puanlar']);
                if(count($dizi['puanlar'])>0) {
                    $dizi['puan_ort'] += $s->lgs / count($dizi['puanlar']); 
                }
            }
            if(strpos($s->title,"AYT") !== false) {
                if((int) $s->yks_say != 0) {
                    $dizi['siralama_ayt_say'][$s->title] = siralama($s->yks_say,"yks_say");
                    $dizi['puanlar_ayt_say'][$s->title] =$s->yks_say;
                }
                if((int) $s->yks_soz != 0) {
                    $dizi['siralama_ayt_soz'][$s->title] = siralama($s->yks_soz,"yks_soz");
                    $dizi['puanlar_ayt_soz'][$s->title] =$s->yks_soz;
                }
                if((int) $s->yks_ea != 0) {
                    $dizi['siralama_ayt_ea'][$s->title] = siralama($s->yks_ea,"yks_ea");
                    $dizi['puanlar_ayt_ea'][$s->title] =$s->yks_ea;
                }
                
            }
            $this_ay = date("m/y",strtotime($s->created_at));
            if(!isset($dizi['ay_dogru'][$this_ay])) $dizi['ay_dogru'][$this_ay] = 0;
            if(!isset($dizi['ay_yanlis'][$this_ay])) $dizi['ay_yanlis'][$this_ay] = 0;
            if(!isset($dizi['ay_bos'][$this_ay])) $dizi['ay_bos'][$this_ay] = 0;
            $dizi['ay_dogru'][$this_ay]+=$alan['dogru'];
            $dizi['ay_yanlis'][$this_ay]+=$alan['yanlis'];
            $dizi['ay_bos'][$this_ay]+=$alan['bos'];
            if(isset($alan['tak-dogru'])) {
                foreach($alan['tak-dogru'] AS $dogru) {
                    $tak = taksonomi_to_level($dogru);
                    if(isset($dizi[$tak])) {
                        $dizi[$tak]++;
                    }
                    
                }
            }
            
           // $dizi['net'] += $alan['net'];
        }
        
    }
    $dizi['puan_ort'] = avg($dizi['puanlar']);
    if($dizi['puan_ort']==0) $dizi['puan_ort'] = "-";
    return $dizi;
} ?>