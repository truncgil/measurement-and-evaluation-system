<?php 
if($junior_percent<0) $junior_percent = 0; 
if($pratisyen_percent<0) $pratisyen_percent = 0; 
if($master_percent<0) $master_percent = 0; 

?>
<div class="row">
  <div class="col-md-4">
      <div class="progress taksonomi-progress">
        <div class="progress-bar bg-junior" style="width:{{$junior_percent}}%">
          {{e2("Junior")}} 
          <br>
          %{{$junior_percent}}
        </div>
      </div>  
      
      {{e2("Junior")}} 
      <?php 
      if(isset($level_dyb_total['Junior'])) {  
       
         $dyb = $level_dyb_total['Junior'];
         ?>
          <?php } ?>
         @include("admin.ogrenci.analiz.dyb-progress")   
      
  </div>
  <div class="col-md-4">
      <div class="progress taksonomi-progress">
        <div class="progress-bar bg-pratisyen" style="width:{{$pratisyen_percent}}%">
          {{e2("Pratisyen")}}
          <br>
          %{{$pratisyen_percent}}
        </div>
      </div>
      {{e2("Pratisyen")}} 
      <?php 
       if(isset($level_dyb_total['Pratisyen'])) {  
        $dyb = $level_dyb_total['Pratisyen'];
        ?>
        <?php } ?>
        @include("admin.ogrenci.analiz.dyb-progress")  
        
  </div>
  <div class="col-md-4">
      <div class="progress taksonomi-progress">
        <div class="progress-bar bg-master" style="width:{{$master_percent}}%">
        {{e2("Master")}}
        <br>
        %{{$master_percent}}
        </div>
      </div>
      {{e2("Master")}} 
      <?php 
      if(isset($level_dyb_total['Master'])) {  
        $dyb = $level_dyb_total['Master'];
        ?>
         <?php } ?>
        @include("admin.ogrenci.analiz.dyb-progress") 
       
  </div>
</div>