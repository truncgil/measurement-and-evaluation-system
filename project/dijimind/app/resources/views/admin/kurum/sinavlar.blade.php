<?php $path = "admin.type.sinavlar"; ?>
<?php if(getisset("kullan")) {
     ?>
        @include("$path.yeni-sinav-sablon")
     <?php 
} else {
     ?>
        @include("$path.kazanimlar")

        <div class="row text-center">
            <div class="col-12">
                {{bilgi("Lütfen Eklemek İstediğiniz Sınavı Seçin")}}
            </div>
            <?php $sablonlar = db("contents")
                                    ->where("type","Kurum Sınav Şablonları")
                                    ->orderBy("s","ASC")
                                    ->get();
            foreach($sablonlar AS $s)  { 
             
             ?>
             {{col("col-md-3",$s->title)}} 
                 <a href="?t=sinavlar&kullan={{$s->json}}" class="btn btn-primary btn-hero"><i class="fa fa-hand-point-right"></i> Kullan</a>
                    <?php $sinav_id = $s->json ?>
                     @include("admin.kurum.sinavlar.sinav-detaylari")
             {{_col()}} 
             <?php } ?>
            
                
        </div>
        <div class="row">
       @include("admin.type.sinavlar.sinav-listesi")
       </div>
     
     <?php 
} ?>