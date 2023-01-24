<?php 
$tyt = explode(",","9,10,11,12,Mezun");
$lgs = explode(",","6,7,8");
$u = u();
$sinif = u()->sinif;
if($sinif=="") {
    bilgi("Profil ayarlarından önce sınıfınızı seçiniz");
} else  { 
 
 if(in_array($sinif,$tyt)) {
     $turkce = db("soru_bankasi")
     ->where("brans","TÜRKÇE (TYT)")
     ->where("type","Denemeler")
     ->whereNotNull("cover")
   //  ->inRandomOrder()
     ->orderBy("paragraf_grup","ASC")
     ->take(40)
     //->where("sinif_duzey",u()->sinif)
     ->get();
     $matematik = db("soru_bankasi")
     ->where("brans","MATEMATİK (TYT)")
     ->where("type","Denemeler")
     ->whereNotNull("cover")
     ->inRandomOrder()
     ->take(29)
     //->where("sinif_duzey",u()->sinif)
     ->get();
     $geometri = db("soru_bankasi")
     ->where("brans","GEOMETRİ (TYT)")
     ->where("type","Denemeler")
     ->whereNotNull("cover")
     ->inRandomOrder()
     ->take(11)
     //->where("sinif_duzey",u()->sinif)
     ->get();
     $sure = 90;
     $sorular_dizi = array();
     $k = 0;
     foreach($turkce AS $s) {
         $sorular_dizi[$k] = $s->id;
         $k++;
     }
  //   sort($sorular_dizi);
   //  print2($sorular_dizi);
     foreach($matematik AS $s) {
         $sorular_dizi[$k] = $s->id;
         $k++;
     }
     
     foreach($geometri AS $s) {
         $sorular_dizi[$k] = $s->id;
         $k++;
     }
     oturumAc();
     $_SESSION['sorular'] = $sorular_dizi;
     $_SESSION['sure'] = $sure;
     //print2($_SESSION);
     
     $title = "80 Soruluk TYT Deneme Sınavı";
     $_SESSION['title'] = $title;
     $_SESSION['sinav_type'] = "TYT";
     $desc = "Sınava Başla";
     $detaylar = '
     TÜRKÇE (TYT) : <div class="badge badge-info">40</div> <br>
                             MATEMATİK (TYT) :  <div class="badge badge-info">29</div> <br>
                             GEOMETRİ (TYT) :  <div class="badge badge-info">11</div>
     ';
 }
 if(in_array($sinif,$lgs)) {
     $turkce = db("soru_bankasi")
     ->where("brans","TÜRKÇE (LGS)")
     ->where("type","Denemeler")
     ->whereNotNull("cover")
   //  ->inRandomOrder()
     ->orderBy("paragraf_grup","DESC")
     ->take(20)
     //->where("sinif_duzey",u()->sinif)
     ->get();
     $matematik = db("soru_bankasi")
     ->where("brans","MATEMATİK (LGS)")
     ->where("type","Denemeler")
     ->whereNotNull("cover")
     ->inRandomOrder()
     ->take(20)
     //->where("sinif_duzey",u()->sinif)
     ->get();
    
     $sure = 60;
     $sorular_dizi = array();
     $k = 0;
     foreach($turkce AS $s) {
         $sorular_dizi[$k] = $s->id;
         $k++;
     }
     foreach($matematik AS $s) {
         $sorular_dizi[$k] = $s->id;
         $k++;
     }
 
     oturumAc();
     $_SESSION['sorular'] = $sorular_dizi;
     $_SESSION['sure'] = $sure;
     $_SESSION['sinav_type'] = "LGS";
     //print2($_SESSION);
     
     $title = "40 Soruluk LGS Deneme Sınavı";
     $_SESSION['title'] = $title;
     $desc = "Sınava Başla";
     $detaylar = '
     TÜRKÇE (LGS) : <div class="badge badge-info">20</div> <br>
     MATEMATİK (LGS) :  <div class="badge badge-info">20</div> <br>
                     
     ';
 }
 
 $sonuc = db("sonuclar")->where("uid",$u->id)
 ->where("title",$title)
 ->get();

 $toplam = $sonuc->count();
 $max_deneme = 10;
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
                  <a href="{{url("admin?t=sinava-basla".$pdf)}}" class="btn btn-success btn-rounded">{{e2($desc)}}</a>
                   
                 <?php } else {
                        bilgi("Sınav deneme hakkınız dolmuştur. İlginiz için teşekkürler.");
                 } ?>
            
 {{_col()}} 
 <?php } ?>