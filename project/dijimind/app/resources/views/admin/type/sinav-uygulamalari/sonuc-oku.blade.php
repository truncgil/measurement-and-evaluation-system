<br><?php 
if(getisset("oku")) {
                  oturumAc();
                  if(getisset("kaydet")) {
                      if(oturumisset("analiz")) {
                        $analiz = $_SESSION['analiz'];
                      } else {
                          bilgi("Lütfen TXT dosyasını tekrar okutunuz","danger");
                      }
                    
                  } else {
                    $analiz = file_to_analiz("dosya");
                    
                    $_SESSION['analiz'] = $analiz;
                  }
                
                //print2($analiz); exit();
                $eklenen = 0;
                $guncellenen = 0;
               
                 ?>
                 <?php if(!is_null($analiz)) {
                    
                   ?>
                  <h3>Okuma Sonucu Şu Şekilde</h3>
 
                  <div class="table-responsive">
                     <table class="table table-striped table-hover">
                         <thead>
                             <tr>
                                 <th>{{e2("SIRA")}}</th>
                                 <th>{{e2("ÖĞRENCİ ADI")}}</th>
                                 <th>{{e2("TC KİMLİK NO")}}</th>
                                 <th>{{e2("KİTAPÇIK")}}</th>
                                 <th>{{e2("ANALİZ SONUÇLARI")}}</th>
                               
                             </tr>
                         
                         </thead>
                         <tbody>
                            
                         <?php 
                         $k=0;
                         foreach($analiz AS $a) { 
                             $k++;
                             ?>
                             <tr>
                                 <td>{{$k}}</td>
                                 <td>{{$a['ogrenci-adi']}}</td>
                                 <td>{{$a['tc-kimlik-no']}}</td>
                                 <td>{{$a['kitapcik']}}</td>
                                 <td>
                                    <?php 
                                    $u = u2($a['tc-kimlik-no']);
                                    if($u) {
                                         ?>
                                         {{$u->id}} {{$u->name}} {{$u->surname}}
                                         <?php 
                                    }
                                    ?>
                                     <button data-toggle="collapse" data-target="#detay{{$k}}">Detaylar</button>
 
                                     <div id="detay{{$k}}" class="collapse">
                                         <div class="table-responsive">
                                             <table class="table table-striped table-bordered table-sm">
                                                 <?php foreach($a['netler'] AS $alan => $deger) { ?>
                                                 <tr>
                                                     <th>{{$alan}}</th>
                                                     <td>{{$deger}}</td>
                                                     
                                                 </tr>
                                                 <?php } ?>
                                                 
                                             </table>
                                             <?php if(is_array($a['puan'])) { ?>
                                             <table class="table table-striped table-bordered table-sm">
                                             
                                             <?php foreach($a['puan'] AS $alan => $deger) { ?>
                                                     <tr>
                                                         <th>{{$alan}}</th>
                                                         <td>{{$deger}}</td>
                                                     </tr>
                                                 <?php } ?>
                                             </table>
                                             <?php } ?>
                                         </div>
                                     </div>
                                     
                                      
                                     
                                 </td>
                             </tr>
                         <?php 
                     if(getisset("kaydet")) {
                             if(postesit("title","")) {
                                 $sonuc_title = rand();
                                 if(postisset("sinav")) {
                                     $sinav = db("sinavlar")->where("id",post("sinav"))->first();
                                     $sonuc_title = $sinav->title;
                                 }
                                 
                                 $_POST['title'] = $sonuc_title;
                             }
                         $varmi = db("sonuclar")
                         ->where("sinav_id",post("sinav"))
                         ->where("ogrenci_adi",$a['ogrenci-adi'])
                         ->count();
                         $ogrenci_varmi = 0;
                         if(trim($a['tc-kimlik-no'])!="") {
                             $a['tc-kimlik-no'] = trim($a['tc-kimlik-no']);
                             $ogrenci_varmi = db("users")
                          
                             ->where("email",$a['tc-kimlik-no'])
                             ->orWhere("id",$a['tc-kimlik-no'])
                             ->first();
                             if($ogrenci_varmi) {
                                 $id = $ogrenci_varmi->id;
                             }  
                         } else {
                             $ogrenci_varmi = db("users")
                             ->where("name",$a['ogrenci-adi'])
                             ->first();
                             if($ogrenci_varmi) {
                                 $id = $ogrenci_varmi->id;
                             }   
                         }
                         $email = $a['tc-kimlik-no'];
                         if(!$ogrenci_varmi) {
                             bilgi("{$a['ogrenci-adi']} eklendi");
                             ekle([
                                 "title" => $a['ogrenci-adi'],
                                 "tc_kimlik_no" => $a['tc-kimlik-no']
                             ],"ogrenciler");
 
                             //öğrenci için aynı zamanda bir de üye  oluşturalım 
                             
                             if($email=="") {
                                 $email = rand();
                             }
                             $password = rand(111111,999999);
                             $id = ekle([
                                 "name" => $a['ogrenci-adi'],
                                 "email" => $email,
                                 "level" => "Öğrenci",
                                 "recover" => $password,
                                 "alias" => $u->alias,
                                 "password" => Hash::make($password)
                             ],"users");
                         } else {
                             $email = $ogrenci_varmi->id;
                         }
                         if($email=="") {
                             $email = $id;
                         }
                         $alias = post("alias");
                         if($u->level == "Kurum") {
                             $alias = $u->alias;
                         }
                         $date = simdi();
                         if(!postesit("date","")) {
                            $date = post("date");
                        }

                        if($varmi==0) {
                            
                             ekle2([
                                 "title" => post("title"),
                                 "type" => "OPTIK",
                                 "level" => post("level"),
                                 "sinav_id" => post("sinav"),
                                 "optik_id" => post("optik"),
                                 "ogrenci_adi" => $a['ogrenci-adi'],
                                 "tc_kimlik_no" => $email,
                                 "created_at" => $date,
                                 "kitapcik" => $a['kitapcik'],
                                 "uid" => $email,
                                 "tyt" => $a['puan']['tyt-puan'],
                                 "alias" => $alias,
                                 "puanlar" => json_encode_tr($a['puan']),
                                 "netler" => json_encode_tr($a['netler']),
                                 "analiz" => json_encode_tr($a['analiz'])
                             ],"sonuclar");
                             $eklenen++;
                         } else {
                             db("sonuclar")
                             ->where("sinav_id",post("sinav"))
                             ->where("ogrenci_adi",$a['ogrenci-adi'])
                             ->update([
                                 "title" => post("title"),
                                 "sinav_id" => post("sinav"),
                                 "optik_id" => post("optik"),
                                 "ogrenci_adi" => $a['ogrenci-adi'],
                                 "tc_kimlik_no" => $email,
                                 "created_at" => $date,
                                 "type" => "OPTIK",
                                 "uid" => $email,
                                 "kitapcik" => $a['kitapcik'],
                                 "tyt" => $a['puan']['tyt-puan'],
                                 "alias" => $alias,
                                 "puanlar" => json_encode_tr($a['puan']),
                                 "netler" => json_encode_tr($a['netler']),
                                 "analiz" => json_encode_tr($a['analiz'])
                             ]);
                             $guncellenen++;
                         }
                         
                         } // kaydet
                     
                  //   yonlendir()
                       
                     } ?>
                         </tbody>
                     </table>
                     
                  </div> 
                  
                 <?php if(getisset("kaydet")) {
                     bilgi("$eklenen öğrencinin sonucu eklenmiştir, $guncellenen öğrencinin sonucu güncellenmiştir");
                 } ?>
                 <div class="text-center">
                        <form action="?<?php if(getisset("t")) echo "t={$_GET['t']}&"; ?>oku&kaydet" method="post" class="serialize">
                        {{csrf_field()}}
                        <input type="hidden" name="title" value="{{post("title")}}">
                        <input type="hidden" name="sinav" value="{{post("sinav")}}">
                        <input type="hidden" name="optik" value="{{post("optik")}}">
                        <input type="hidden" name="level" value="{{post("level")}}">
                        <?php if(!postesit("date",""))  { 
                          ?>
                         <input type="hidden" name="date" value="{{post("date")}}"> 
                        <?php } ?>

                       
                        <div class="btn-group">
                            <button type="submit"  class="btn btn-success"><i class="fa fa-save"></i> {{e2("Sonuçları Kaydet")}}</button>
                            <a href="?id={{post("title")}}" target="_blank" class="btn btn-primary"><i class="fa fa-edit"></i> {{e2("Sonuçları Düzenle")}}</a>
                        </div>
                        </form> 
                  
                </div>
                <?php } ?> 
                 <?php 
                   
            } ?>