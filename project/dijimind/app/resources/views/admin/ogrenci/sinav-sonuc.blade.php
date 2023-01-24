<?php 
sinav_cache_remove();
$sonuc = db("sonuclar")->where("id",get("id"))->where("uid",u()->id)->first();
$j = j($sonuc->analiz);
$sinav = false;
$sinav_bag = false;
if($sonuc->sinav_id!="") {
    $sinav = db("sinavlar")->where("id",$sonuc->sinav_id)->first();
    if($sinav->bag!="") {
        $sinav_bag = db("sinavlar")->where("id",$sinav->bag)->first();
    }
}

?>
<h2>{{e2($sonuc->title)}}</h2>
<div class="row">
<?php 
$s = 1;
$toplam_basari = 0; 
foreach($j AS $alan => $deger) {
    $title = slug_to_title($alan);
    $toplam = $deger['dogru'] + $deger['yanlis'] + $deger['bos'];
             ?>
    {{col("col-md-12",$title,$s)}}
        
             <div class="table-responsive">
                 <table class="table table-bordered">
                     <tr>
                         <td>{{e2("Doğru")}}</td>
                         <th>{{$deger['dogru']}}</th>
                     </tr>
                     <tr>
                         <td>{{e2("Yanlış")}}</td>
                         <th>{{$deger['yanlis']}}</th>
                     </tr>
                     <tr>
                         <td>{{e2("Boş")}}</td>
                         <th>{{$deger['bos']}}</th>
                     </tr>
                     <tr>
                         <td>{{e2("Net")}}</td>
                         <th>{{round($deger['net'],2)}}</th>
                     </tr>
                     <tr>
                         <td>{{e2("Başarı Yüzdesi")}}</td>
                         <?php $yuzde = round($deger['net']*100/$toplam,2);
                         $toplam_basari += $yuzde;
                         ?>
                         <th>%{{$yuzde}}</th>
                     </tr>
                 </table>
             </div>
             <div class="text-center">
                <a href="?t=analizlerim&id={{get("id")}}" class="btn btn-primary btn-hero">{{e2("Detaylı Analizler")}}</a>
             </div>

           
    {{_col()}}
    <?php 
        $s++; } ?>
    <?php 
    if($sonuc->kid!="") {
        $sinav = db("sinavlar_ogrenci")
            ->where("id",$sonuc->kid)
            ->update([
                'basari' => $toplam_basari / ($s - 1)
            ]);
    }
    ?>
    <?php if($sinav_bag) {
         ?>
         {{col("col-12 text-center","Diğer Sınav")}}
            <div class="text-center"></div>
            Bu sınavından sonra <strong>{{$sinav_bag->title}}</strong> sınavını da çözmeniz gerekmektedir.
            <br>
            Şimdi çözmek ister misiniz? <br>
            <a href="?t=sinav-olustur-deneme-sinavi&id={{$sinav_bag->title}}" class="btn-hero btn btn-primary">Sınava Başla</a>
         {{_col()}}
         <?php 
    } ?>
</div>