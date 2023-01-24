<button class="btn btn-primary btn-hero mb-10" type="button" data-toggle="collapse" data-target="#fitrele_alan" aria-expanded="false" aria-controls="fitrele_alan">{{e2("Sınava / Branşa Göre Filtrele ")}}</button>
<div class="collapse multi-collapse <?php if(getisset("filtre")) echo("show"); ?>" id="fitrele_alan">
      <div class="card card-body">
<form action="" method="get">
    <?php if(getisset("uid")) {
         ?>
         <input type="hidden" name="uid" value="{{get("uid")}}">
         <input type="hidden" name="t" value="analizlerim">
         <?php 
    } else {
         ?>
         <input type="hidden" name="t" value="analizlerim">
         <?php 
    } ?>
    
    <input type="hidden" name="filtre" value="ok">
    <div class="row">
        <?php if(isset($ogrenciler))  { 
            $ogrenci_array = [];
            $sinif_array = [];
            if(getisset("ogrenci")) {
                $ogrenci_array = $_GET['ogrenci'];
            }
            if(getisset("sinif")) {
                $sinif_array = $_GET['sinif'];
            }
          ?>
<?php $ogrenciler = kurum_users("Öğrenci");
             ?>
        <div class="col-md">
            <?php 
            $siniflar = kurum_siniflar(); ?>
            <div class="list-group" id="sinif_list">
             <?php foreach($siniflar AS $s) {
                  ?>
                  <label for="sinif{{str_slug($s)}}" class="list-group-item d-flex justify-content-between align-items-center">
                    <div>{{$s}}</div>
                    <span>
                        <input type="checkbox" name="sinif[]" <?php if(in_array($s,$sinif_array)) echo "checked"; ?> value="{{$s}}" id="sinif{{str_slug($s)}}"> 
                    </span>
                  </label>
                  <?php 
             } ?>
            </div>
        </div>
         <div class="col-md">
            
           
           
            <div class="list-group" id="ogrenci_list">
             <?php foreach($ogrenciler AS $s) {
                  ?>
                  <label for="ogrenci{{$s->id}}" class="list-group-item d-flex justify-content-between align-items-center">
                    <div>{{$s->name}} {{$s->surname}}</div>
                    <span>
                        <input type="checkbox" name="ogrenci[]" <?php if(in_array($s->id,$ogrenci_array)) echo "checked"; ?> value="{{$s->id}}" id="ogrenci{{$s->id}}"> 
                    </span>
                  </label>
                  <?php 
             } ?>
            </div>
         </div> 
         <?php } ?>
         <?php if(isset($sonuclar2))  { 
           ?>
         <div class="col-md">
             <ul class="list-group">
     <?php 
     $sinav = [];
     $brans = [];
     if(getisset("sinav")) {
         $sinav = $_GET['sinav'];
     }
     if(getisset("brans")) {
         $brans = $_GET['brans'];
     }
    ?>
             <?php foreach($sonuclar2 AS $s) {
                 ?>
                 <label for="sinav{{$s->id}}" class="list-group-item d-flex justify-content-between align-items-center">
                     <div>
                         {{$s->title}}
                         <br>
                         <small>{{date("d.m.Y",strtotime($s->created_at))}}</small>
 
                     </div>
                     
                     <span>
                         <input type="checkbox" name="sinav[]" <?php if(in_array($s->sinav_id,$sinav)) echo "checked"; ?> value="{{$s->sinav_id}}" id="sinav{{$s->sinav_id}}"> 
                     </span>
                 </label>
                 
                 <?php 
             } ?>
             </ul>
         </div> 
          <?php } ?>
<?php if(isset($branslar))  { 
  ?>
         <div class="col-md">
             <ul class="list-group">
             <?php foreach($branslar AS $b) {
                 $title = slug_to_title($b);
                 ?>
                 <label for="brans{{$b}}" class="list-group-item d-flex justify-content-between align-items-center">
                         <div>{{$title}}</div>
                         <span>
                             <input type="checkbox" name="brans[]" <?php if(in_array($b,$brans)) echo "checked"; ?>  value="{{$b}}" id="brans{{$b}}">
                         </span>
                 </label>
                 
                 <?php 
             } ?>
             </ul>
 
         </div> 
 <?php } ?>

        <div class="col-md-12 text-center">
            <button class="btn btn-danger btn-hero mx-auto mt-10">{{e2("Filtrele")}}</button>
        </div>
    </div>
        
</form>
</div>
    </div>