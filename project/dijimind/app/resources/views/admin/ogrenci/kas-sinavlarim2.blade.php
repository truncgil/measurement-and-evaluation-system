<?php if($u->level=="Kurum")  { 
    if(getisset("id")) {
        $ogrenciId = get("id");
    } else {
        yonlendir("admin");
    }
  ?>
 

 <?php } else {
     ?>
     {{onay_gerekli()}} 
     <?php 
 } ?>
 
<?php $path = "admin.ogrenci.kas-sinavlarim2"; ?>
<div class="row">
    @include("$path.sinavlar")
</div>
