<?php $kazanimToKonu = kazanim_to_konu2(); 
if($u->level=="Öğrenci") {
  $u->alias_ids = [$u->id];
}
if($u->level=="Veli") {
  
  $u->alias_ids = $u->ogrenci_ids;
 // dd($u);
}

/*
if($u->level=="Veli") {
  $u->alias_ids = [$]
}
*/
?>
<div class="row">
     {{col("col-12","Net Takibi")}} 
        <form action="" method="get">
            @csrf
            <input type="hidden" name="t" value="net-takibi">
            <input type="hidden" name="filtre" value="ok">
            <?php if($u->level=="Kurum")  { 
              ?>
             {{e2("Öğrenci Adı")}}
             <select name="uid" id="" class="form-control select2">
                 <option value="">{{e2("Tüm Öğrenciler")}}</option>
                 <?php $ogrenciler = kurum_users("Öğrenci"); foreach($ogrenciler AS $o)  { 
                   ?>
                  <option value="{{$o->id}}" <?php if(getesit("uid",$o->id)) echo "selected"; ?>>{{$o->name}} {{$o->surname}} / {{$o->alan}} ({{$o->sinif}})</option> 
                  <?php } ?>
             </select> 
             {{e2("Sınıf Adı")}}
             <select name="sinif" id="" class="form-control select2">
                 <option value="">{{e2("Tüm Sınıflar")}}</option>
                 <?php $ogrenciler = kurum_siniflar_detayli(); foreach($ogrenciler AS $sinif => $o)  { 
                   $implode_ogrenci = implode(",",$o);
                   ?>
                  <option value="{{$implode_ogrenci}}" <?php if(getesit("sinif",$implode_ogrenci)) echo "selected"; ?>>{{$sinif}} ({{count($o)}} Öğrenci)</option> 
                  <?php } ?>
             </select> 
             <?php } ?>
             {{e2("Sınav Adı")}}
             <?php
            $selectedSinav = [];
            if(!getesit("sinav","")) {
              $selectedSinav = $_GET['sinav'];
            }
            $sinavlar = db("sonuclar")
                  ->whereIn("uid",$u->alias_ids)
                  ->where(function($query) {
                   
                      $query->orWhere('title','like','%TYT%');
                      $query->orWhere('title','like','%AYT%');
                      $query->orWhere('title','like','%LGS%');
                      $query->orWhere('title','like','%DBS%');
                    
                    
                  
                    
                  })
                  ->groupBy("title")
                  ->get();
                  
                   ?>
             <select name="sinav[]" id="" class="form-control select2" multiple>
                 <?php foreach($sinavlar AS $sinav)  { 
                   ?>
                   <option value="{{$sinav->title}}" <?php if(in_array($sinav->title,$selectedSinav)) echo "selected"; ?>>{{$sinav->title}}</option> 
                  
                  <?php } ?>
             </select> 
            {{e2("Tarih Aralığı")}}
            <div class="input-group">
                <input type="date" value="{{get("d1")}}" class="form-control" name="d1" id="d1">
                <input type="date" value="{{get("d2")}}" class="form-control" name="d2" id="d2">
                
            </div>
            {{e2("Sınav Türü")}}
            <?php 
            $selectedSinavType = [];
            if(!getesit("sinav_type","")) {
              $selectedSinavType = $_GET['sinav_type'];
            }
            ?>
                  <select name="sinav_type[]" id="" class="form-control select2" multiple>
                    <?php foreach(sinav_turleri() AS $sinav_type)  { 
                      ?>
                     <option value="{{$sinav_type}}" <?php if(in_array($sinav_type,$selectedSinavType)) echo "selected"; ?>>{{$sinav_type}}</option> 
                     <?php } ?>
                  </select>
              {{e2("Branş")}}
              <?php 
              
              $secili_branslar = [];
              if(!getesit("brans","")) {
                $secili_branslar = $_GET['brans'];
              }
          //    dd($_GET);              ?>
              <select name="brans[]"  id="" class="form-control select2" multiple>
                <?php 
                
                foreach(branslar() AS $b)  { 
                  ?>
                   <option value="{{$b->slug}}" <?php if(in_array($b->slug,$secili_branslar)) echo "selected"; ?>>{{$b->title}}</option> 
                 <?php } ?>

              </select>

             <br>
            <button class="btn btn-primary btn-hero">{{e2("Filtrele")}}</button>
        </form>
     {{_col()}}
     {{col("col-md-12","Zamana Göre Netler",0)}} 
                <canvas id="multipleLineChart" width="600" height="200"></canvas>
            <script>
const ctx123 = document.getElementById('multipleLineChart').getContext('2d');
<?php 
$selectSinavType = [];
if(!getesit('sinav_type','')) {
  $selectSinavType = $_GET['sinav_type'];
}
$selectSinav = [];
if(!getesit('sinav','')) {
  $selectSinav = $_GET['sinav'];
}

