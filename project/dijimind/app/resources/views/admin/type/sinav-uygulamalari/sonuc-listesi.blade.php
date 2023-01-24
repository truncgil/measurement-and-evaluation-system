<?php $sinav = table_to_array("sinavlar","id");
$user = table_to_array2("users","id");
?>
<div class="content">
<div class="row">
    {{col("col-md-12","Filtrele")}}
    <?php $siniflar = array();
    $getturler = array();
    if(getisset("siniflar")) {
        $siniflar = $_GET['siniflar'];
    }
    if(getisset("turler")) {
        $getturler = $_GET['turler'];
    }
    ?>
                    <form action="" method="get">
                        <input type="hidden" name="filtre" value="ok">
                        <?php if($u->level == "Kurum") {
                             ?>
                             <input type="hidden" name="t" value="optik-okuma">
                             <?php 
                        } ?>
                        <div class="row">
                            <div class="col-md-3">
                                {{e2("Sınıflar")}}
                                <select name="siniflar[]" id="siniflar" class="form-control select2" multiple>
                                    <?php for($k=2;$k<=12;$k++)  { 
                                        ?>
                                    <option value="{{$k}}" <?php if(in_array($k,$siniflar)) echo "selected"; ?>>{{$k}}. {{e2("Sınıf")}}</option> 
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                {{e2("Sınav Türü")}}
                                <select name="turler[]" id="turler" class="form-control select2" multiple>
                                    <?php $turler = explode(",","LGS,SBS,TYT,AYT"); foreach($turler AS $t) { 
                                      ?>
                                     <option value="{{$t}}" <?php if(in_array($t,$getturler)) echo "selected"; ?>>{{$t}}</option> 
                                     <?php } ?>

                                </select>
                            </div>
                            <div class="col-md-3">
                                {{e2("Sınav Adı")}}
                                    <input type="text" class="form-control" value="{{get("title")}}" name="title" id="">
                            </div>
                            <div class="col-md-3">
                                {{e2("Sınav Tarih Aralığı")}}
                                <div class="input-group">
                                    <input type="date" class="form-control" value="{{get("d1")}}" name="d1" id="">
                                    <input type="date" class="form-control" value="{{get("d2")}}"   name="d2" id="">
                                </div>
                            </div>
                            <div class="col-12 mt-10 text-center">
                                <button class="btn btn-primary">{{e2("Filtrele")}}</button>
                            </div>
                        </div>
                    </form>
    {{_col()}}
</div>
    <div class="block">
            <div class="block-header block-header-default" >
                <h3 class="block-title"><i class="fa fa-plus"></i> {{e2("Son Okunan Sınavlar")}}</h3>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                <?php 
                if(getisset("uygulama-sil")) {
                    /*
                    db("sonuclar")
                    ->where("title",get("uygulama-sil"))
                    ->delete();
                    */
                }
                $sorgu = db2("sinavlar")
                ->select("*",'title')
                ->selectRaw("(select count(sinav_id) from sonuclar where sinav_id = sinavlar.id) AS total")
                ->whereIn("uid",$u->alias_ids)
                ->groupBy('title')
                ->orderBy("id","DESC");
                if(getisset("turler")) {
                    $getturler = $_GET['turler'];
                  
                    $sorgu = $sorgu->where(function($query) use($getturler){
                        foreach($getturler AS $tur) {
                            $query = $query->orWhere("title","like","%$tur%");
                        }
                        
                    });
                }
                if(getisset("siniflar")) {
                    $getsiniflar = $_GET['siniflar'];
                 //   print2($getsinavlar);
                    $sorgu = $sorgu->where(function($query) use($getsiniflar){
                        foreach($getsiniflar AS $sinif) {
                            $query = $query->orWhere("level","$sinif");
                        }
                        
                    });
                }
                if(!getesit("d1","")) {
                    $sorgu = $sorgu->whereBetween("date",[get("d1"),get("d2")]);
                }
                if(!getesit("title","")) {
                    $sorgu = $sorgu->where("title","like","%{$_GET['title']}%");
                }
                $sorgu = $sorgu->simplePaginate(20);
               // dd($sorgu);
               // print2($sorgu); exit();
                ?>
                    <table class="table">
                        <tr>
                            <th>Sınav Adı</th>
                            <th>Tamamlayan</th>
                            <th>Uygulama Zamanı</th>
                            <th>Başlangıç Zamanı</th>
                            <th>Bitiş Zamanı</th>
                        
                            <th>İşlem</th>
                        </tr>
                        <?php foreach($sorgu AS $s) { 
                            if(isset($sinav[$s->sinav_id])) {
                            $this_sinav = $sinav[$s->sinav_id];
                            $this_sinav_json = j($this_sinav->json);
                            $this_user = $user[$s->uid]; 
                            ?>
                        <tr id="t{{$s->id}}">
                            <td>{{$this_sinav->title}}</td>
                            <td>
                            
                                <div class="progress">
                                    <div class="progress-bar" style="width:{{$s->total}}%">{{$s->total}}</div>
                                </div>
                            </td>
                            <td><input type="datetime-local" name="created_at" class="form-control edit" table="sonuclar"  value="{{date('Y-m-d\TH:i:s', strtotime($s->created_at))}}" id="{{$s->id}}"></td>
                            <td>{{df($this_sinav_json['date'])}}</td>
                            <td>{{df($this_sinav_json['date'])}}</td>
                          
                            <td>
                                <a href="?id={{$s->title}}" class="btn btn-primary">Sonuç Listesi</a>
                                <a href="" class="btn btn-warning">Raporlar</a>
                                <a href="?uygulama-sil={{$s->title}}" ajax="#t{{$s->id}}" teyit="{{e2("Bu uygulamanın sonuçları silinecektir. Bu işlem geri alınamaz")}}" class="btn btn-danger">{{e2("Sil")}}</a>
                            </td>
                        </tr>
                        <?php }
                        }
                        ?>

                    </table>
                </div>
                {{$sorgu->appends($_GET)->links()}}
            </div>
    </div>
</div>