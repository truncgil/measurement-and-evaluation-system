<?php $u = u(); 
$sinavlar = db("sonuclar")
->select("title")
->where("title","NOT LIKE","%KAS Sınavı%");
if($u->level=="Öğrenci") {
   $sinavlar = $sinavlar->where("uid",$u->id);
} else {
   $sinavlar = $sinavlar->whereIn("uid",$u->alias_ids);
}
$sinavlar = $sinavlar->groupBy("title");
$sinavlar = $sinavlar->get();
$selectSinav = [];
$selectBrans = [];
$selectOgrenci = [];
if(getisset("sinav")) {
   $selectSinav = $_GET['sinav'];
}
if(getisset("brans")) {
   $selectBrans = $_GET['brans'];
}
if(getisset("ogrenci")) {
   $selectOgrenci = $_GET['ogrenci'];
}
//dump($selectSinav);
?> 
<div class="row">
    {{col("col-12","Filtrele")}} 
         <form action="" method="get">
            <input type="hidden" name="t" value="dijiayna">
            <?php if($u->level=="Kurum") {

               ?>
               Öğrenci Adı
               <select name="ogrenci[]" id="" class="form-control select2" multiple>
                  <?php foreach(kurum_users("Öğrenci") AS $ogrenci)  { 
                    ?>
                   <option value="{{$ogrenci->id}}"
                   {{in_array($ogrenci->id,$selectOgrenci) ? "selected" : ""}}

                   >{{$ogrenci->name}} {{$ogrenci->surname}}</option> 
                   <?php } ?>
               </select>
               <?php  
            } ?>
            Sınav Adı
               <select name="sinav[]" id="" class="form-control select2" multiple>
                  <?php foreach($sinavlar AS $sinav)  { 
                    ?>
                   <option value="{{$sinav->title}}" {{in_array($sinav->title,$selectSinav) ? "selected" : ""}}>{{$sinav->title}}</option> 
                   <?php } ?>
               </select>
            Ders Adı
               <select name="brans[]" id="" class="form-control select2" multiple>
                     <?php foreach(branslar() AS $brans) {
                         ?>
                         <option value="{{$brans->title}}" {{in_array($brans->title,$selectBrans) ? "selected" : ""}}>{{$brans->title}}</option>
                         <?php 
                     } ?>
               </select>
               Tarih
               <div class="input-group">
                  <input type="date" name="start" value="{{get("start")}}" class="form-control" id="">
                  <input type="date" name="end" value="{{get("end")}}" class="form-control" id="">
               </div>
               <button class="btn btn-primary btn-hero mt-5">Filtrele</button>
         </form>
    {{_col()}}
     {{col("col-12","Diji Ayna")}} 
        <p>"Söyle bana hatalarım nedir?"</p>
      <?php 
      try {
         $sonuc = db("sonuclar")
            ->where("title","NOT LIKE","%KAS Sınavı%");
           

            if($u->level=="Öğrenci") {
               $sonuc = $sonuc->where("uid",$u->id);
            } else {
               $sonuc = $sonuc->whereIn("uid",$u->alias_ids);
            }

         if(getisset("id")) {
            $sonuc = $sonuc->where("id",get("id"));
         }

         if(getisset("sinav")) {
            $sonuc = $sonuc->whereIn("title", $selectSinav);
         }

         if(!getesit("start","")) {
            $sonuc = $sonuc->whereDate("created_at",">=",get("start"));
         }
        
         if(!getesit("end","")) {
            $sonuc = $sonuc->whereDate("created_at","<=",get("end"));
         }
        
         if(getisset("ogrenci")) {
            $sonuc = $sonuc->whereIn("uid",$selectOgrenci);
         }

         $sonuclar = $sonuc->simplePaginate(20);

      if($sonuclar) {
         
          ?>
         {{$sonuclar->appends($_GET)->links()}}
         <div class="table-responsive">
            <table class="js-table-sections  table-hover table">
               
               <thead>
                     <tr>
                           <th style="width: 30px;"># </th>
                           <th>Sınav Adı</th>
                           <th class="d-none d-sm-table-cell" style="width: 20%;">Kitapçık</th>
                           <th class="d-none d-sm-table-cell" style="width: 20%;">Soru No</th>
                           <th class="d-none d-sm-table-cell" style="width: 20%;">Durum</th>
                     </tr>
                  </thead>
               @foreach($sonuclar AS $sonuc)
                  <tbody class="js-table-sections-header">
                     <tr>
                           <td class="text-center">
                              <i class="fa fa-angle-right"></i>
                           </td>
                           <td class="font-w600">{{$sonuc->title}}</td>
                           <td>
                              <span class="badge badge-warning">{{$sonuc->kitapcik}}</span>
                           </td>
                           
                           <td></td>
                           <td></td>
                     </tr>
                  </tbody>
                  <tbody>
                     <?php $analiz = j($sonuc->analiz); ?>
                     <?php 
                           if($sonuc->sorular!="") {
                              $sorular = explode(",",$sonuc->sorular);
                           }
                           
                          
                           $soruNo = 0;
                     ?>

                     @foreach($analiz AS $dersAdi => $dersArray)
                     <?php $cevaplar = str_split($dersArray['cevaplar']); ?>
                        <?php $k = 1; 
                        ?>
                        @foreach($cevaplar AS $cevap)
                        <?php $goster = false;

                        ?>
                        <?php if(ctype_lower($cevap) || $cevap=="*") { 
                           
                           $bransTitle = slug_to_title($dersAdi);
                           if(count($selectBrans)==0 || in_array($bransTitle, $selectBrans))  { 
                            
                            ?>
                              <tr>
                                    <td class="text-center"></td>
                                    <td class="font-w600 text-success">{{$bransTitle}}</td>
                                    <td class="font-size-sm"></td>
                                    <td class="d-none d-sm-table-cell">
                                       {{$k}}
                                       
                                    </td>
                                    <td>
                                       <?php if(ctype_lower($cevap)) { ?>
                                          YANLIŞ
                                       <?php }  ?>
                                       <?php if($cevap=="*") { ?>
                                          BOŞ
                                       <?php   } ?>
                                       <?php if(isset($sorular[$soruNo])) {
                                          ?>

                                          <a href="?ajax=dijiayna-soru-detay&id={{$sorular[$soruNo]}}" class="btn btn-sm btn-secondary ajax_modal">
                                             <i class="fa fa-list"></i>
                                          </a>
                                          <?php 
                                       } ?>
                                       
                                       </td>
                                    
                              </tr> 
                              <?php $k++;  $soruNo++; ?>
                            <?php } ?>
                        
                        
                        <?php } ?>
                        @endforeach
                     @endforeach
                  </tbody>
               @endforeach
               
               
               
               
                  
            
            </table>
         </div>
         {{$sonuclar->appends($_GET)->links()}}
         

          <?php 
      }
      } catch (\Throwable $th) {
        dump($th);
      }
      
      ?>
     {{_col()}}
</div>
<script>jQuery(function () {
                                    Codebase.helpers('table-tools');
                                });</script>