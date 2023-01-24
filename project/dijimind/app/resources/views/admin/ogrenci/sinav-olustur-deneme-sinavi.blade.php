<?php 

$tyt = explode(",","9,10,11,12,Mezun");
$lgs = explode(",","6,7,8");
$u = u();
$yetkili_mi = db("sinav_yetkileri")
    ->where("uid",$u->id)
    ->where("sinav",get("id"))
    ->first();
    //!$yetkili_mi
if(false) {
    bilgi("Bu sınavı görmeye yetkiniz yoktur");
} else {
$sinif = u()->sinif;
$sinav_type = "TYT";
if($sinif==8) $sinav_type = "LGS";
oturumAc();
unset($_SESSION['sinav_cache']);
unset($_SESSION['cache_sure']);
unset($_SESSION['index_cache']);

$sinav = db("sinavlar")
->where("title",get("id"))
->first();
if(!$sinav) {
    yonlendir("admin?t=sinavlarim&alert=Sınav bulunamadı");
}
$zaman_fark = zf2($sinav->date);
$sinav_adi = $sinav->title;
$_SESSION['sinav_id'] = $sinav->id;
$dersler = j($sinav->dersler);
// print2($dersler);
$dersler_dizi = [];
foreach($dersler AS $d) {
    $dersler_dizi[$d['optik']] = $d['isim'];
}
ksort($dersler_dizi);
//    print2($dersler_dizi);
$sorular_dizi = [];
foreach($dersler_dizi AS $ders_adi) {

$sira_type = "a_sira";
$sira_type_title = "Soru sırası A kitapçığına göre düzenlenmiştir.";
if(getisset("sira_type")) {
    $sira_type = get("sira_type");
    $sira_type_title = "Soru sırası B kitapçığına göre düzenlenmiştir.";

}
$sinavOlusturDizi = [
    "group" => $sinav_adi,
    "sirala" =>"$sira_type:ASC",
    "brans" => $ders_adi
];
//dump($sinavOlusturDizi);

$dizi = sinav_olustur($sinavOlusturDizi,true);
//dump($dizi);
//     print2($dizi);
$sorular_dizi = array_merge($sorular_dizi,$dizi);
}

/// print2($sorular_dizi);

//dd($sinav);
$sinav_json = j($sinav->json);
//dd($sinav_json['sure']);
$sure = $sinav_json['sure'];
//    print2($_SESSION);
$_SESSION['sorular'] = $sorular_dizi;
$_SESSION['sure'] = $sure;
$_SESSION['sinav_type'] = $sinav_type;
//print2($_SESSION);
$soru_sayisi = count($sorular_dizi);
$title = $sinav->title;
$_SESSION['title'] = $title;

$_SESSION['sira_type'] = "a_sira";
if(getisset("sira_type")) {
    $_SESSION['sira_type'] = get("sira_type");
}
$digerSoruSayisi =  array_sum(array_map(function($item) { 
    return $item['soru']; 
}, $dersler));
if($soru_sayisi==0) {
    $soru_sayisi = $digerSoruSayisi;
}
$desc = "Sınava Başla";


$detaylar = "
Toplam $soru_sayisi soru içermektedir.
                
" ;
//print2($sorular_dizi);

 $_SESSION['pdf'] = $sinav->files;
 
 $sonuc = db("sonuclar")->where("uid",$u->id)
 ->where("title",$title)
 ->get();

 $toplam = $sonuc->count();
 $max_deneme = 3;
 $kalan_deneme = $max_deneme - $toplam;
 if($kalan_deneme<0) {
     $kalan_deneme = 0;
 }
 if(!isset($_SESSION['cache']['cevap'])) {
    unset($_SESSION['cache_sure']);
 }

 ?>
 
 {{col("col-md-6 mx-auto text-center",$title)}}
                <?php if($kalan_deneme>0)  { 
                  ?>
                  <img src="{{url("assets/img/online.png")}}" style="width:300px"  class="img-fluid" alt="">
                  <h3>{{$title}}</h3>
                  <div class="alert alert-info">{{$kalan_deneme}} {{e2("deneme hakkınız kaldı")}}</div>
                
                  <table class="table table-striped table-bordered   mt-20">
                      <tr>
                          <td>{{e2("Süre")}}</td>
                          <td>{{$sure}} Dakika</td>
                      </tr>
                      
                      <tr>
                          <td>{{e2("Dersler")}}</td>
                          <td>
                              <?php echo $detaylar; ?>
                          </td>
                      </tr>
                  </table>
                <?php 
                $pdf = "";
                if(getesit("m","pdf")) {
                    $pdf = "&pdf";
                } ?>
                <?php if($zaman_fark<=0)  { 
                  ?>
                  <div class="alert alert-warning">{{e2($sira_type_title)}}</div>
                   <a href="{{url("admin?t=sinava-basla&pdf")}}" class="btn btn-danger btn-rounded">
                   <i class="fa fa-2x fa-file-pdf"></i>   <br>  
                   {{e2("Kağıt Üzerinde Sınavı Çöz")}}
                 </a>
                   <a href="{{url("admin?t=sinava-basla")}}" class="btn btn-success btn-rounded">
                   <i class="fa fa-2x fa-globe"></i>   <br>
                   {{e2("Online Olarak Sınavı Çöz")}}
                 </a>
                     
                 <?php } else {
                     bilgi("Sınav $zaman_fark gün sonra aktif olacaktır.");
                 } ?>
                 <?php } else {
                        bilgi("Sınav deneme hakkınız dolmuştur. İlginiz için teşekkürler.");
                 } ?>
            
 {{_col()}} 
<?php } ?>