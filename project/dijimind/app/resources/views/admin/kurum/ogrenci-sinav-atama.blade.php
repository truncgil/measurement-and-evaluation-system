<?php $ogrenciler = kurum_users("Öğrenci"); 
 ?>
<div class="row">
    {{col("col-12","Yeni Bireysel Sınav Ata")}} 
    <?php if(getisset("add")) {
        foreach($_POST['uid'] AS $uid) {
            if($uid!="")  { 
                $varmi = db("sinav_yetkileri")->where("uid",$uid)->where("sinav",post("sinav"))->first();
                if(is_null($varmi)) {
                    ekle2([
                        'uid' => $uid,
                        'uid2' => $u->id,
                        'tarih' => date("Y-m-d"),
                        'sinav' => post("sinav")
                    ],"sinav_yetkileri");
                    bilgi("$uid nolu öğrenci {$_POST['sinav']} ile yetkilendirilmiştir");
                } else {
                    bilgi("Bu sınav $uid nolu öğrenciye zaten daha önce yetkilendirilmiştir","warning");
                }  
            } 
        }
         
        
    } ?>
        <form action="?t=ogrenci-sinav-atama&add" method="post">
            @csrf
            Öğrenci Seçiniz
            <select name="uid[]" id="" required class="form-control select2" multiple>
                <option value="">Seçiniz</option>
                <?php foreach($ogrenciler AS $o)  { 
                  ?>
                 <option value="{{$o->id}}">{{$o->name}} {{$o->surname}}</option> 
                 <?php } ?>
            </select>
            Sınav Seçiniz
            <select name="sinav" required id="" class="form-control select2">
                <option value="">Seçiniz</option>
                <?php $sinavlar = db("sinavlar")
                ->where(function($query) {
                    $query->orWhere("title","like","%OPTİK ATAMA%");
                    $query->orWhere("y","1");
                })
                
                
                ->get(); 
                
                foreach($sinavlar AS $s)  { 
                  ?>
                 <option value="{{$s->title}}">{{$s->title}}</option> 
                 <?php } ?>
            </select>
            <br>
            <br>
            <button class="btn btn-primary btn-hero">{{e2("Yetkilendir")}}</button>
        </form>
    {{_col()}}
    {{col("col-12","Daha Önce Atanmış Sınavlar")}} 
    <?php if(getisset("delete")) {
       db("sinav_yetkileri")
            ->whereIn("uid",$u->alias_ids)
            ->where("id",get("delete"))
            ->delete();

        bilgi("{$_GET['delete']} nolu sınav ataması silindi.");
    } ?>
                    <?php $yetkiler = db("sinav_yetkileri")
                        ->whereIn("uid",$u->alias_ids)
                        ->where("uid2", $u->id)
                        ->orderBy("id","DESC")
                        ->simplePaginate(20); ?>
{{$yetkiler->links()}}
        <table class="table">
            <tr>
                <th>{{e2("Sınav Adı")}}</th>
                <th>{{e2("Öğrenci")}}</th>
                <th>{{e2("Tarih")}}</th>
                <th>{{e2("İşlem")}}</th>
            </tr>
            <?php foreach($yetkiler AS $y)  {
                 
              ?>
             <tr>
                 <td>{{$y->sinav}}</td>
                 <td>{{@$ogrenciler[$y->uid]->name}} {{@$ogrenciler[$y->uid]->surname}}</td>
                 <td>{{df($y->created_at)}}</td>
                 <td><a href="login-by-id?id={{$y->uid}}" class="btn btn-primary">🔏</a>
                <a href="{{getisset("t") ? "?t=".get("t") . "&" : "?"}}delete={{$y->id}}" teyit="Emin misiniz?" class="btn btn-danger">❌</a>
                </td>
             </tr> 
             <?php } ?>
        </table>
        {{$yetkiler->links()}}
    {{_col()}}
</div>