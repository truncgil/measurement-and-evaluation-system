<?php 
$puanTurleri = [
    'puanlar_tyt' => 'TYT',
    'puanlar_lgs' => 'LGS',
    'puanlar_ayt_say' => 'AYT Sayısal',
    'puanlar_ayt_soz' => 'AYT Sözel',
    'puanlar_ayt_ea' => 'AYT Eşit Ağırlık',
];
foreach($puanTurleri AS $puanTuru => $title)  { 
 
  $values = implode(",",$soru_sayisi[$puanTuru]);
  $labels =  implode_key(",",$soru_sayisi[$puanTuru]);
 ?>
 <?php col("col-md-6","$title Puana Göre Gelişim Grafiği",23) ?>
     {{charts($labels,$values,"","line")}}
 <?php _col(); ?> 
 <?php } ?>