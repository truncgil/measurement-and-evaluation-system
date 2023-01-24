<?php 

if(isset($everyone)) {
    $everyone = true;
} else {
    $everyone = false;
}
$sorumlu_dersler = sorumlu_dersler();
if(isset($veli)) {
    if(oturumisset("ogrenci")) {
        $u = select_ogrenci();
    } else {
        $u = u();
    }
    
} else {
    if(!$everyone) {
        if(isset($ogrenci)) {
            $u = $ogrenci;
        } else {
            $u = u();
        }
    }
}

 ?>
<div class="row">
    <?php if(getisset("id")) {
        $sonuc = db("sonuclar")
        ->where("id",get("id"));
        if(!$everyone) {
            if($u->level=="Kurum") {
                $sonuc = $sonuc->whereIn("uid",$u->alias_ids);
            } else {
                $sonuc = $sonuc->where(function($query) use($u) {
                    $query = $query->orWhere("uid",$u->id);
                    $query = $query->orWhere("tc_kimlik_no",$u->email);
                });
            }
        }
        
        
        $sonuc =$sonuc->first();
     //   dd($sonuc);
        
        if($sonuc) {
          //  print2($sonuc);
            $j  = j($sonuc->analiz);

            
        } else {
            yonlendir("?t=analizlerim");
        }
        
        
         ?>
         <div class="col-12">
            <h1>{{$sonuc->title}} 
            <?php 
                $id = get("id");
                $hash = md5(env('SECRET_KEY').$id); ?>
                <div
                onclick="if (navigator.share) {
    navigator.share({
      title: '{{$u->name}} {{$u->surname}} {{$sonuc->title}} Karnesi',
      url: '{{url("karne?id=$id&hash=$hash")}}'
    }).then(() => {
      //console.log('Thanks for sharing!');
    })
    .catch(console.error);
  } else {
    // fallback
  }"
                href="" class="btn btn-primary" title="Karneyi Paylaş"><i class="fa fa-share"></i></div>

            </h1>
            <div class="float-right">
                
            </div>
         </div>
         <?php $s =  $sonuc; ?>
        {{col("col-12","")}} 
            @include("admin.inc.ogrenci-puan-siralama")
        {{_col()}}
         {{col("col-12","Genel Başarı Analizi")}}
                <div class="row">
                    @include("admin.ogrenci.analiz.genel-basari")
                </div>
         {{_col()}}
          {{col("col-12 cozumler","Çözümler ve Cevaplar",4)}} 
            <div class="row">
                @include("admin.ogrenci.analiz.cozumler")
            </div>
          {{_col()}}
        
         {{col("col-12","Eksik Konu Analizi")}}
                @include("admin.ogrenci.analiz.eksik-konu-analizi")
         {{_col()}}
         {{col("col-12 d-none","DijiRapor")}}
                @include("admin.ogrenci.analiz.dijirapor")

         {{_col()}}
         <?php 
    } else  { 
        
        
      ?>
        
        <?php 
        if(isset($ogrenciler)) { //aynı etki alanındaki tüm öğrencilerin sonuçlarını göster
            $sonuc = db("sonuclar")
            ->whereIn("uid",$ogrenciler);
        } else {
            if(!$everyone) {
                if($u->level=="Kurum") {
                    if(getisset("uid")) {
                        $u = u2(get("uid"));
                    }
                }
            
                $sonuc = db("sonuclar")
                ->where(function($query) use($u) {
                    $query = $query->orWhere("uid",$u->id);
                // $query = $query->orWhere("tc_kimlik_no",$u->email);
                });
            }
        }
       
        if(getisset("sinav")) {
            if(!empty($_GET['sinav'])) {
                $sonuc = $sonuc->whereIn("sinav_id",$_GET['sinav']);
            }
        }
        $sonuc = $sonuc->get();
        $j = [];
        $select_brans = [];
        if(getisset("brans")) {
            $select_brans = $_GET['brans'];
        }
        $select_sinif = [];
        if(getisset("sinif")) {
            $select_sinif = $_GET['sinif'];
            $sinif_ogrencileri  = [];
           // print2($select_sinif);
            foreach($select_sinif AS $this_sinif) {
                $this_ogrenciler = sinif_ogrencileri($this_sinif);
                //print2($this_ogrenciler);
                $sinif_ogrencileri = array_merge($sinif_ogrencileri,$this_ogrenciler);
            }
        }
        $select_ogrenci = [];
        if(getisset("ogrenci")) {
            $select_ogrenci = $_GET['ogrenci'];
        }
      //  print2($sinif_ogrencileri);
        foreach($sonuc AS $s) {
            $a  = j($s->analiz);
          //  $j = array_merge($j,$a);
            //print2($j); 
            
            if(!empty($sinif_ogrencileri)) {
                if(in_array($s->uid,$sinif_ogrencileri)) {
                    $ogrenci_goster = true;
                } else {
                    $ogrenci_goster = false;
                }
            } else {
                $ogrenci_goster = true;
            }
            if(!empty($select_ogrenci)) {
                if(in_array($s->uid,$select_ogrenci)) {
                    $ogrenci_goster = true;
                } else {
                    $ogrenci_goster = false;
                }
            } else {
                if(!getisset("sinif")) {
                    $ogrenci_goster = true;
                }
                
            }
            if($ogrenci_goster) {
                foreach($a AS $alan => $deger) {
                    if(!empty($select_brans)) {
                        if(in_array($alan,$select_brans)) {
                            $goster = true;
                        } else {
                            $goster = false;
                        }
                    } else {
                        $goster = true;
                    }
                    if($goster) {
                        foreach($deger AS $alt_alan => $alt_deger) {
                            if(is_integer($alt_deger) || is_float($alt_deger)) {
                                if(!isset($j[$alan][$alt_alan][$alt_deger])) $j[$alan][$alt_alan][$alt_deger] = 0;
                                $j[$alan][$alt_alan][$alt_deger] += $alt_deger;
                            } elseif(is_array($alt_deger)) {
                                
                                if(!isset($j[$alan][$alt_alan])) $j[$alan][$alt_alan] = [];
                                $j[$alan][$alt_alan] = array_merge($j[$alan][$alt_alan],$alt_deger);
                            } elseif(is_string($alt_deger)) {
                                if(!isset($j[$alan][$alt_alan][$alt_deger])) $j[$alan][$alt_alan][$alt_deger] = "";
                                $j[$alan][$alt_alan][$alt_deger] .= $alt_deger;
                            }
                        }
                        
                    }
                    
                }
            }
            
        }
       
     //  print2($j);
        ?>
        {{col("col-md-12","Analiz Özeti",2)}}
                @include("admin.ogrenci.analiz.genel-analizler")
        {{_col()}} 

        {{col("col-12","Eksik Konu Analizi")}}
            @include("admin.ogrenci.analiz.eksik-konu-analizi")
        {{_col()}}
        
        {{col("col-12 d-none","DijiRapor")}}
            @include("admin.ogrenci.analiz.dijirapor")
        {{_col()}}
    
     <?php } ?>
</div>