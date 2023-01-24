<?php 
$siralamaTurleri = [
    'siralama_tyt' => 'TYT',
//    'siralama_lgs' => 'LGS',
    'siralama_ayt_say' => 'AYT Sayısal',
    'siralama_ayt_soz' => 'AYT Sözel',
    'siralama_ayt_ea' => 'AYT Eşit Ağırlık',
];
foreach($siralamaTurleri AS $siralamaTuru => $title)  { 
 
  $values = implode(",",$soru_sayisi[$siralamaTuru]);
  $labels =  implode_key(",",$soru_sayisi[$siralamaTuru]);
 ?>
 <?php col("col-md-6","$title Sıralamaya Göre Gelişim Grafiği",24) ?>
     {{charts($labels,$values,"","line")}}
 <?php _col(); ?> 
 <?php } ?>