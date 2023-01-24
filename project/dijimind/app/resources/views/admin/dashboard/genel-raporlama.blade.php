
<?php if(u()->level=="Admin") {
     ?>

        <h2 class="content-heading">{{e2("Sistem Raporları")}}</h2>
        <div class="row">
            {{col("col-md-4 text-center","Onaylanan Soru",4)}}
                <h1>{{db("soru_atama")->where("durum","Onaylandı")->count()}}</h1>

            {{_col()}}
            {{col("col-md-4 text-center","Onay Bekleyen Soru",7)}}
                <h1>{{db("soru_atama")->where("durum","Onay Bekliyor")->whereNotNull("cevap")->count()}}</h1>

            {{_col()}}
            {{col("col-md-4 text-center","Cevap Bekleyen Soru",2)}}
                <h1>{{db("soru_atama")->where("durum","Onay Bekliyor")->whereNull("cevap")->count()}}</h1>

            {{_col()}}

        </div>
        <div class="row">
            <?php $labels = "a,b,c"; 
            $values = "1,3,4";
            $sonuclar = db("sonuclar")->where("type","ONLINE")->get();
            $dizi = array();
            foreach($sonuclar AS $s) {
                $label = date("d.m.Y",strtotime($s->created_at));
                if(!isset($dizi[$label])) $dizi[$label] = 0;
                $dizi[$label]++;
            }
            $values = implode(",",$dizi);
            $labels =  implode_key(",",$dizi);
            ?>
            {{col("col-md-12","Günlük Online Sınav Uygulama Sayısı","10")}} 
                <?php charts($labels,$values,"Online Sınav Sayısı","line"); ?>
            {{_col()}}
        </div>
        <div class="row">
            
            <?php chartsByData("users","level","Kullanıcılar","bar","36","col-md-4"); ?>
            <div class="col-md-8">
                 {{col("col-12","Öğrenci İstatistikleri")}} 
                 <?php $ogrenciler = db("users")->where("level","Öğrenci")->get();
                 $dizi = [];
                 $toplam = $ogrenciler->count();
                 foreach($ogrenciler AS $o) {
                     if($o->sinif==8) {
                         $tur = "LGS";
                     } else {
                         $tur = "TYT";
                     }
                     $o->il = strtoupper(str_slug($o->il));
                     $o->ilce = strtoupper(str_slug($o->ilce));
                     if($o->il=="") $o->il = "- IL";
                     if($o->ilce=="") $o->ilce = "- ILCE";
                     if($o->sinif=="") $tur = "- TUR";
                     if(!isset($dizi[$o->il][$o->ilce][$tur])) $dizi[$o->il][$o->ilce][$tur] = 0;
                     $dizi[$o->il][$o->ilce][$tur]++;
                    
                 }
                  ksort($dizi);
               //  print2($dizi);
                 ?>
                 <script>
                    $(document).ready(function(){
                    $("#ogrenci_ara").on("keyup", function() {
                        var value = $(this).val().toLowerCase();
                        $("#ogrenciler tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                        });
                    });
                    });
                    </script>
                 <input type="text" name="" placeholder="{{e2("Ara...")}}" id="ogrenci_ara" class="form-control">
                 <div class="table-responsive" style="max-height:236px;">
                    <table id="ogrenciler" class="table">
                        <tr>
                            <th>İl</th>
                            <th>İlçe</th>
                            <th>LGS</th>
                            <th>TYT</th>
                            <th>DİĞER</th>
                        </tr>
                        <?php 
                        $lgs_say = 0;
                        $tyt_say = 0;
                        $diger_say = 0;
                        
                        foreach($dizi AS $il => $deger)  { 
                            $il_toplam = 0;
                          ?>
                         
                         <?php 
                         ksort($deger);
                         foreach($deger AS $ilce => $sayi) { ?> 
                         <tr>
                             <td>{{$il}}</td>
                             <td>{{$ilce}}</td>
                             <?php 
                             
                             foreach($sayi AS $tur => $say)  { 
                                if($tur=="LGS") {
                                    $lgs_say += $say;
                                } elseif($tur=="TYT") {
                                    $tyt_say += $say;
                                } else {
                                    $diger_say += $say;
                                }
                                $il_toplam += $say;
                               ?>
                              <td>{{$say}}</td> 
                              <?php 
                            
                            } ?>
                         </tr>
                         

                         <?php } ?>
                         <tr>
                             <th>{{$il}} Toplamı</th>
                             <th>{{$il_toplam}}</th>
                         </tr>
                         <?php } ?>
                         
                    </table>

                </div>
                <hr>
                <div class="btn-group btn-block">
                    <div class="btn btn-primary">
                        LGS <br>    
                        {{$lgs_say}}
                    </div>
                    <div class="btn btn-danger">
                        TYT <br>    
                        {{$tyt_say}}
                    </div>
                    <div class="btn btn-warning">
                        Diğer <br>    
                        {{$diger_say}}
                    </div>
                    <div class="btn btn-success">
                        Toplam <br>    
                        {{$toplam}}
                    </div>
                </div>
                
                 {{_col()}}
                
            </div>
          
        </div>
        
     <?php 
} ?>
