<?php 
$u = u();
$yetki = explode(",","Admin,Kurum,Öğretmen");
if(!in_array($u->level,$yetki)) {
    echo "Yetkiniz yok";
    exit();
}
if(getisset("uid")) {
    $ogrenci = u2(get("uid"));
    if(!$ogrenci) {
        echo "Öğrenci bulunamadı";
        exit();
    }
} elseif(getisset("id")) {
    $sinavId = get("id");
    $ogrenciler = $u->alias_ids;
    $ogrenciKim = db("sonuclar")
        ->select("users.name","users.surname","sonuclar.title")
        ->join("users","users.id","=","sonuclar.uid")
        ->where("sonuclar.id",$sinavId)

        ->whereIn("sonuclar.uid",$ogrenciler)
        ->first();
       // dd($ogrenciKim);

} else {
    $ogrenciler = $u->alias_ids;
}
if(isset($ogrenciler)) {
    if(getisset("id")) {
        $title = "{$ogrenciKim->name} {$ogrenciKim->surname} {$ogrenciKim->title} Sınavı Analizleri";
    } else {
        $title = "Tüm Zamanların Analizleri"; 
    }
    
} else {
    $title = "{$ogrenci->name} {$ogrenci->surname} Analizleri"; 
}

?>
<div class="row">
    
    {{col("col-md-12 text-center")}}
        <h2>{{$title}}</h2>
    {{_col()}}
</div>
@include("admin.ogrenci.analizlerim")