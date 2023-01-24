
<script>
    $(function(){
        $(".konu-baslik").on("click",function(){
            var konu = $(this).attr("konu");
            var selector = $(this).attr("selector");
            var brans = $(this).attr("brans");
            $(selector).toggleClass("d-none");
            /*
            $(selector).html("{{e2("Lütfen bekleyiniz...")}}");
            $.post('?ajax=konu-dijirapor',{
                konu : konu,
                brans : brans,
                json : $("#dizi4").html(),
                _token : "{{csrf_token()}}"
            },function(d){
                $(selector).html(d);
            });
            */
        });
    });
</script>
<style>
    .konu-baslik {
        cursor:pointer;
    }
</style>
<?php 
$kat_sayi = kat_sayi();
$kazanim_to_konu = kazanim_to_konu();
$konu_mapping = konu_mapping($u);

//print2($konu_mapping); 

if(sinav_type($u->sinif)=="LGS") {
    $bos_katsayi = 0.33;
    $dogru_katsayi = 1.33;
    $bos_dogru_katsayi = 1;
} else {
    $bos_katsayi = 0.25;
    $dogru_katsayi = 1.25;
    $bos_dogru_katsayi = 1;
}
$soru_sayilari = [];

$dizi = [];
$dizi2 = []; //taksonomi dizisi

