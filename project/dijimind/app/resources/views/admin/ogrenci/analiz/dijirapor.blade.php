<?php 
$net_bolum = 4;
if(sinav_type($u->sinif)=="LGS") $net_bolum=3;
$dizi = array();
$dizi2 = array();
$dizi3 = [];
$dizi4 = [];
$total = 0;
$level_total = [];
$level_dyb_total = []; //doğru yanlış boş toplamları
$kazanim_to_konu = kazanim_to_konu();

foreach($j AS $alan => $deger) {
   $title = slug_to_title($alan);
    $levels = explode(",","Junior,Pratisyen,Master");
    foreach($levels AS $level) {
        if(!isset($dizi3[$alan][$level]["Doğru"])) $dizi3[$alan][$level]["Doğru"] = 0;
        if(!isset($dizi3[$alan][$level]["Yanlış"])) $dizi3[$alan][$level]["Yanlış"] = 0;
        if(!isset($dizi3[$alan][$level]["Boş"])) $dizi3[$alan][$level]["Boş"] = 0;
        if(!isset($dizi3[$alan][$level]["Net"])) $dizi3[$alan][$level]["Net"] = 0;

        if(!isset($dizi3[$alan][$level]["Doğru"])) $dizi3[$alan][$level]["Doğru"] = 0;
        if(!isset($dizi3[$alan][$level]["Yanlış"])) $dizi3[$alan][$level]["Yanlış"] = 0;
        if(!isset($dizi3[$alan][$level]["Boş"])) $dizi3[$alan][$level]["Boş"] = 0;
        if(!isset($dizi3[$alan][$level]["Net"])) $dizi3[$alan][$level]["Net"] = 0;
        
    }
    $say = 0;
    foreach($deger['tak-dogru'] AS $tak) {
        
        $level = taksonomi_to_level($tak);
        $konu = @$kazanim_to_konu[$title][@$deger['kazanim-dogru'][$say]];
        if(!isset($dizi4[$alan][$konu][$level]['Doğru'])) $dizi4[$alan][$konu][$level]['Doğru'] = 0;
        if(!isset($dizi4[$alan][$konu][$level]['Yanlış'])) $dizi4[$alan][$konu][$level]['Yanlış'] = 0;
        if(!isset($dizi4[$alan][$konu][$level]['Boş'])) $dizi4[$alan][$konu][$level]['Boş'] = 0;
        @$dizi4[$alan][$konu][$level]['Doğru']++;

        if(!isset($level_total[$level])) $level_total[$level] = 0;
        $level_total[$level]++;
        if(!isset($level_dyb_total[$level]['Doğru'])) $level_dyb_total[$level]['Doğru'] = 0;
        $level_dyb_total[$level]['Doğru']++;
        if(!isset($dizi2[$alan][$level])) $dizi2[$alan][$level] = 0;
        if(!isset($dizi2[$alan]["Toplam"])) $dizi2[$alan]["Toplam"] = 0;

        
        
        
        
    //    if(!isset($dizi3[$alan][$level]["Boş"])) $dizi3[$alan][$level]["Boş"] = 0;
        
        $dizi2[$alan][$level]++;
        $dizi2[$alan]["Toplam"]++;
        if(!isset($dizi3[$alan][$level]['Doğru'])) $dizi3[$alan][$level]["Doğru"] = 0;
        $dizi3[$alan][$level]["Doğru"]++;
        
        
        $say++;
        $total++;
    }
    foreach($deger['tak-yanlis'] AS $tak) {
      
      $level = taksonomi_to_level($tak);
      $konu = @$kazanim_to_konu[$title][@$deger['kazanim-yanlis'][$say]];
      if(!isset($dizi4[$alan][$konu][$level]['Doğru'])) $dizi4[$alan][$konu][$level]['Doğru'] = 0;
      if(!isset($dizi4[$alan][$konu][$level]['Yanlış'])) $dizi4[$alan][$konu][$level]['Yanlış'] = 0;
      if(!isset($dizi4[$alan][$konu][$level]['Boş'])) $dizi4[$alan][$konu][$level]['Boş'] = 0;
      @$dizi4[$alan][$konu][$level]['Yanlış']++;
      if(!isset($level_total[$level])) $level_total[$level] = 0;
      $level_total[$level]++;
      if(!isset($level_dyb_total[$level]['Yanlış'])) $level_dyb_total[$level]['Yanlış'] = 0;
      $level_dyb_total[$level]['Yanlış']++;
      if(!isset($dizi3[$alan][$level]["Yanlış"])) $dizi3[$alan][$level]["Yanlış"] = 0;
      $dizi3[$alan][$level]["Yanlış"]++;
    }
    foreach($deger['tak-bos'] AS $tak) {
      $level = taksonomi_to_level($tak);

      $konu = @$kazanim_to_konu[$title][@$deger['kazanim-bos'][$say]];
      if(!isset($dizi4[$alan][$konu][$level]['Doğru'])) $dizi4[$alan][$konu][$level]['Doğru'] = 0;
      if(!isset($dizi4[$alan][$konu][$level]['Yanlış'])) $dizi4[$alan][$konu][$level]['Yanlış'] = 0;
      if(!isset($dizi4[$alan][$konu][$level]['Boş'])) $dizi4[$alan][$konu][$level]['Boş'] = 0;
      @$dizi4[$alan][$konu][$level]['Boş']++;

      if(!isset($level_total[$level])) $level_total[$level] = 0;
      $level_total[$level]++;
      if(!isset($level_dyb_total[$level]['Boş'])) $level_dyb_total[$level]['Boş'] = 0;
      $level_dyb_total[$level]['Boş']++;
      if(!isset($dizi3[$alan][$level]["Boş"])) $dizi3[$alan][$level]["Boş"] = 0;
      $dizi3[$alan][$level]["Boş"]++;
    }
   

    
}

