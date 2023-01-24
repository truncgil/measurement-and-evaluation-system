<?php 
if(isset($ogrenciler)) {
    if(getisset("ogrenci")) {
        $ogrenciler = $_GET['ogrenci'];
    }
    if(getisset("sinif")) {
        $ogrenciler = sinif_ogrencileri($_GET['sinif']);
    }
   // print2($ogrenciler);
    $sonuclar = db("sonuclar")
    
    ->whereIn("uid",$ogrenciler);
    $sonuclar2 = db("sonuclar")
    ->whereIn("uid",$ogrenciler)->groupBy("title")->get();
   // echo $sonuclar->count();
} else {
    $sonuclar = db("sonuclar")
    ->where("uid",$u->id);
    $sonuclar2 = db("sonuclar")
    ->where("uid",$u->id)->groupBy("title")->get();
}

if(getisset("filtre")) {
    if(!empty($_GET['sinav'])) {
        $sonuclar = $sonuclar->whereIn("sinav_id",$_GET['sinav']);
    }
   
    
}
$sonuclar = $sonuclar->get();
//echo $sonuclar->count();
//exit();
$dizi = [];
$p = 0;
$gtoplam = array();
$gtoplam['dogru'] = 0;
$gtoplam['yanlis'] = 0;
$gtoplam['bos'] = 0;
$gtoplam['net'] = 0;
$gtoplam['yuzde'] = 0;
//print2($sonuclar);
$branslar = [];
$select_brans = [];
if(getisset("brans")) {
    $select_brans = $_GET['brans'];
}

foreach($sonuclar AS $s) {
    $j = j($s->analiz);
  
    foreach($j AS $alan => $deger) {
        if(!isset($ogrenciler)) {
            //eğer kurumsal analiz geldiyse ilgili öğrencilinin alanını seç
            $alan_bilgi = $u->alan;
            /*
            $sorumlu_ders_pattern = $alan . '-' . str_slug($alan_bilgi);
            if(!isset($sorumlu_dersler[$sorumlu_ders_pattern])) {
                continue;
            }
            */
        }
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
            $title = $alan;
            
            if(!isset($dizi[$title]['dogru'])) $dizi[$title]['dogru'] = 0;
            if(!isset($dizi[$title]['yanlis'])) $dizi[$title]['yanlis'] = 0;
            if(!isset($dizi[$title]['bos'])) $dizi[$title]['bos'] = 0;
            if(!isset($dizi[$title]['net'])) $dizi[$title]['net'] = 0;
            $dizi[$title]['dogru'] += $deger['dogru'];
            $dizi[$title]['yanlis'] += $deger['yanlis'];
            $dizi[$title]['bos'] += $deger['bos'];
            $dizi[$title]['net'] += $deger['net'];
            $toplam = $deger['dogru'] + $deger['yanlis'] + $deger['bos'];
            $yuzde = $deger['net']*100/$toplam;
            $gtoplam['dogru'] += $deger['dogru'];
            $gtoplam['yanlis'] += $deger['yanlis'];
            $gtoplam['bos'] += $deger['bos'];
            $gtoplam['net'] += $deger['net'];
        }
        if(!in_array($alan,$branslar)) {
            array_push($branslar,$alan);
        }
        
    //    $gtoplam['yuzde'] += round($yuzde,2);
    }
    
    $p++;
    
}
?>

<p>Girilmiş olan {{$p}} sonuca göre hesaplanan sonuç analizi şu şekildedir: </p>
@include("admin.ogrenci.analiz.filtre")

<div class="table-responsive mt-20">
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th>{{e2("Dersler")}}</th>
                    <th>{{e2("D")}}</th>
                    <th>{{e2("Y")}}</th>
                    <th>{{e2("B")}}</th>
                    <th>{{e2("N")}}</th>
                    <th>{{e2("%")}}</th>
                </tr>
                
                <?php foreach($dizi AS $alan => $deger)  {
                    
                    $toplam = $deger['dogru'] + $deger['yanlis'] + $deger['bos'];
                    $yuzde = $deger['net']*100/$toplam; 
                    $title = slug_to_title($alan);
                 //   echo $yuzde;
                    $gtoplam['yuzde'] += $yuzde;
                  ?>
                 <tr>
                     <th>{{$title}}</th>
                     <td>{{$deger['dogru']}}</td>
                     <td>{{$deger['yanlis']}}</td>
                     <td>{{$deger['bos']}}</td>
                     <td>{{round($deger['net'],2)}}</td>
                     <td>%{{round($yuzde,2)}}</td>
                 </tr> 
                 <?php } ?>
                 <tr class="table-success">
                    <th>{{e2("GENEL TOPLAM")}}</th>
                    <th>{{$gtoplam['dogru']}}</th>
                    <th>{{$gtoplam['yanlis']}}</th>
                    <th>{{$gtoplam['bos']}}</th>
                    <th>{{round($gtoplam['net'],2)}}</th>
                    <th>%{{@round($gtoplam['yuzde']/$p,2)}}</th>
                </tr>
            </table>
            
        </div>

   