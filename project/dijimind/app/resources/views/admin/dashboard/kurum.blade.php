
<?php 
$u = u();
$users = aliasUsersArray();
$sonuclar = kurum_sonuclar(); 
$dogru_yanlis_bos = array();
$siniflar = kurum_siniflar();
$ogrenciler = kurum_users("Öğrenci");
$ogrenci_sayisi = count($ogrenciler);
foreach($sonuclar AS $sonuc) {
    //print2($sonuc);
    $j = j($sonuc->analiz);
  //  print2($j);
    foreach($j AS $alan => $deger) {
        $brans_title = slug_to_title($alan);
        @$dogru_yanlis_bos[$brans_title]['dogru'] += $deger['dogru']; 
        @$dogru_yanlis_bos[$brans_title]['yanlis'] += $deger['yanlis']; 
        @$dogru_yanlis_bos[$brans_title]['bos'] += $deger['bos']; 
    }
}
//print2($dogru_yanlis_bos);
?>
<div class="row">

    <div class="col-md-4">
        {{col2("text-center")}}
       
            <img src="{{kurum_logo()}}" class="img-fluid" alt="Dijimind">
        {{_col2()}}
    <a class="block text-center  " href="#">

            <div class="block-header bg-gd-dusk block-content-full block-content-sm">
                <span class="text-white text-center">{{$u->name}} {{$u->surname}}</span>
            </div>
            <div class="block-content block-content-full bg-primary-lighter rounded-0">
                <img class="img-avatar img-avatar-thumb" src="{{profile_pic()}}" alt="">
            </div>
            <div class="block-content">
                <div class="row items-push text-center">
                    <div class="col-6">
                        <div class="mb-5"><i class="si si-trophy  fa-2x text-primary"></i></div>
                        <div class="font-size-sm text-muted">{{get_rozet_alias()}}</div>
                    </div>
                    <div class="col-6">
                        <div class="mb-5"><i class="si si-star fa-2x text-primary"></i></div>
                        <div class="font-size-sm text-muted">{{get_score_alias()}}</div>
                    </div>
                    <div class="col-6">
                        <div class="mb-5"><i class="si si-users  fa-2x text-primary"></i></div>
                        <div class="font-size-sm text-muted">{{$ogrenci_sayisi}}</div>
                    </div>
                    <div class="col-6">
                        <div class="mb-5"><i class="si si-layers fa-2x text-primary"></i></div>
                        <div class="font-size-sm text-muted">{{count($siniflar)}}</div>
                    </div>
                   
                </div>
            </div>
           
        </a>
        <div class="block text-center  d-none" >

            <div class="block-header {{bg_color(36)}} block-content-full block-content-sm">
                <span class="font-w600 text-white">{{e2("Sınava Kalan Süre")}}</span>
            </div>
            <div class="block-content">
    <script>
        (function () {
  const second = 1000,
        dakute = second * 60,
        hour = dakute * 60,
        day = hour * 24;

  //I'm adding this section so I don't have to keep updating this pen every year :-)
  //remove this if you don't need it
  <?php $sinav_type = sinav_type($u->sinif); ?>
  let today = new Date(),
      dd = String(today.getDate()).padStart(2, "0"),
      mm = String(today.getMonth() + 1).padStart(2, "0"),
      yyyy = today.getFullYear(),
      nextYear = yyyy + 1,
      dayMonth = "{{ayar("$sinav_type Sınav Ay")}}/{{ayar("$sinav_type Sınav Gün")}}/",
      birthday = dayMonth + {{ayar("$sinav_type Sınav Yıl")}};
  
  today = mm + "/" + dd + "/" + yyyy;
  if (today > birthday) {
    birthday = dayMonth + nextYear;
  }
  //end
  
  const countDown = new Date(birthday).getTime(),
      x = setInterval(function() {    

        const now = new Date().getTime(),
              distance = countDown - now;

        document.getElementById("gün").innerText = Math.floor(distance / (day)),
          document.getElementById("hours").innerText = Math.floor((distance % (day)) / (hour)),
          document.getElementById("dakutes").innerText = Math.floor((distance % (hour)) / (dakute)),
          document.getElementById("seconds").innerText = Math.floor((distance % (dakute)) / second);

        //do something later when date is reached
        if (distance < 0) {
          document.getElementById("headline").innerText = "It's my birthday!";
          document.getElementById("countdown").style.display = "none";
          document.getElementById("content").style.display = "block";
          clearInterval(x);
        }
        //seconds
      }, 0)
  }());
    </script>
    <style>
 
