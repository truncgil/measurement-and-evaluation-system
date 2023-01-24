<?php function kat_sayi() {
    $brans = db("contents")->where("type","Branşlar")->get();
    $dizi = [];
    foreach($brans AS $b) {
        if($b->max=="") $b->max = 1;
        $dizi[$b->slug] = $b->max;
    }
    return $dizi;
} 
?>