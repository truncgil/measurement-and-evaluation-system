<?php 
$u = u();
$users = kurum_users("Öğrenci");
$sonuclar = db("sonuclar")
->whereIn("uid",$u->alias_ids);
if(getisset("filtre")) {
    if(!getesit("title","")) {
        $sonuclar = $sonuclar->where("title","like","%".trim(get("title"))."%");
    }
    if(!getesit("sinav_tur","")) {
        $sonuclar = $sonuclar->where("title","like","%".get("sinav_tur")."%");
    }
    if(!getesit("sonuc_tur","")) {
        $sonuclar = $sonuclar->where("alt_type",get("sonuc_tur"));
    }
    if(!getesit("uid","")) {
        $sonuclar = $sonuclar->where("uid",get("uid"));
    }
    
} else {
    $sonuclar = $sonuclar->orderBy("id","DESC");
}

$sonuclar = $sonuclar->simplePaginate(20); ?>
<div class="row">
     {{col("col-12","Sınav Sonuç Raporu (Ayrıntılı Liste)")}} 
        <?php $sorgu = db("sonuclar")
        ->whereIn("uid",$u->alias_ids)
        //->join("sinavlar","sonuclar.kid","sinavlar.id")
        ->select("title")
        ->whereIn("type",['OPTIK','DENEME'])
        ->groupBy("sonuclar.title")
        ->orderBy("id","DESC")
        ->get();
        //dd($sorgu);
        ?>
        <form action="" method="get" target="_blank">
                <input type="hidden" name="ajax" value="sinav-sonuc-raporu">
                <select name="title" id="" class="form-control select2">
                    <?php foreach($sorgu AS $s)  { 
                    ?>
                    <option value="{{$s->title}}">{{$s->title}}</option> 
                    <?php } ?>
                </select> 
                <br>
                <br>
                <button class="btn btn-primary">{{e2("Sonuçları Ayrıntılı Listele")}}</button>
        </form>
     {{_col()}}
     {{col("col-12","Filtrele")}} 
        <form action="" class="get">
            <input type="hidden" name="t" value="sonuclar">
            <input type="hidden" name="filtre" value="ok">
        <div class="row">
                <div class="col-md-6">
                    {{e2("Sınav Türü")}}
                    <select name="sinav_tur" id="" class="form-control">
                        <option value="">{{e2("Tümü")}}</option>
                        <?php foreach(sinav_turleri() AS $s)  { 
                        ?>
                        <option value="{{$s}}" <?php if(getesit("sinav_tur",$s)) echo "selected"; ?>>{{$s}}</option> 
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6">
                    {{e2("Sonuç Türü")}}
                    <select name="sonuc_tur" id="" class="form-control">
                        <option value="">{{e2("Tümü")}}</option>
                        <?php foreach(sonuc_turleri() AS $s)  { 
                        ?>
                        <option value="{{$s}}" <?php if(getesit("sonuc_tur",$s)) echo "selected"; ?>>{{$s}}</option> 
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6">
                    {{e2("Sınav Adı")}}
                    <input type="text" name="title" value="{{get("title")}}" id="" class="form-control">
                </div>
                <div class="col-md-6">
                    {{e2("Öğrenci Adı")}}
                    <select name="uid" id="" class="form-control select2">
                        <option value="">{{e2("Tümü")}}</option>
                        <?php foreach($users AS $ogrenci)  { 
                        ?>
                        <option value="{{$ogrenci->id}}" <?php if(getesit("uid",$ogrenci->id)) echo "selected"; ?>>{{$ogrenci->name}} {{$ogrenci->surname}} ({{$ogrenci->alan}} / {{$ogrenci->sinif}})</option> 
                        <?php } ?>
                    </select>
                </div>
               
                <div class="col-12">
                    <div class="text-center mt-5">
                        <button class="btn btn-primary btn-hero">{{e2("Filtrele")}}</button>
                    </div>
                </div>
            </div>
        </form>
     {{_col()}}

     {{col("col-12","Sınav Sonuçları Listesi")}} 
        <div class="table-responsive">
            {{$sonuclar->appends($_GET)->links()}}
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th>{{e2("Adı Soyadı")}}</th>
                    <th>{{e2("Sınav Adı")}}</th>
                    <th>{{e2("Puan")}}</th>
                    <th>{{e2("İşlem")}}</th>
                </tr>
                <?php 
               
                foreach($sonuclar AS $s)  { 
                    
                    $ogrenci = isset($users[$s->uid]) ? $users[$s->uid]->name . " " . $users[$s->uid]->surname : "";
                ?>
                <tr>
                    <td>{{$ogrenci}}</td>
                    <td>{{$s->title}}</td>
                    <td>
                        <?php if($s->tyt!=0) {
                            ?>
                            <div class="badge badge-success">TYT: {{$s->tyt}}</div>
                            <?php 
                        } ?>
                        <?php if($s->yks_say!=0) {
                            ?>
                            <div class="badge badge-warning">YKS SAY: {{$s->yks_say}}</div>
                            <?php 
                        } ?>
                        <?php if($s->yks_soz!=0) {
                            ?>
                            <div class="badge badge-primary">YKS SÖZ: {{$s->yks_soz}}</div>
                            <?php 
                        } ?>
                        <?php if($s->yks_ea!=0) {
                            ?>
                            <div class="badge badge-danger">YKS EA: {{$s->yks_ea}}</div>
                            <?php 
                        } ?>
                        <?php if($s->lgs!=0) {
                            ?>
                            <div class="badge badge-success">LGS: {{$s->lgs}}</div>
                            <?php 
                        } ?>
                    </td>
                    <td>
                        <a href="?t=analizlerim&id={{$s->id}}" class="btn btn-primary">Detaylar</a>
                    </td>
                </tr> 
                <?php } ?>
            </table>
            {{$sonuclar->appends($_GET)->links()}}
        </div>
      
     {{_col()}}
</div>