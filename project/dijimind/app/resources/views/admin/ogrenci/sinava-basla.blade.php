<?php 
 oturumAc();
 //read_only_exit();
 //dd($_SESSION);
if(getisset("cache")) {
    print2($_POST);
   
    $_SESSION['cache'] = $_POST;
    exit();
} 
if(getisset("cache_sure")) {
    $_SESSION['cache_sure'] = get("cache_sure");
    exit();
}
if(getisset("index_cache")) {
    $_SESSION['index_cache'] = get("index_cache");
   // print2($_SESSION);
    exit();
}
?>
<?php 
$sure = oturum("sure");
if(oturumisset("cache_sure")) {
    $sure = oturum("cache_sure") / 60;
    //echo $sure;
}
$sorular = oturum("sorular");
//print2($sorular);
$justPdf = false;
if(count($sorular)==0) {
    $justPdf = true;
    //dd($_SESSION);
    $sinav = db("sinavlar")->where("id",oturum("sinav_id"))->first();
    $dersler = j($sinav->dersler);
    $kazanimlar = j($sinav->kazanimlar);
    usort($dersler, fn($a, $b) => $a['optik'] <=> $b['optik']);
    $sorular = [];
    $sorular2 = [];
    foreach($dersler AS $ders) {
        for($k = 1; $k<=$ders['soru']; $k++) {

            $kazanim_pattern = str_slug($ders['isim']) . "_kazanim_" . $k;
            $tak_pattern = str_slug($ders['isim']) . "_tak_" . $k;
            $sorular2[] = $k;
            $sorular[] = (Object) [
                'id' => $k,
                'brans'=>$ders['isim'],
                'a_sira' => $k,
                'b_sira' => $k,
                'paragraf_grup' => "",
                'cover' => "",
                'html' => "",
                'title' => "",
                'video' => "",
                'konu' => "",
                'taksonomik_duzey' => @$kazanimlar[$tak_pattern],
                'kazanim' => @$kazanimlar[$kazanim_pattern]
            ];
        }
    }
    $sorular2 = implode(",",$sorular2);
    

} else {
    $sorular2 = implode(",",$sorular);
    $sorular = db("soru_bankasi")->whereIn("id",$sorular)
        ->orderByRaw("FIELD(id, $sorular2)")
        ->get();
}


//print2($_SESSION);
?>

<?php if($justPdf) { ?>
    @include("admin.ogrenci.sinav-pdf.css")
    @include("admin.ogrenci.sinav-pdf.pdf")
<?php } else  { 
  ?>
 <?php if(getisset("pdf")) { ?>
     @include("admin.ogrenci.sinav-pdf.css")
     @include("admin.ogrenci.sinav-pdf.pdf")
 <?php } ?> 
 <?php } ?>

 
 
    @include("admin.ogrenci.sinava-basla.css")
    @include("admin.ogrenci.sinava-basla.sayac")
    @include("admin.ogrenci.sinava-basla.sinav-form")
    
    @include("admin.ogrenci.sinava-basla.timeup")
    @include("admin.ogrenci.sinava-basla.script")
    @include("admin.ogrenci.sinava-basla.optik-form")
    @include("admin.ogrenci.sinava-basla.footer") 