foreach($j AS $alan => $deger) {
    if(!isset($ogrenciler)) {
        //kurum analizlerinin dışındaki sorgularda kişisel analizlerde ilgisiz dersleri gösterme
        $sorumlu_ders_pattern = $alan . '-' . str_slug($u->alan);
        if(!isset($sorumlu_dersler[$sorumlu_ders_pattern])) {
            continue;
        }
    }
    
    $this_katsayi = 1;
    if(isset($kat_sayi[$alan])) $this_katsayi = $kat_sayi[$alan];
    $brans_title = slug_to_title($alan);
    
    $k = 0;
    foreach($deger['kazanim-dogru'] AS $kd) {
        $kd = trim($kd);
        $this_konu = "Tanımsız";

        if(isset($kazanim_to_konu[$brans_title][$kd])) $this_konu = $kazanim_to_konu[$brans_title][$kd];
        if(!isset($dizi[$alan][$this_konu][$kd]['toplam'])) $dizi[$alan][$this_konu][$kd]['toplam'] = 0;
        if(!isset($dizi[$alan][$this_konu][$kd]['dogru'])) $dizi[$alan][$this_konu][$kd]['dogru'] = 0;
        if(!isset($dizi[$alan][$this_konu][$kd]['yanlis'])) $dizi[$alan][$this_konu][$kd]['yanlis'] = 0;
        if(!isset($dizi[$alan][$this_konu][$kd]['bos'])) $dizi[$alan][$this_konu][$kd]['bos'] = 0;
        if(!isset($dizi[$alan][$this_konu][$kd]['bos_puan'])) $dizi[$alan][$this_konu][$kd]['bos_puan'] = 0;
        if(!isset($dizi[$alan][$this_konu][$kd]['dogru_puan'])) $dizi[$alan][$this_konu][$kd]['dogru_puan'] = 0;
        if(!isset($dizi[$alan][$this_konu][$kd]['db_puan'])) $dizi[$alan][$this_konu][$kd]['db_puan'] = 0;

        $dizi[$alan][$this_konu][$kd]['dogru']++;
        $dizi[$alan][$this_konu][$kd]['toplam']++;

        $level = taksonomi_to_level($deger['tak-dogru'][$k]);
        $tanim = explode(",","Doğru,Yanlış,Boş");
        foreach($tanim AS $t) {
            if(!isset($dizi2[$alan][$this_konu][$level][$t])) {
                $dizi2[$alan][$this_konu][$level][$t] = 0;
            }
        }
        $dizi2[$alan][$this_konu][$level]['Doğru']++;

        $k++;
    }
    $k = 0;
    foreach($deger['kazanim-yanlis'] AS $kd) {
        $kd = trim($kd);
        if(isset($kazanim_to_konu[$brans_title][$kd])) $this_konu = $kazanim_to_konu[$brans_title][$kd];
        if(!isset($dizi[$alan][$this_konu][$kd]['toplam'])) $dizi[$alan][$this_konu][$kd]['toplam'] = 0;
        if(!isset($dizi[$alan][$this_konu][$kd]['dogru'])) $dizi[$alan][$this_konu][$kd]['dogru'] = 0;
        if(!isset($dizi[$alan][$this_konu][$kd]['yanlis'])) $dizi[$alan][$this_konu][$kd]['yanlis'] = 0;
        if(!isset($dizi[$alan][$this_konu][$kd]['bos'])) $dizi[$alan][$this_konu][$kd]['bos'] = 0;
        if(!isset($dizi[$alan][$this_konu][$kd]['bos_puan'])) $dizi[$alan][$this_konu][$kd]['bos_puan'] = 0;
        if(!isset($dizi[$alan][$this_konu][$kd]['dogru_puan'])) $dizi[$alan][$this_konu][$kd]['dogru_puan'] = 0;
        if(!isset($dizi[$alan][$this_konu][$kd]['db_puan'])) $dizi[$alan][$this_konu][$kd]['db_puan'] = 0;
        $dizi[$alan][$this_konu][$kd]['yanlis']++;
        $dizi[$alan][$this_konu][$kd]['toplam']++;

        $level = taksonomi_to_level($deger['tak-yanlis'][$k]);
        $tanim = explode(",","Doğru,Yanlış,Boş");
        foreach($tanim AS $t) {
            if(!isset($dizi2[$alan][$this_konu][$level][$t])) {
                $dizi2[$alan][$this_konu][$level][$t] = 0;
            }
        }
        $dizi2[$alan][$this_konu][$level]['Yanlış']++;
        
        $k++;
        
        $dizi[$alan][$this_konu][$kd]['bos_puan'] +=   $this_katsayi * $bos_katsayi;
    //    $dizi[$alan][$kd]['dogru_puan'] +=   $dogru_katsayi *  $this_katsayi;
  //      $dizi[$alan][$kd]['db_puan'] +=  $dizi[$alan][$kd]['bos_puan'] + $dizi[$alan][$kd]['dogru_puan'];
    }
    $k = 0;
    foreach($deger['kazanim-bos'] AS $kd) {
        $kd = trim($kd);
        if(isset($kazanim_to_konu[$brans_title][$kd])) $this_konu = $kazanim_to_konu[$brans_title][$kd];
        if(!isset($dizi[$alan][$this_konu][$kd]['toplam'])) $dizi[$alan][$this_konu][$kd]['toplam'] = 0;
        if(!isset($dizi[$alan][$this_konu][$kd]['dogru'])) $dizi[$alan][$this_konu][$kd]['dogru'] = 0;
        if(!isset($dizi[$alan][$this_konu][$kd]['yanlis'])) $dizi[$alan][$this_konu][$kd]['yanlis'] = 0;
        if(!isset($dizi[$alan][$this_konu][$kd]['bos'])) $dizi[$alan][$this_konu][$kd]['bos'] = 0;
        if(!isset($dizi[$alan][$this_konu][$kd]['bos_puan'])) $dizi[$alan][$this_konu][$kd]['bos_puan'] = 0;
        if(!isset($dizi[$alan][$this_konu][$kd]['dogru_puan'])) $dizi[$alan][$this_konu][$kd]['dogru_puan'] = 0;
        if(!isset($dizi[$alan][$this_konu][$kd]['db_puan'])) $dizi[$alan][$this_konu][$kd]['db_puan'] = 0;
        $dizi[$alan][$this_konu][$kd]['dogru_puan'] += $dogru_katsayi * $this_katsayi;
      //  $dizi[$alan][$kd]['db_puan'] += $dogru_katsayi * $this_katsayi;
        $dizi[$alan][$this_konu][$kd]['bos']++;
        $dizi[$alan][$this_konu][$kd]['toplam']++;

        $level = taksonomi_to_level($deger['tak-bos'][$k]);
        $tanim = explode(",","Doğru,Yanlış,Boş");
        foreach($tanim AS $t) {
            if(!isset($dizi2[$alan][$this_konu][$level][$t])) {
                $dizi2[$alan][$this_konu][$level][$t] = 0;
            }
        }
        $dizi2[$alan][$this_konu][$level]['Boş']++;
        
        $k++;
    }
   
}

