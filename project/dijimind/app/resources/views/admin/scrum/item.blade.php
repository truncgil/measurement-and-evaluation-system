<?php if($item->brans!="") {
  $color_name = $item->brans;
} else {
  $color_name = $item->kid;
} ?>
<div class="scrumboard-item" ondblclick="detail({{$item->id}},'{{$item->title}}')" id="{{$item->id}}" 
style="background-color:hsl(<?php echo unique_color($color_name) ?>deg 40% 90%)"
>
    <div class="scrumboard-item-options">
        <a class="scrumboard-item-handler cursor-pointer btn btn-sm btn-alt-warning" href="javascript:void(0)">
            <i class="fa fa-hand-grab-o"></i>
        </a>
        
        <?php if($item->kid=="")  { 
          ?>
         <button class="btn btn-sm btn-alt-warning" data-id="{{$item->id}}" data-toggle="sb-item-remove">
             <i class="fa fa-close"></i>
         </button> 
         <?php } ?>

        
    </div>
    <div class="scrumboard-item-content">
        <?php if($item->star==1) {
             ?>
             <div class="badge badge-danger" title="{{e2("Önemli olarak işaretlenmiş")}}">
                <i class="fa fa-star"></i>
             </div>
             <?php 
        } ?>
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
          <?php  echo ($item->konu) ?>
        </div> 
        <?php } ?>
       

       <hr>
       {{$item->title}}
        <br>
        <?php if($item->bas!="") {
             ?>
             <div class="badge badge-success" title="{{e2("Sayfa aralığı")}}"><i class="fa fa-book"></i> {{$item->bas}} - {{$item->son}}</div>
             <?php 
        } ?>
        <?php if($item->complete==1)  { 
          ?>
         <small class="badge badge-success"><i class="fa fa-check-circle"></i> {{df($item->updated_at)}}</small> 
         <?php } ?>
         <?php if(!is_null($item->ajanda)) {
           ?>
           <small class="badge badge-success"><i class="fa fa-calendar"></i></small>
           <?php 
         } ?>
        
    </div>
   

</div>