<div class="row">
    {{col("col-md-12","Öğrencileriniz")}}
 
    <?php 
    $k = 0;
    foreach($u->ogrenciler AS $o) {
    //    print2($o);
         ?>
         <a href="#" onclick="$.get('?ogrenci={{$k}}',function(){location.reload();});" class="btn <?php if(oturumesit("select_ogrenci",$k)) echo "btn-primary"; else "btn-outer-primary"; ?>">
            <img class="img-avatar img-avatar-thumb p-5 mb-5 d-block" src="{{profile_pic(64,$o)}}" alt=""> 
            <br>
            <br>
            <strong>{{$o->name}} {{$o->surname}}</strong> <br>
            <span >{{ed($o->sinif,"YKS")}} / {{$o->alan}}</span>
         </a>
         <?php 
    $k++; } ?>
    {{_col()}}
</div>