//print2($dizi);
//exit();
?>
<div class="row">
    <select name="ders-sec" id="eksik-konu-ders-sec" class="form-control d-none">
        <option value="">{{e2("TÜMÜ")}}</option>
        <?php foreach($j AS $alan => $deger)  { 
            $title = slug_to_title($alan);
          ?>
         <option value="{{$title}}">{{$title}}</option> 
         <?php } ?>
    </select>
    <div class="col-12">

    <div id="accordion2" role="tablist" aria-multiselectable="true">
        <?php 
        foreach($dizi AS $alan => $deger)  { 
            $title = slug_to_title($alan);
            $slug_brans = $alan;
            $this_katsayi = 1;
            if(isset($kat_sayi[$alan])) $this_katsayi = $kat_sayi[$alan];
            $graph_percent = [];
            $hedefler = [];
            
            
          ?>
         <div class="block block-bordered block-rounded mb-2">
                 <div class="block-header" role="tab" id="accordion2_h1">
                     <a class="font-w600" data-toggle="collapse" data-parent="#accordion2" href="#accordion{{$alan}}" aria-expanded="true" aria-controls="accordion2_q1">{{$title}}</a>
                 </div>
                 <div id="accordion{{$alan}}" class="collapse " role="tabpanel" aria-labelledby="accordion2_h1">
                    <div class="block-content">
                        <div class="row">
                            <?php // print2($deger); ?>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 order-1">
                                        
                                    </div>
                                <div class="col-12 order-2">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover">
                                            <tr>
                                               
                                                <th>{{e2("Soru Sayısı")}}</th>
                                                <th>{{e2("Doğru")}}</th>
                                                <th>{{e2("Yanlış")}}</th>
                                                <th>{{e2("Boş")}}</th>
                                                <th>{{e2("Net")}}</th>
                                                <th>{{e2("Yanlışlar Boş Olsaydı")}}</th>
                                                <th>{{e2("Yanlışlar ve Boşlar Doğru Olsaydı")}}</th>
                                            </tr>
                                            <tr class="{{$alan}}_tablo_header"></tr>
                                        </table>
                                        <div class="{{$alan}}_dijirapor_clone mb-10"></div>
                                        <script>

                                            $(function(){
                                                $(".{{$alan}}_dijirapor_clone").html($(".{{$alan}}_dijirapor").html());
                                                $(".{{$alan}}_tablo_header").html($(".{{$alan}}_tablo_footer").html());
                                            });
                                        </script>
                                       
                                        <table class="table table-bordered table-striped table-hover">
                                            <tr>
                                                <th>{{e2("Konu")}}</th>
                                                <th>{{e2("Soru Sayısı")}}</th>
                                                <th>{{e2("Doğru")}}</th>
                                                <th>{{e2("Yanlış")}}</th>
                                                <th>{{e2("Boş")}}</th>
                                                <th>{{e2("Net")}}</th>
                                                <th>{{e2("Yanlışlar Boş Olsaydı")}}</th>
                                                <th>{{e2("Yanlışlar ve Boşlar Doğru Olsaydı")}}</th>
                                            </tr>
                                            <?php 
                                            $gtoplam = 0;
                                            $dogru_toplam = 0;
                                            $yanlis_toplam = 0;
                                            $bos_toplam = 0;
                                            $yanlislar_bos_olsaydi_toplam = 0;
                                            $yanlislar_boslar_dogru_bos_olsaydi_toplam = 0;

                                            $net_bolum = 4;
                                            if(sinav_type($u->sinif)=="LGS") {
                                                $net_bolum = 3;
                                            }
                                            
                                            foreach($deger AS $konu => $kazanim)  {
                                                $toplam_konu_dogru = 0;
                                                $toplam_konu_yanlis = 0;
                                                $toplam_konu = 0;
                                                ?>
                                                <tr>
                                                    <td colspan="7" konu="{{$konu}}" brans="{{$alan}}" selector=".{{$alan}}{{str_slug($konu)}}" class="text-danger konu-baslik">
                                                        <i class="fa fa-folder"></i> 
                                                        <strong>{{$konu}} </strong>
                                                    </td>
                                                </tr>
                                                <tr class="{{$alan}}{{str_slug($konu)}} d-none">
                                                    <td colspan="7">
                                                    <?php 
                                                    $dyb_dizi = $dizi2[$alan][$konu];
                                                    foreach($dyb_dizi AS $tak_name =>  $dyb) {
                                                         ?>
                                                         {{$tak_name}}
                                                         @include("admin.ogrenci.analiz.dyb-progress")
                                                         <?php 
                                                    } ?>
                                                    </td>
                                                </tr>
                                                <?php 
                                               // dd($kazanim);
                                                foreach($kazanim AS $kazanim_adi => $deger) { 
                                                    $gtoplam += $deger['toplam'];
                                                    $dogru_toplam +=  $deger['dogru'];
                                                    $yanlis_toplam +=  $deger['yanlis'];
                                                    $bos_toplam +=  $deger['bos'];
                                                
                                                    $yanlislar_bos_olsaydi = $deger['yanlis'] * $bos_katsayi * $this_katsayi;
                                                    $yanlislar_boslar_dogru_bos_olsaydi = $deger['bos']  * $this_katsayi + $deger['yanlis'] * $dogru_katsayi * $this_katsayi;
                                                    $yanlislar_boslar_dogru_bos_olsaydi_toplam += $yanlislar_boslar_dogru_bos_olsaydi;
                                                    $yanlislar_bos_olsaydi_toplam += $yanlislar_bos_olsaydi;
                                                /*
                                                (
                        [toplam] => 2
                        [dogru] => 1
                        [yanlis] => 1
                        [bos] => 0
                        [bos_puan] => 0.25
                        [dogru_puan] => 1.25
                        [db_puan] => 1.5
                    )
                                                */
                                            ?>
                                                    <tr class="<?php if($deger['toplam']==$deger['dogru']) echo "table-success"; ?>">
                                                        <td>
                                                            
                                                        <?php if($deger['toplam']==$deger['dogru']) {

                                                            ?>
                                                            <i class="fa fa-check-circle"></i> 
                                                            <?php 
                                                        }; ?>  
                                                        <i class="fa fa-arrow-circle-right"></i>  
                                                        <?php echo html_entity_decode($kazanim_adi) ?></td>
                                                        <td>{{$deger['toplam']}}</td>
                                                        <td>{{$deger['dogru']}}</td>
                                                        <td>{{$deger['yanlis']}}</td>
                                                        <td>{{$deger['bos']}}</td>
                                                        <td>{{$deger['dogru'] - $deger['yanlis'] / $net_bolum}}</td>
                                                        <td>
                                                            <?php if($yanlislar_bos_olsaydi!=0) {
                                                            ?>
                                                            <div class="badge badge-success">+{{nf($yanlislar_bos_olsaydi,"",5)}}</div>
                                                                <?php 
                                                            } ?>
                                                        </td>
                                                        <td>
                                                            <?php if($yanlislar_boslar_dogru_bos_olsaydi!=0) { ?>    
                                                                <div class="badge badge-success">+{{nf($yanlislar_boslar_dogru_bos_olsaydi,"",5)}}</div>
                                                            <?php } ?>
                                                        </td>
                                                    </tr> 
                                                <?php 
                                            $toplam_konu_dogru += $deger['dogru'];
                                            $toplam_konu_yanlis += $deger['yanlis'];
                                            $toplam_konu += $deger['toplam'];
                                            } ?>
                                                <?php 
                                                try {
                                                    $this_percent = round(($toplam_konu_dogru - $toplam_konu_yanlis / $net_bolum) * 100 / $toplam_konu,2);
                                                    $graph_percent[$konu] = $this_percent; 
                                                    if($graph_percent[$konu]<0) {
                                                        $graph_percent[$konu] = 0;
                                                    }
                                                    if($this_percent>=95) {
                                                        $this_seviye = "iyi";
                                                    } elseif($this_percent>=75) {
                                                        $this_seviye = "orta";
                                                    } else {
                                                        $this_seviye = "dusuk";
                                                    }
                                                } catch (\Throwable $th) {
                                                    $graph_percent[$konu] = 0;
                                                }
                                                //echo "$title $konu $this_seviye <br>";
                                                try {
                                                    if($this_percent==0) {
                                                        $hedefler[$konu] = $konu_mapping[$title][$konu]["dusuk"];
                                                    } else {
                                                        $hedefler[$konu] = $konu_mapping[$title][$konu][$this_seviye];
                                                        /*
                                                        if($hedefler[$konu]<0) {
                                                            $hedefler[$konu] = $konu_mapping[$title][$konu]["dusuk"];
                                                        }
                                                        */
                                                    }
                                                    
                                                   // print2($hedefler[$konu]); 
                                                } catch (\Throwable $th) {
                                                    $hedefler[$konu] = 100;
                                                    //throw $th;
                                                   // print2($th);
                                                }
                                                
                                                /*
                                                     Array
(
    [TÜRKÇE (TYT)] => Array
        (
            [Anlatım Bozukluğu] => Array
                (
                    [konu_onemi] => 1
                    [iyi] => 95
                    [orta] => 75
                    [dusuk] => 65
                )

            [Cümle Türleri] => Array
                (
                    [konu_onemi] => 1
                    [iyi] => 95
                    [orta] => 75
                    [dusuk] => 65
                )

            [Cümlede Anlam] => Array
                (
                    [konu_onemi] => 1
                    [iyi] => 95
                    [orta] => 75
                    [dusuk] => 65
                )
                                                */
                                                ?>
                                            <?php } ?>
                                            <tr class="{{$alan}}_tablo_footer d-none">
                                               
                                                <th>{{$gtoplam}}</th>
                                                <th>{{$dogru_toplam}}</th>
                                                <th>{{$yanlis_toplam}}</th>
                                                <th>{{$bos_toplam}}</th>
                                                <th>{{$dogru_toplam - $yanlis_toplam / $net_bolum}}</th>
                                                <th><div class="badge badge-success">+{{nf($yanlislar_bos_olsaydi_toplam,"",5)}}</div></th>
                                                <th><div class="badge badge-success">+{{nf($yanlislar_boslar_dogru_bos_olsaydi_toplam,"",5)}}</div></th>
                                            </tr>
                                            
                                        </table>
                                    </div> 
                                </div>
                                <div class="col-12 order-1">
                                <?php 
                          //      print2($hedefler);
                          //      print2($graph_percent);
                              //  arsort($graph_percent);
                              //  arsort($hedefler);
                                /*
                                foreach($graph_percent AS $alan => $deger) {
                                    
                                 //   $new_alan = substr($alan,0,20);
                                    $graph_percent[$new_alan] = $deger;
                                   // unset($graph_percent[$alan]);
                                }
                                */
                            //    print2($graph_percent);
                                $values = implode(",",$graph_percent);
                                $values2 = implode(",",$hedefler);
                                $labels =  implode_key("**",$graph_percent);
                                $id = rand();
                                $opacity = 1;
                                $labels = explode("**",$labels);
                                $labels = array_map('tirnakli', $labels);
                                $label_say= count($labels);
                                $labels = implode(",",$labels);
                                $height = 300;
                                $width = 150*$label_say + 100;
                                $type = "bar"; 
                                 ?>
<div class="table-responsive">
    <canvas id="truncgil<?php echo $id ?>" class="truncgil-chart"  style="width:<?php echo $width; ?>px !important;height:<?php echo $height; ?>px !important;max-height:<?php echo $height; ?>px !important;margin:20px auto" ></canvas>
</div>
<script>
var ctx = document.getElementById('truncgil<?php echo $id ?>');
var delayed<?php echo $id ?>;
var myChart = new Chart(ctx, {
    type: '<?php echo $type ?>',
   //type: 'line',
  //    stepped: true,
  <?php $renkler = [];
    for($k=1;$k<=20;$k++) {
        $r1 = rand(1,255);
        $r2 = rand(1,255);
        $r3 = rand(1,255);
        array_push($renkler,"'rgba($r1, $r2, $r3,0.5)'");
    }
  ?>
    data: {
        labels: [<?php echo $labels ?>],
        datasets: [{
            label: 'Seviyeniz',
            data: [<?php echo $values ?>],
            backgroundColor: [
                <?php echo implode(",",$renkler) ?>
            ],
            borderColor: [
                <?php echo implode(",",$renkler) ?>
            ],
            borderRadius :20,
            maxBarLenght:100,
            borderWidth: 1,
            order:2
        },
        {
            label: 'Hedef',
            data: [<?php echo $values2 ?>],
            borderColor: 'rgba(255, 255, 255, <?php echo 0 ?>)',
            borderWidth:0,
            pointRadius: 10,
            backgroundColor: 'rgba(255, 0, 0, <?php echo 1 ?>)',
            type: 'line',
            stepped: true,
            order:1
        }
    ]
    },
    options: {
        animation: {
            tension: {
                duration: 1000,
                easing: 'linear',
                from: 1,
                to: 0,
                loop: true
            },
            onComplete: () => {
                delayed<?php echo $id ?> = true;
            },
            delay: (context) => {
                let delay<?php echo $id ?> = 0;
                if (context.type === 'data' && context.mode === 'default' && !delayed<?php echo $id ?>) {
                delay<?php echo $id ?> = context.dataIndex * 300 + context.datasetIndex * 100;
                }
                return delay<?php echo $id ?>;
            }
        },
        plugins: {
            title: {
                display: true
            // text: 'Chart.js Bar Chart - Stacked'
            },
        },
        responsive: false,
        scales: {
            x: {
                stacked: false,
                
                maxBarThickness: 100,
                barPercentage: 0.4
            
                
            },
            y: {
                stacked: false
               
            }
        }
  }
});
</script>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                 </div>
                 
         </div> 
         <?php } ?>
       
    </div>
    </div>

</div>
<?php // print2($soru_sayilari) ?>