#countdown .container {
  color: #333;
  margin: 0 auto;
  text-align: center;
}
#countdown ul {
    margin:0;
    padding:0;
}
#countdown h1 {
  font-weight: normal;
  letter-spacing: .125rem;
  text-transform: uppercase;
}

#countdown li {
  display: inline-block;
  font-size: 10px;
  list-style-type: none;
  padding: 1em;
  text-transform: uppercase;
}

#countdown li span {
  display: block;
  font-size: 20px;
}

.emoji {
  display: none;
  padding: 1rem;
}

.emoji span {
  font-size: 4rem;
  padding: 0 .5rem;
}

@media all and (max-width: 768px) {
  #countdown h1 {
    font-size: calc(1.5rem * var(--smaller));
  }
  
  #countdown li {
    font-size: calc(1.125rem * var(--smaller));
  }
  
  #countdown li span {
    font-size: 30px;
  }
}
    </style>
       
        <div id="countdown" class="text-center">
            <ul>
                <li><span id="gün"></span>{{e2("Gün")}}</li>
                <li><span id="hours"></span>{{e2("Saat")}}</li>
                <li><span id="dakutes"></span>{{e2("Dakika")}}</li>
                <li><span id="seconds"></span>{{e2("Saniye")}}</li>
            </ul>
        </div>
    
        <div class="row js-appear-enabled animated fadeIn text-center" data-toggle="appear">
                        <!-- Row #1 -->
                        
                        <!-- END Row #1 -->
                    </div>
        
        
        </div>
        </div>
        </div>
    <div class="col-md-8">
    <?php 
    /*
taksonomi([
    "A1" => [
        "Doğru" => 65,
        "Yanlış" => 20,
        "Boş" => 15
    ]
    ]);
    */
    $sinav_sayisi = db("sonuclar")->whereIn("uid",$u->alias_ids)->count();
    
?>
<?php $soru_sayisi = soru_sayisi($u->alias_ids); 
//dump($soru_sayisi);
$soru_sayisi['puan_ort'] = round($soru_sayisi['puan_ort'],4);
//print2($soru_sayisi);

?>
    <div class="owl-carousel ozet">
                            <div class="">
                                {{stat_block("Öğrenci <br> Sayısı",$ogrenci_sayisi,150,61)}}
                                
                            </div>
                            <div class="">
                                {{stat_block("Girilen <br> Sınav",$sinav_sayisi,150,58)}}
                                
                            </div>
                            <div class="">
                                {{stat_block("Çözülen <br> Soru",$soru_sayisi['toplam'],150,59)}}
                            </div>
                            <div class="">
                                {{stat_block("Doğru Yapılan <br> Soru",$soru_sayisi['dogru'],150,61)}}
                                
                            </div>
                           
                            <div class="">
                                {{stat_block("Yanlış Yapılan <br> Soru",$soru_sayisi['yanlis'],150,56)}}
                                
                            </div>
                            <div class="">
                                {{stat_block("Boş Bırakılan <br> Soru",$soru_sayisi['bos'],150,62)}}
                                
                            </div>
                            <div class="">
                                {{stat_block("Ortalama <br> Puan",$soru_sayisi['puan_ort'],150,60)}}
                                
                            </div>

                        </div>
                        <script>
                            $(function(){
                                
                            });
                        </script>
                        <div class="row">  
                            <?php col("col-md-7","Şimdiye Kadar Çözülen Tüm Sınavlarda",15) ?>
                                {{charts("Doğru,Yalnış,Boş","{$soru_sayisi['dogru']},{$soru_sayisi['yanlis']},{$soru_sayisi['bos']}","Doğru yanlış boş sayınız","pie","260")}} 
                                {{charts("Junior,Pratisyen,Master","{$soru_sayisi['Junior']},{$soru_sayisi['Pratisyen']},{$soru_sayisi['Master']}","Taksonomik Düzeyiniz","bar","260")}}      
                            <?php _col(); ?>
                            <?php col("col-md-5","Zaman Çizelgesi",5) ?>
                                <div class="slimScrollDiv" style="position: relative; overflow: auto; overflow-x:hidden; width: auto; height: 520px;">

                                <ul class="list list-timeline list-timeline-modern pull-t">
                                <!-- Twitter Notification -->
                                <?php $logs = db("logs")->whereIn("uid",$u->alias_ids)->orderBy("datetime","DESC")->take(50)->get();
                                
                                foreach($logs AS $l)  { 
                                    $user = $users[$l->uid];
                                 
                                 ?>
                                 <li>
                                     <div class="list-timeline-time">{{date("d.m H:i",strtotime($l->datetime))}}
                                        {{$user->name}} {{$user->surname}}

                                     </div>
                                     <i class="list-timeline-icon fa {{$l->class}}"></i>
                                     <div class="list-timeline-content">
                                         <?php if($l->subject!="")  { 
                                           ?>
                                          <p class="font-w600">{{$l->subject}}</p> 
                                          <?php } ?>
                                         <p>{{$l->text}}</p>
                                     </div>
                                 </li> 
                                 <?php } ?>
                                <!-- END Twitter Notification -->

                               
                                
                            </ul>
                            </div>
    <?php _col(); ?>

                        </div>
                        
                        
            </div>

    
 
