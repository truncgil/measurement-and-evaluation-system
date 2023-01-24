<?php $path = "admin.type.sinav-uygulamalari"; ?>
<?php if(getisset("id")) {
     ?>
     @include("$path.sonuc-detaylari")
     <?php 
} else {
     ?>
     @include("$path.yeni-sonuc")
     <?php 
} ?>


@include("admin.type.sinav-sonuc-listesi")
