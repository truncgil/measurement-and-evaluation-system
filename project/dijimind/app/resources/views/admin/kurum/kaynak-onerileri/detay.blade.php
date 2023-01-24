
<?php 
$ogrenciler = kurum_users("Öğrenci");
$siniflar = kurum_siniflar();
$kaynak = db("kaynaklar")->where("id",get("id"))->first();
if(strpos($kaynak->brans,"LGS")!==false) {
  $kitap_type = "LGS";
} else {
  $kitap_type = "YKS";
}
$j = j($kaynak->json);
?>
<div class="row">
  <div class="col-12">
  <nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="?t=kaynak-onerileri">{{e2("Kaynak Önerileri")}}</a></li>
    <li class="breadcrumb-item"><a href="?t=kaynak-onerileri&q={{$kaynak->brans}}">{{$kaynak->brans}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$kaynak->title}}</li>
  </ol>
</nav>
  </div>
     {{col("col-md-5 text-center",$kaynak->brans . " " .$kaynak->title)}} 
        <img src="{{picture2($kaynak->cover,256)}}" class="img-fluid" alt="{{$kaynak->title}}">
        <div class="table-responsive" style="height:600px">
                <table class="table table-bordered table-hover">
                    <?php 
                    $a = 0;
                    foreach($j['konu'] AS $konu)  { 
                      ?>
                     <tr>
                         <td><small>{{$konu}}</small></td>
                         <td width="100" class="text-center"><small>{{@$j['bas'][$a]}}..{{@$j['son'][$a]}}</small></td>
                     </tr> 
                     <?php $a++; } ?>
                </table>
               </div>
     {{_col()}}
     <div class="col-md-7">
        <div class="row">
            {{col("col-md-12","Öğrenciye Bu Kitaptan Konu Görevi Ata")}} 
            <?php if(getisset("kaynak-ata")) {
              if(!postesit("sinif","")) {
                $ogrenciSay = 0;
                foreach($ogrenciler AS $ogrenci) {
                  
                  if(postesit("sinif",$ogrenci->sinif . " " . $ogrenci->sube)) {

                    $uid =  $ogrenci->id;
                    setGorev([
                      's' => 1,
                      'title' => $kaynak->brans . ' 📖 Kitap çalışması ('.$kaynak->title.')',
                      'level' => 'Junior',
                      'type' => 'Kaynak Önerisi',
                      'brans' => $kaynak->brans,
                      'konu' => $j['konu'][post("sira")],
                      'kid' => $kaynak->id,
                      'koc' => $u->id,
                      'son' => $j['son'][post("sira")],
                      'bas' => $j['bas'][post("sira")],
                      'uid' => $uid
                    ]);
                    $ogrenciSay++;

                  }
                  
                }
                bilgi("{$_POST['sinif']} sınıfındaki toplam $ogrenciSay öğrenciye {$j['konu'][post("sira")]} kaynak görevi ataması yapılmıştır.");

              } else {
                if(postisset("uid")) {
                  foreach($_POST['uid'] AS $uid)  { 
               
                    setGorev([
                      's' => 1,
                      'title' => $kaynak->brans . ' 📖 Kitap çalışması ('.$kaynak->title.')',
                      'level' => 'Junior',
                      'type' => 'Kaynak Önerisi',
                      'brans' => $kaynak->brans,
                      'konu' => $j['konu'][post("sira")],
                      'kid' => $kaynak->id,
                      'koc' => $u->id,
                      'son' => $j['son'][post("sira")],
                      'bas' => $j['bas'][post("sira")],
                      'uid' => $uid
                    ]);
                    bilgi("$uid nolu öğrenciye {$j['konu'][post("sira")]} görev ataması yapılmıştır."); 
                }
                
              }
              
              } 
            } ?>
            <form action="?t=kaynak-onerileri&id={{get("id")}}&kaynak-ata" method="post">
                  @csrf
                  {{e2("Sınıf Seçiniz")}}
                  <select name="sinif" id="" class="form-control select2">
                    <option value="">{{e2("Seçiniz")}}</option>
                    <?php foreach($siniflar AS $sinif)  { 
                       ?>
                     <option value="{{$sinif}}">{{$sinif}}</option> 
                     <?php } ?>
                  </select>
                  {{e2("Öğrenci Adı")}}
                    <select name="uid[]" id="" class="form-control select2" multiple>
                      <option value="">{{e2("Öğrenci Seçiniz")}}</option>
                      <?php foreach($ogrenciler AS $o)  { 
                        $sinav_type = sinav_type($o->sinif);
                        if($sinav_type==$kitap_type)  { 
                        ?>
                      <option value="{{$o->id}}">{{$o->name}} {{$o->surname}}</option> 
                      <?php } ?>
                      <?php } ?>
                    </select>

                  {{e2("Atanacak Konu")}}
                      <select name="sira" required id="" class="form-control select2">
                        <option value="">{{e2("Konu Seçiniz")}}</option>
                        <?php 
                        $a = 0;
                        foreach($j['konu'] AS $k)  { 
                          ?>
                        <option value="{{$a}}">{{$k}} ({{@$j['bas'][$a]}} - {{@$j['son'][$a]}})</option> 
                        <?php $a++; } ?>
                      </select>
                      <button type="submit" class="mt-3 btn btn-hero btn-primary">{{e2("Kaynak Önerisi Görevini Ata")}}</button>
                  </form>
            {{_col()}}
            {{col("col-md-12","Atanan Öğrenciler")}} 
            <?php $todo = db("todo")
                            ->select("uid","konu","bas","son")
                            ->selectRaw("SUM(IF(complete=1,1,0)) AS tamamlanan")
                            ->selectRaw("SUM(IF(complete=2,1,0)) AS devam_eden")
                            ->selectRaw("SUM(IF(complete=0,1,0)) AS todo")
                            ->selectRaw("SUM(1) AS toplam")
                            ->where("type","Kaynak Önerisi")->where("kid",$kaynak->id)
                            ->orderBy("id","DESC")
                            ->groupBy("uid")
                            ->get();
            ?>
                  <table class="table table-striped table-hover table-bordered">
                      <tr>
                          <th>{{e2("Öğrenci Adı")}}</th>
                          <th>{{e2("Konu Adedi")}}</th>
                          <th>{{e2("Durum")}}</th>
                      </tr>
                      <?php foreach($todo AS $t)  { 
                        if(isset($ogrenciler[$t->uid]))  { 
                          $percent = round(100*($t->tamamlanan) / $t->toplam,0); 
                          $ogrenci = $ogrenciler[$t->uid];
                          
                           
 
                         ?>
                            <tr>
                                <td>{{$ogrenci->name}} {{$ogrenci->surname}}</td>
                                <td>
                                <div class="badge badge-primary" title="{{e2("Yapılacak")}}">{{$t->todo}}</div>  
                                  <div class="badge badge-info" title="{{e2("Devam Eden")}}">{{$t->devam_eden}}</div>  
                                  <div class="badge badge-success" title="{{e2("Tamamlanan")}}">{{$t->tamamlanan}}</div>  
                                  <div class="badge badge-danger" title="{{e2("Tamamlanan")}}">{{$t->toplam}}</div>  
                                
                                </td>
                                <td>
                                    <div class="progress m-1">
                                        <div class="progress-bar bg-success" role="progressbar" style="min-width:unset;width: {{$percent}}%" ></div>
                                    </div>
                                </td>
                            </tr>   
                          
                        <?php } ?>
                      <?php } ?>
                  </table>
            {{_col()}}

        </div>
      </div>
</div>