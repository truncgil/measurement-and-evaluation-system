<?php if($item->brans!="") {
  $color_name = $item->brans;
} else {
  $color_name = $item->kid;
} ?>
<div class="scrumboard-item p-10" 
style="background-color:hsl(<?php echo unique_color($color_name) ?>deg 40% 90%)"
>
  
    <div class="scrumboard-item-content">
        
             <div class="badge badge-danger" title="{{e2("Önemli olarak işaretlenmiş")}}">
               {{$item->level}}
             </div>
            
        <?php $item_array = (Array) $item ?>
        <div class="d-none">{{str_slug(implode(",",$item_array))}}</div>
        <?php if($item->brans!="")  { 
         ?>
        <div class="badge badge-info">
           {{$item->brans}}
        </div> 
        <?php } ?>
       <?php if($item->konu!="")  { 
         ?>
        <div class="badge badge-warning">
          <?php echo ($item->konu) ?>
        </div> 
        <?php } ?>
       

       <hr>
       {{$item->title}}
        <br>
        <?php if($item->complete==1) {
          if($item->sonuc_id!="")  { 
              ?>
              <div class="btn-group text-center">
                 <a href="?t=sinav-sonuc&id={{$item->sonuc_id}}" class="btn btn-success" title="{{e2("Sonuç Özeti")}}">🔠</a>
                 <a href="?t=analizlerim&id={{$item->sonuc_id}}" class="btn btn-primary" title="{{e2("Detaylı Analizler ve Cevaplar")}}">📊</a>
              </div> 
           <?php } ?>
             <?php 
        } ?>
        <?php if($sinava_basla) {
             ?>
             <div class="text-center">
                    <div class="btn-group">
                    <a href="?t=kas-sinavlarim&sinav-olustur={{$item->id}}" title="{{e2("Online Olarak Sınava Başla")}}" class="btn btn-primary">
                        <i class="fa fa-globe"></i>     
                        
                    </a>

                    <a href="?t=kas-sinavlarim&sinav-olustur={{$item->id}}&pdf" title="{{e2("Kağıt Üzerinde Yazdırarak Sınava Başla")}}" class="btn btn-danger">
                        <i class="fa fa-file-pdf"></i> 
                        
                    </a>
                    </div>
             </div>

             <?php 
        } ?>
        <?php if($item->complete==1)  { 
          ?>
         <small class="badge badge-success d-none"><i class="fa fa-check-circle"></i> {{df($item->updated_at)}}</small> 
         <?php } ?>
         <?php if(!is_null($item->ajanda)) {
           ?>
           <small class="badge badge-success"><i class="fa fa-calendar"></i></small>
           <?php 
         } ?>
        
    </div>
   

</div>