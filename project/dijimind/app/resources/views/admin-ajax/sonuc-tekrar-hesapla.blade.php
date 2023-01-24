<?php 
$u = u();
if($u->level=="Admin") {
    $sonuclar = db("sonuclar");

if(getisset("sinav_id")) {
    $sonuclar = $sonuclar->where("sinav_id", get("sinav_id"));
}

if(getisset("title")) {
    $sonuclar = $sonuclar->where("title", "LIKE","%" .  get("title") . "%");
}

/*
$effects = $sonuclar->simplePaginate(10);
dump($effects);
*/

$sonuclar = $sonuclar->update(['sonuc_hesapla'=>0]);
echo "$sonuclar sonucun puanları tekrar hesaplanıyor";
//exit();


 ?>
@include("cron.puan-hesapla")
 <?php 
}

?>
