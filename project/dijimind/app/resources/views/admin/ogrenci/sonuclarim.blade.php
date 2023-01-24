<?php 
if($veli) {
    $u = select_ogrenci();
} else {
    $u = u();
}

$path = "admin.ogrenci.sonuclarim"; ?>
<div class="row">
    @include("$path.sonuclarim-manuel")
    @include("$path.sonuclarim")
</div> 