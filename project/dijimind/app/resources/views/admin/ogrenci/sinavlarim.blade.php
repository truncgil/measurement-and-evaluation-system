{{onay_gerekli()}}
<div class="row">
<?php $path = "admin.ogrenci.sinavlarim"; ?>
<?php if(!$veli)  { 
  ?>
  <?php $deneme_sinavlari = db("contents")->where("type","Deneme Sınavları")
  ->where("y",1)
  ->where(function($query) use($u){
     $query->orWhereNull("alias");
     if($u->alias!="") {
        $query->orWhere("alias","like","%{$u->alias}%");
     }
     
  })
  ->get();
  //contents("Deneme Sınavları"); 
  ?>
  <?php foreach($deneme_sinavlari AS $ds)  { 
    $siniflar = explode(",",$ds->json);
    if(in_array($u->sinif,$siniflar))  { 
     
     ?>
       {{col("col-md-6",$ds->title)}}
       
       <div class="text-center">
           <a href="{{url("admin?t=sinav-olustur-deneme-sinavi&id=".$ds->tkid)}}"  >
               <img src="{{picture2($ds->cover,256)}}" width="256" class="img-fluid" alt="">
             
               <p>{{e2($ds->html)}}</p>
           </a>
       </div>
   {{_col()}}   
     <?php } ?>
   <?php } ?>
  
 
     {{col("col-md-6 d-none","Ücretsiz Online Sınav",2)}}
         <div class="text-center">
             <a href="{{url("admin?t=sinav-olustur")}}"  >
                 <img src="{{url("assets/img/kagit.png")}}" width="128" class="img-fluid" alt="">
                 
                 <p>{{e2("Sınavınızı çevrimiçi olarak çözün ve anında sonucunuzu alın")}}</p>
             </a>
         </div>
     {{_col()}} 
     </div>
    
     <?php } ?>
     <div class="row">
    @include("$path.sinavlar")
    </div>
    
    

