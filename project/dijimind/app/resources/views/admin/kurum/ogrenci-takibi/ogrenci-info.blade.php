<div class="d-none">{{str_slug(implode(",",$d))}}</div> 
<img src="{{profile_pic(128,$o)}}" class="img-avatar img-avatar-thumb" alt="">
<br>
<div class="btn-group">
    <a href="?t=dijichat&id={{$o->id}}" class="btn btn-primary" title="{{e2("DijiChat ile Yazış")}}"><i class="fa fa-comment"></i></a>
    <a href="?t=soru-takibi&id={{$o->id}}" class="btn btn-danger" title="{{e2("Soru Takibi")}}"><i class="fa fa-question"></i></a>
    <a href="?t=ajanda&id={{$o->id}}" class="btn btn-success" title="{{e2("Takvim")}}"><i class="fa fa-calendar"></i></a>
    <a href="{{$url}}&id={{$o->id}}" class="btn btn-info" title="{{e2("Görevleri")}}"><i class="fa fa-check-double"></i></a>
    <a href="?t=kas-sinavlarim2&id={{$o->id}}" class="btn btn-warning" title="{{e2("Kas Sınavları")}}"><i class="fa fa-pen"></i></a>
    
    
</div>
<div class="btn-group mt-5">
    <a href="login-by-id?id={{$o->id}}" target="_blank" class="btn btn-warning" title="{{e2("Giriş Yap")}}"><i class="fa fa-lock"></i></a>
    <a href="?t=ogrenciler&user-detay={{$o->id}}" target="_blank" class="btn btn-danger" title="{{e2("Detaylar")}}"><i class="fa fa-list"></i></a>
    <a href="?t=analizlerim&uid={{$o->id}}" target="_blank" class="btn btn-primary" title="{{e2("Analizler")}}"><i class="fa fa-chart-pie"></i></a>
</div>
<br>
{{$o->id}} / {{$o->sinif}}. Sınıf / {{$o->alan}} 