<?php 
$sonuclar = db("sonuclar")->where("title",get("id"))->get();
if(getisset("sonuc-sil")) {
   // db("sonuclar")->where("id",get("sonuc-sil"))->delete();
}
$ogrenciler = table_to_array2("users","id");

?>
<div class="content">
    <div class="row">
        {{col("col-md-12","Sonuc Detayları")}}
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th>{{e2("Sonuc ID")}}</th>
                    <th>{{e2("Öğrenci Adı")}}</th>
                    <th>{{e2("TC Kimlik NO")}}</th>
                    <th>{{e2("Puan")}}</th>
                    <th>{{e2("Netler")}}</th>
                    <th>{{e2("Cevaplar")}}</th>
                    <th>{{e2("Konsol Verisi")}}</th>
                    <th>{{e2("İşlemler")}}</th>
                </tr>
                <?php foreach($sonuclar AS $s) { 
                    $ogrenci = @$ogrenciler[$s->uid];
                    $j = j($s->analiz);
                //  $analiz = j($j['analiz']);
                    $puanlar = j($s->puanlar);
                    $netler = j($s->netler);
            ?>
                <tr id="t{{$s->id}}">
                <td>{{$s->id}}</td>
                    <td><?php // print2($s) ?>{{$s->ogrenci_adi}}
                    <input type="number" title="{{e2("Kullanıcı Numarası")}}" name="uid" id="{{$s->id}}" value="{{$s->uid}}" class="form-control edit" table="sonuclar">
                    Atanmış kullanıcı: 
                    <strong>{{@$ogrenci->name}} {{@$ogrenci->surname}}</strong>
                </td>
                    
                    <td>{{$s->tc_kimlik_no}}</td>
                    <td>
                        <?php if(is_array($puanlar)) { ?>
                        <?php foreach($puanlar AS $alan => $deger) {
                             ?>
                             <strong>{{$alan}}: </strong> {{$deger}} <br>
 
                             <?php 
                        } ?>
                        <?php } ?>

                    </td>
                    <td style="width:300px;">
                    
                        <?php if(is_array($netler)) {
                            foreach($netler AS $alan => $deger) {
                                 ?>
                                  <strong>{{$alan}}: </strong> {{$deger}} <br>
                                 <?php 
                            }
                        } ?>
                      
                    </td>
                    <td>
                        <?php  
                        if(is_array($j)) {
                        foreach($j AS $alan => $deger) { ?>
                            <strong>{{$alan}}</strong> : {{$deger['cevaplar']}} <br>
                        <?php }
                        }
                        ?>
                    </td>
                    <td>
                        <div style="height:200px;overflow:auto;width:300px;">
                            <?php print2($j); ?>
                        </div>
                    </td>
                    <td>
                    <a href="{{url("admin-ajax/ogrenci-sinavlari?id=".$s->tc_kimlik_no)}}" title="Öğrenci Sınavları" class="ajax_modal btn btn-primary">Sınav Sonuçları</a>

                        <a href="?id={{get("id")}}&sonuc-sil={{$s->id}}" teyit="{{e2("Sonuç silinecek. Bu işlem geri alınamaz!")}}" ajax="#t{{$s->id}}" class="btn btn-danger e"><i class="fa fa-times"></i></a>
                    </td>

                </tr>
                <?php } ?>
            </table>
        </div>
        {{_col()}}
    </div>
</div>
