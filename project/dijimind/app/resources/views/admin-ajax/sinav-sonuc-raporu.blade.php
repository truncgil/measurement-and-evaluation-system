<?php 
$u = u();
$sinav = db("sonuclar")->where("title",get("title"))->whereIn("uid",$u->alias_ids)->first(); 
$analiz = j($sinav->analiz);
$sonuclar = db("sonuclar")
->join("users","sonuclar.uid","users.id")
->select(
    "sube_sira",
    "sinif_sira",
    "kurum_sira",
    "il_sira",
    "genel_sira",
    "users.name",
    "users.sinif",
    "users.sube",
    "users.surname",
    "sonuclar.analiz",
    "sonuclar.puan",
    "sonuclar.lgs",
    "sonuclar.tyt",
    "sonuclar.yks_say",
    "sonuclar.yks_ea",
    "sonuclar.yks_soz"
    )
->where("sonuclar.title",get("title"))
->orderBy("sonuclar.tyt","DESC")
->orderBy("sonuclar.lgs","DESC")
->whereIn("sonuclar.uid",$u->alias_ids)->get(); 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$sinav->title}} Sınav Sonuç Raporu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

</head>
<body>
    <div class="container-fluid">
        <h1>{{$sinav->title}}</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-striped table-hover text-center">
                <tr>
                    <th rowspan="2"  class="table-success">Öğrenci Adı</th>
                    <?php foreach($analiz AS $alan => $deger)  { 
                        $alan = slug_to_title($alan);
                    ?>
                    <th colspan="3" class="table-primary">{{$alan}}</th> 
                    <?php } ?>
                    <th rowspan="2" class="table-warning">T. Net</th>
                    <th rowspan="2" class="table-danger">Puan</th>
                    <th rowspan="2" class="table-primary">ŞB Drc.</th>
                    <th rowspan="2" class="table-primary">Sınıf Drc.</th>
                    <th rowspan="2" class="table-primary">KRM Drc.</th>
                    <th rowspan="2" class="table-primary">İl Drc.</th>
                    <th rowspan="2" class="table-primary">Genel Drc.</th>
                </tr>
                <tr>
                    <?php foreach($analiz AS $alan => $deger)  { 
                        $alan = slug_to_title($alan);
                    ?>
                    <th class="table-primary">D</th> 
                    <th class="table-primary">Y</th> 
                    <th class="table-primary">N</th> 
                    <?php } ?>
                </tr>
                <?php 
                $k= 1;
                $toplam = [];
                $toplam_puan = 0;
                $genel_toplam_net = 0;
               
                foreach($sonuclar AS $s)  { 

                    $toplam_net = 0;
                    $analiz = j($s->analiz);
                    $toplam_puan += $s->puan;
                ?>
                <tr>
                    <th  class="table-success"><small>{{$s->name}} {{$s->surname}} ({{$s->sinif}}{{$s->sube}})</small></th>
                    <?php foreach($analiz AS $a => $deger) {
                        if(!isset($toplam[$a]['dogru'])) $toplam[$a]['dogru'] = 0;
                        if(!isset($toplam[$a]['yanlis'])) $toplam[$a]['yanlis'] = 0;
                        if(!isset($toplam[$a]['net'])) $toplam[$a]['net'] = 0;
                        $toplam[$a]['dogru'] += $deger['dogru'];
                        $toplam[$a]['yanlis'] += $deger['yanlis'];
                        $toplam[$a]['net'] += $deger['net'];
                        $toplam_net += $deger['net'];
                        $genel_toplam_net += $deger['net'];
                        ?>
                        <td width="20"><small>{{$deger['dogru']}}</small></td>
                        <td width="20"><small>{{$deger['yanlis']}}</small></td>
                        <td width="20"><small>{{round($deger['net'],2)}}</small></td>

                        <?php 
                    } ?>
                    
                    <td   class="table-warning">
                        <small>
                            {{round($toplam_net,2)}}
                        </small>
                    </td>
                    <td   class="table-danger">
                        <small>
                            {{$s->puan}}
                        </small>
                    </td>
                    <td>{{$s->sube_sira}}</td>
                    <td>{{$s->sinif_sira}}</td>
                    <td>{{$s->kurum_sira}}</td>
                    <td>{{$s->il_sira}}</td>
                    <td>{{$s->genel_sira}}</td>
                </tr> 
                <?php $k++; } ?>
                <tr>
                    <th>{{e2("Ortalama")}}</th>
                    <?php foreach($toplam AS $alan => $deger)  { 
                    ?>
                    <th>{{round($deger['dogru']/$k,2)}}</th> 
                    <th>{{round($deger['yanlis']/$k,2)}}</th> 
                    <th>{{round($deger['net']/$k,2)}}</th> 
                    <?php } ?>
                    <th>{{round($genel_toplam_net/$k,2)}}</th>
                    <th>{{round($toplam_puan/$k,2)}}</th>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