foreach($dizi3 AS $alan => &$tax) {
  foreach($tax AS $tax_level => &$level) {
   // print2($tax_level); exit();
    if(!isset($dizi[$tax_level])) $dizi[$tax_level] = 0;
    if(isset($level["Doğru"])) {
      if(isset($level["Yanlış"])) {
        
        $level["Net"] = $level["Doğru"] - $level["Yanlış"] / $net_bolum;
        
      } else {
       
        $level["Net"] = $level["Doğru"];
      }
      $dizi[$tax_level] += $level["Net"];
    } else {
    //  echo "$alan $level doğru yok <br>";
    }
  }
  
}
//dd($dizi4);
//print2($level_dyb_total);
//print2($dizi); exit();

//print2($level_total);
if(!isset($dizi['Junior'])) $dizi['Junior'] = 0;
if(!isset($dizi['Pratisyen'])) $dizi['Pratisyen'] = 0;
if(!isset($dizi['Master'])) $dizi['Master'] = 0;
if(!isset($level_total['Junior'])) $level_total['Junior'] = 0;
if(!isset($level_total['Pratisyen'])) $level_total['Pratisyen'] = 0;
if(!isset($level_total['Master'])) $level_total['Master'] = 0;
if(!isset($level_dyb_total['Junior'])) $level_dyb_total['Junior'] = 0;
if(!isset($level_dyb_total['Pratisyen'])) $level_dyb_total['Pratisyen'] = 0;
if(!isset($level_dyb_total['Master'])) $level_dyb_total['Master'] = 0;
//print2($dizi);
$junior_percent = 0;
$pratisyen_percent = 0;
$master_percent = 0;
if($level_total['Junior']!=0) $junior_percent = round($dizi['Junior'] * 100 / $level_total['Junior'],0);
if($level_total['Pratisyen']!=0) $pratisyen_percent = round($dizi['Pratisyen'] * 100 / $level_total['Pratisyen'],0);
if($level_total['Master']!=0) $master_percent = round($dizi['Master'] * 100 / $level_total['Master'],0);
?>
<strong>Genel Durum</strong>
@include("admin.ogrenci.analiz.dijirapor.genel-durum")
<style>
  .taksonomi-progress {
    height:auto;
  }
