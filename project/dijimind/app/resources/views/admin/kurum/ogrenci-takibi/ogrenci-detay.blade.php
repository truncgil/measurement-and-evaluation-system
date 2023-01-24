<?php 
$u = u();
$o = db("users")
->where("id",get("id"))->where("level","Öğrenci")->where("alias",$u->alias)->first(); ?>
<?php if($o)  { 

  ?>
 <h2></h2>
 <nav aria-label="breadcrumb">
   <ol class="breadcrumb">
     <li class="breadcrumb-item"><a href="#">{{e2("Öğrenci Takibi")}}</a></li>
     <li class="breadcrumb-item"><a href="{{$url}}&q={{$o->sinif}}. Sınıf">{{$o->sinif}}. Sınıf</a></li>
     <li class="breadcrumb-item"><a href="{{$url}}&q={{$o->sinif}}. Sınıf / {{$o->alan}}">{{$o->alan}}</a></li>
     <li class="breadcrumb-item active" aria-current="page">{{$o->name}} {{$o->surname}}</li>
   </ol>
 </nav> 

<div class="row">
    <?php $d = (Array) $o; ?>
    {{col("col-md-3 ogrenci text-center",$o->name . " " . $o->surname)}}
        @include("admin.kurum.ogrenci-takibi.ogrenci-info")
    {{_col()}}
    <div class="col-md-9">
        <div class="row">

        <?php 
        $s = db("users")
            ->selectRaw("(select count(id) from sinavlar_ogrenci where uid = users.id) AS kas")
            ->selectRaw("(select count(id) from sinavlar_ogrenci where uid = users.id AND complete=1) AS kas_complete")
            ->selectRaw("(select count(id) from todo where uid =  users.id AND koc IS NULL) AS todo")
            ->selectRaw("(select count(id) from todo where uid =  users.id AND koc IS NULL AND complete=1) AS todo_complete")
            ->selectRaw("(select count(id) from todo where uid =  users.id AND complete=1 AND koc IS NOT NULL) AS koc_complete")
            ->selectRaw("(select count(id) from todo where uid =  users.id AND koc IS NOT NULL) AS koc")
            //->selectRaw("select count(id) from todo where uid = ? AND complete=1",[$o->id])
            ->where("id",$o->id)
            ->first();
            $todo_yuzde = @round($s->todo_complete*100/$s->todo);
            $kas_yuzde = @round($s->kas_complete*100/$s->kas);
            $koc_yuzde = @round($s->koc_complete*100/$s->koc);
        ?>
     {{col("col-md-12","Görevleri")}} 
                {{e2("Görev")}} ({{$s->todo_complete}}/{{$s->todo}})
                <div class="progress">
                    <div class="progress-bar progress-bar-striped  bg-success progress-bar-animated" style="width:{{$todo_yuzde}}%">
                    </div>
                </div> 
                {{e2("KAS")}} ({{$s->kas_complete}}/{{$s->kas}})
                <div class="progress">
                    <div class="progress-bar progress-bar-striped  bg-warning progress-bar-animated" style="width:{{$kas_yuzde}}%">
                    </div>
                </div> 
                {{e2("KGT")}} ({{$s->koc_complete}}/{{$s->koc}})
                <div class="progress">
                    <div class="progress-bar progress-bar-striped  bg-info progress-bar-animated" style="width:{{$koc_yuzde}}%">
                    </div>
                </div> 
     {{_col()}}
      {{col("col-md-12","Çalıştığı Kaynaklar")}} 
       
      {{_col()}}
      </div>
      </div>
      
</div>
@include("admin.scrum.board")
 <?php } else {
     bilgi("Öğrenci bilgilerine erişiminiz yoktur. Ya öğrenci numarasını yalnış girdiniz. Ya da öğrenci etki alanınızın dışındadır.");
 } ?>