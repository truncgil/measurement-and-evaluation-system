<?php $url = "?t=ogrenci-takibi"; ?>
<?php if(getisset("id")) {
   ?>
   @include("admin.kurum.ogrenci-takibi.ogrenci-detay")
   <?php 
} else {
   ?>
   @include("admin.kurum.ogrenci-takibi.list")
   <?php 
} ?>