</style>
<div id="dizi4" class="d-none"><?php  echo json_encode_tr($dizi4); ?></div>
<div id="accordion3" class="mt-10 role="tablist" aria-multiselectable="true">
  <?php foreach($j AS $alan => $deger)  { 
              $title = slug_to_title($alan);
            ?>
          <div class="block block-bordered block-rounded mb-2 ">
                  <div class="block-header" role="tab" id="accordion2_h1">
                      <a class="font-w600" data-toggle="collapse" data-parent="#accordion3" href="#accordion2{{$alan}}" aria-expanded="true" aria-controls="accordion2_q1">{{$title}}</a>
                  </div>
                  <div id="accordion2{{$alan}}" class="collapse " role="tabpanel" aria-labelledby="accordion2_h1">
                      <div class="block-content {{$alan}}_dijirapor">
                          <?php
                          $total = 0;
                          
                          if(isset($dizi2[$alan]['Toplam'])) $total = $dizi2[$alan]['Toplam'];
                          $toplanacak = 
                          $toplam_junior = @$dizi3[$alan]['Junior']['Doğru'] + @$dizi3[$alan]['Junior']['Yanlış'] + @$dizi3[$alan]['Junior']['Boş'];
                          $toplam_pratisyen = @$dizi3[$alan]['Pratisyen']['Doğru'] + @$dizi3[$alan]['Pratisyen']['Yanlış'] + @$dizi3[$alan]['Pratisyen']['Boş'];
                          $toplam_master = @$dizi3[$alan]['Master']['Doğru'] + @$dizi3[$alan]['Master']['Yanlış'] + @$dizi3[$alan]['Master']['Boş'];
                          $junior_percent = 0;
                          $pratisyen_percent = 0;
                          $master_percent = 0;
                         // echo "$toplam_junior $toplam_pratisyen $toplam_master ";
                        //  print2($dizi3[$alan]);
                          if($toplam_junior!=0) $junior_percent = round($dizi3[$alan]['Junior']['Net'] * 100 / $toplam_junior,0);
                          if($toplam_pratisyen!=0) $pratisyen_percent = round($dizi3[$alan]['Pratisyen']['Net'] * 100 / $toplam_pratisyen,0);
                          if($toplam_master!=0) $master_percent = round($dizi3[$alan]['Master']['Net'] * 100 / $toplam_master,0);
                          if($junior_percent<0) $junior_percent = 0; 
                          if($pratisyen_percent<0) $pratisyen_percent = 0; 
                          if($master_percent<0) $master_percent = 0; 
                          ?>
                          <strong>{{e2("Genel Durum")}}</strong>
                          <div class="row">

                            <div class="col-md-4">
                              <?php if($toplam_junior==0) {
                                 ?>
                                 @include("admin.ogrenci.analiz.progress-not-found")
                                 <?php 
                              } else  { 
                                ?>
                               <div class="progress taksonomi-progress text-center">
                                 <div class="progress-bar bg-junior" style="width:{{$junior_percent}}%">
                                   {{e2("Junior")}}
                                   <br>
                                   %{{$junior_percent}}
                                 </div>
                               </div> 
                               <?php } ?>

                            </div>

                            <div class="col-md-4">
                            <?php if($toplam_pratisyen==0) {
                                 ?>
                                @include("admin.ogrenci.analiz.progress-not-found")  
                                 <?php 
                              } else  { 
                                ?>
                              <div class="progress taksonomi-progress text-center">

                                <div class="progress-bar bg-pratisyen" style="width:{{$pratisyen_percent}}%">

                                  {{e2("Pratisyen")}}
                                  <br>
                                  %{{$pratisyen_percent}}

                                </div>


                              </div>
                              <?php } ?>
                            </div>

                            <div class="col-md-4">
                            <?php if($toplam_master==0) {
                                 ?>
                                 @include("admin.ogrenci.analiz.progress-not-found")
                                 <?php 
                              } else  { 
                                ?>
                              <div class="progress taksonomi-progress text-center">

                                <div class="progress-bar bg-master" style="width:{{$master_percent}}%">

                                  {{e2("Master")}}
                                  <br>
                                  %{{$master_percent}}

                                </div>

                              </div>
                              <?php } ?>


                            </div>

                          </div>
                          <div class="row">
                        
                            <?php 
                            //print2($dizi3);
                         
                            foreach($dizi3[$alan] AS $tur => $sonuclar)  { 
                                $sonuc_toplam = 0;
                                
                                foreach($sonuclar AS $so_alan => $so_deger) {
                                  if($so_alan!="Net") {
                                    $sonuc_toplam+=$so_deger;
                                  }
                                    
                                }
                                if($tur=="Junior") $order = 1;
                                if($tur=="Pratisyen") $order = 2;
                                if($tur=="Master") $order = 3;

                              ?>
                             <div class="col-md order-{{$order}}">
                               <strong>{{$tur}}</strong> <br>
                               <?php $dyb = $sonuclar; 
                               unset($dyb['Net']);
                               ?>
                              @include("admin.ogrenci.analiz.dyb-progress")
                              
                             </div> 
                             <?php } ?>
                          </div>
                      </div>
                  </div>
          </div> 
  <?php } ?>
</div> 