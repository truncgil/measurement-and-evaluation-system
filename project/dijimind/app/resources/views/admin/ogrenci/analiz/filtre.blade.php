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
        <div class="col-md-12">
            Sınıf
            <?php 
            $siniflar = kurum_siniflar(); ?>
            <select name="sinif[]" id="" class="form-control select2" multiple>
            <?php foreach($siniflar AS $s) {
                  ?>
                <option value="{{$s}}" <?php if(in_array($s,$sinif_array)) echo "selected"; ?>>{{$s}}</option>
            <?php } ?>
            </select>
            
        </div>
         <div class="col-md-12">
            Öğrenci
           
            <select name="ogrenci[]" id="" class="form-control select2" multiple>
                <?php foreach($ogrenciler AS $s) {
                    ?>
                    <option value="{{$s->id}}" <?php if(in_array($s->id,$ogrenci_array)) echo "selected"; ?>>{{$s->name}} {{$s->surname}} ({{$s->sinif}} / {{$s->alan}} / {{$s->sube}})</option>
                <?php } ?>
            </select>
            
         </div> 
         <?php } ?>
         <?php if(isset($sonuclar2))  { 
           ?>


         <div class="col-md-12">
          
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
            Sınav
            <select  name="sinav[]" id="" class="form-control select2" multiple>
                <?php foreach($sonuclar2 AS $s) {
                    ?>
                    <option value="{{$s->sinav_id}}" <?php if(in_array($s->sinav_id,$sinav)) echo "selected"; ?>>
                    {{$s->title}}
                    {{date("d.m.Y",strtotime($s->created_at))}}
                </option>
                <?php } ?>
            </select>

            
         </div> 
          <?php } ?>
<?php if(isset($branslar))  { 
  ?>
         <div class="col-md-12">
            Ders
            <select  name="brans[]" id="" class="form-control select2" multiple>
                <?php foreach($branslar AS $b) { 
                    $title = slug_to_title($b);
                    ?>
                    <option value="{{$b}}" <?php if(in_array($b,$brans)) echo "selected"; ?>>
                    {{$title}}
                </option>
                <?php } ?>
            </select>
             
 
         </div> 
 <?php } ?>

        <div class="col-md-12 text-center">
            <button class="btn btn-danger btn-hero mx-auto mt-10">{{e2("Filtrele")}}</button>
        </div>
    </div>
        
</form>
</div>
    </div>