$sonuclar = db("sonuclar")->where(function($query) use($selectSinavType) {
  if(count($selectSinavType)==0) {
    $query->orWhere('title','like','%TYT%');
    $query->orWhere('title','like','%AYT%');
    $query->orWhere('title','like','%LGS%');
    $query->orWhere('title','like','%DBS%');
  } else {
    foreach($selectSinavType AS $sinavType) {
      $query->orWhere('title','like',"%$sinavType%");
    }
  }
  

  
});
if(getesit("sinav_type","")) {
  $sonuclar = db("sonuclar")->where(function($query) use($selectSinav) {
    if(count($selectSinav)==0) {
      $query->orWhere('title','like','%TYT%');
      $query->orWhere('title','like','%AYT%');
      $query->orWhere('title','like','%LGS%');
      $query->orWhere('title','like','%DBS%');
    } else {
    
      foreach($selectSinav AS $sinav) {
        $query->orWhere("title",$sinav);
      }
      
    }
  
    
  });
}

$format = "d/m/Y";
$sonuc_filter = true;
/*
if(getisset("filtre"))  {
  $sonuc_filter = false;
  $q = $_GET;

  if(isset($q['kas_sinavlari'])) {
    $sonuc_filter = true;
  }

  if(isset($q['deneme_sinavlari'])) {
    $sonuc_filter = true;
  }

  $sonuclar = $sonuclar->where(function($query) use($q) {
   
      if(isset($q['kas_sinavlari'])) {
        //$query->orWhere('alt_type','KAS');
        $query->orWhere('title','LIKE','%KAS%');
      }
      if(isset($q['deneme_sinavlari'])) {
        $query->orWhere('title','LIKE','%DENEME%');
      }

  });
}
*/
if($u->level=="Öğrenci") {
  $sonuclar = $sonuclar->where("uid",$u->id);
} else {

  if(!getesit("uid","")) {
    $sonuclar = $sonuclar->where("uid",get("uid"));
  } else {

    if(!getesit("sinif","")) {
      $secili_ogrenciler = explode(",",get("sinif"));
      $sonuclar = $sonuclar->whereIn("uid",$secili_ogrenciler);
    } else {
      $sonuclar = $sonuclar->whereIn("uid",$u->alias_ids);
    }
    
  }
}


if(!getesit("d1","")) {
  $sonuclar = $sonuclar->where("created_at",">=",get("d1"));
} else {
  $sonuclar = $sonuclar->where("created_at",">=",date("Y-m-d",strtotime("-365 days")));
}

if(!getesit("d2","")) {
  $sonuclar = $sonuclar->where("created_at","<=",get("d2"));
}

if(!getesit("periyot","")) {
  $format = get("periyot");
}

//dd($sonuclar->toSql());


$dizi = [];
$dizi2 = [];
$dizi3 = [];
$sonuclar = $sonuclar
  ->orderBy("created_at","ASC")
  ->whereNotNull("puan_tur")
  ->get();

$sonucSayilari = [];
$sinavlar = [];
if($sonuc_filter) {

  foreach($sonuclar AS $s) {

    if(!in_array($s->title,$sinavlar)) {
      array_push($sinavlar,$s->title);
    }
    $netBolum = 4;

    if(!isset($sonucSayilari[str_slug($s->title)]))
      $sonucSayilari[str_slug($s->title)]=0;
    
      $sonucSayilari[str_slug($s->title)]++;
    

        $j = j($s->analiz);
  
    


    foreach($j AS $alan => $deger) {

      


      $net = $deger['net'];

      $brans_goster = true;

      if(!getesit("brans","")) {

        if(in_array($alan,$_GET['brans'])) {
          $brans_goster = true;
        } else {
          $brans_goster = false;
        }
      }

      $key = '"'.$s->title.'"';
  //    if(!isset($dizi[$key])) $dizi[$key] = 0;
      if($brans_goster) {
        if(isset($dizi[$key])) {
          if($net!=0) {
            $dizi[$key] += $net;
             
          }
          

        } else {
          $dizi[$key] = $net;
        }
        
      }
     
      
      
      if($brans_goster) {
        if(!isset($dizi2[$alan])) $dizi2[$alan] = 0;
        $dizi2[$alan] += $net;
  
      }
      
     

    }
  }
}


//refactor average aritmetic

$new = [];
foreach($dizi AS $alan => $deger) {

  $new[$alan] = round($deger / $sonucSayilari[str_slug($alan)],2);

}

$dizi = $new;


?>

Chart.register(ChartDataLabels);
Chart.defaults.set('plugins.datalabels', {
  color: '#FE777B'
});
const chart = new Chart(ctx123, {
  plugins: [ChartDataLabels],
  type: 'bar',
  
  options: {
        plugins: {
          // Change options for ALL labels of THIS CHART
          datalabels: {
            color: '#36A2EB'
          }
        },
          
        animation: {
          
            onProgress: function(animation) {
                progress.value = animation.currentStep / animation.numSteps;
            }
        }
    },
  data: {
    labels: [
      <?php echo implode(",",array_keys($dizi)) ?>
    ],
    datasets: [{
        label: '# Net Sayısı',
        datalabels: {
          color: '#fff',
          backgroundColor: '#e95229',
          borderRadius: 10
        },
        data: [{{implode(", ",array_values($dizi))}}],
        borderWidth: 0,
        fill: true,
        borderRadius: 10,
        borderColor: '#005c6a',tension: 0.4,
        backgroundColor: '#005c6a',tension: 0.4
      }
    ]
  },
  options: {
    
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    }
  }
});


            </script>
        {{_col()}}
     
</div>