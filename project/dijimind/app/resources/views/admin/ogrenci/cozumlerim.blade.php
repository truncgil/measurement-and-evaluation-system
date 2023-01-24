<?php 
if(isset($veli)) {
    if(oturumisset("ogrenci")) {
        $u = select_ogrenci();
    } else {
        $u = u();
    }
    
} else {
    if(isset($ogrenci)) {
        $u = $ogrenci;
    } else {
        $u = u();
    }
}

 ?>
<div class="row">
    <?php if(getisset("id")) {
        $sonuc = db("sonuclar")
        ->where("id",get("id"))
        ->where(function($query) use($u) {
            $query = $query->orWhere("uid",$u->id);
          //  $query = $query->orWhere("tc_kimlik_no",$u->email);
        })
        ->first();
        
        
        if($sonuc) {
          //  print2($sonuc);
            $j  = j($sonuc->analiz);
            
        } else {
            yonlendir("?t=analizlerim");
        }
        
        
         ?>
         <div class="col-12">
            <h1>{{$sonuc->title}}</h1>
         </div>

          {{col("col-12 cozumler","Çözümler ve Cevaplar",4)}} 
            <div class="row">
                @include("admin.ogrenci.analiz.cozumler")
            </div>
          {{_col()}}

         <?php 
    } 
        
      ?>
</div>