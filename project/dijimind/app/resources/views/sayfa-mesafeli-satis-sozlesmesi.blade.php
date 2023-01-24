<?php 
$u = u();
$siparis = db("odemeler")
->where("id",get("id"));
$yetki = explode(",","Admin,Muhasebe");
if(!in_array($u->level,$yetki)) {
    $siparis = $siparis->where("uid",$u->id);
} else {
    
}
$siparis = $siparis->first();

if($siparis) {
    if(in_array($u->level,$yetki)) {
        $u = u2($siparis->uid);
    }
$title = "{$u->name} {$u->surname} {$siparis->id}";
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}} Mesafeli Satış Sözleşmesi</title>
</head>
<body>
<?php 


    $c = c("mesafeli-satis-sozlesmesi");
    $html = $c->html;
    $html = str_replace("{ad_soyad}","{$u->name} {$u->surname}",$html);
    $html = str_replace("{adres}","{$u->address}",$html);
    $html = str_replace("{telefon}","{$u->phone}",$html);
    $html = str_replace("{email}","{$u->email}",$html);
    $html = str_replace("{paket_adi}","{$siparis->paket_adi}",$html);
    $html = str_replace("{paket_icerigi}","{$siparis->paket_adi} Paketi Sınav ve Dijimind Online Analiz Sistemi",$html);
    $html = str_replace("{toplam_tutar}",price($siparis->tutar),$html);
    echo $html;
} else {
    echo "0";
}

?>
</body>
</html>