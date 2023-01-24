<?php if(isset($ogrenci)) {
    $u = $ogrenci;
}
?>
<?php if(isset($u->name))  { 
  ?>
 <a class="block text-center  " href="#">
 
             <div class="block-header bg-gd-dusk block-content-full block-content-sm">
                 <span class="text-white text-center">{{$u->name}} {{$u->surname}}</span>
             </div>
             <div class="block-content block-content-full bg-primary-lighter rounded-0">
                 <img class="img-avatar img-avatar-thumb" src="{{profile_pic(128,$u)}}" alt="">
             </div>
             <div class="block-content">
                 <div class="row items-push text-center">
                     <div class="col-6">
                         <div class="mb-5"><i class="si si-trophy  fa-2x text-primary"></i></div>
                         <div class="font-size-sm text-muted">{{e2("En Aktif Öğrenci")}}</div>
                     </div>
                     <div class="col-6">
                         <div class="mb-5"><i class="si si-star fa-2x text-primary"></i></div>
                         <div class="font-size-sm text-muted">{{get_score($u->id)}}</div>
                     </div>
                     <div class="col-6">
                         <div class="mb-5"><i class="si si-folder  fa-2x text-primary"></i></div>
                         <div class="font-size-sm text-muted">{{$u->alan}} / {{$u->tur}}</div>
                     </div>
                     <div class="col-6">
                         <div class="mb-5"><i class="si si-layers fa-2x text-primary"></i></div>
                         <div class="font-size-sm text-muted">{{$u->sinif}} / {{$u->sube}}</div>
                     </div>
                 </div>
             </div>
            
         </a> 
 <?php } ?>