</div>
<div class="row">
<?php 
 $values = implode(",",$soru_sayisi['ay_dogru']);
 $labels =  implode_key(",",$soru_sayisi['ay_dogru']);
?>
    <?php col("col-md-4","Aylara Göre Doğru Sayıları",13) ?>
            {{charts($labels,$values,"","line")}}
    <?php _col(); ?>
<?php 
 $values = implode(",",$soru_sayisi['ay_yanlis']);
 $labels =  implode_key(",",$soru_sayisi['ay_yanlis']);
?>
    <?php col("col-md-4","Aylara Göre Yanlış Sayıları",9) ?>
            {{charts($labels,$values,"","line")}}
    <?php _col(); ?>
    <?php 
 $values = implode(",",$soru_sayisi['ay_bos']);
 $labels =  implode_key(",",$soru_sayisi['ay_bos']);
?>
    <?php col("col-md-4","Aylara Göre Boş Sayıları",21) ?>
            {{charts($labels,$values,"","line")}}
    <?php _col(); ?>

    @include("admin.dashboard.kurum.puanlar")
    @include("admin.dashboard.kurum.siralama")

    
</div>
<?php 
$max_sira = max_sira();
$dijimind_sira = dijimind_sira();
$tr_siralama = 0;
$dijimind_siralama = 0;
try {
    if($soru_sayisi['son_siralama']!=0) {
        $tr_siralama = round($max_sira/$soru_sayisi['son_siralama'],0);
        $dijimind_siralama = round($dijimind_sira/$soru_sayisi['son_siralama'],0);
    }
    ?>
    <h2 class="content-heading">{{e2("Branşlara Göre Toplam Doğru Yanlış Boş Sayısı")}}</h2>
    <div class="row">
        <?php col("col-md-6 text-center","Türkiye Sıralamasındaki Yeriniz",4); ?>
            <?php people_chart(10,$tr_siralama,"success"); ?>
            <div class="badge badge-success">{{e2("Her kişi 10000 kişiyi temsil etmektedir.")}}</div>
        <?php _col(); ?>
        <?php col("col-md-6 text-center","Dijimind Sıralamasındaki Yeriniz",0); ?>
            <?php people_chart(10,$dijimind_siralama,"primary"); ?>
            <div class="badge badge-primary">{{e2("Her kişi 1000 kişiyi temsil etmektedir.")}}</div>
        <?php _col(); ?>
    
    </div>
    <?php
} catch (\Throwable $th) {
    //throw $th;
}
?>
   

</div>
<div class="row">
    <?php 
    $k=5;
    foreach($dogru_yanlis_bos AS $alan => $deger) {
         ?>
        <?php col("col-md-3","$alan",$k) ?>
            {{charts("Doğru,Yanlış,Boş",implode(",",$dogru_yanlis_bos[$alan]),$alan,"doughnut")}}
        <?php _col(); ?>
         <?php 
    $k++; } ?>
</div>