<?php $u = u(); ?>
<div class="row">
     {{col("col-12","Diji Ayna")}} 
        <p>"Söyle bana hatalarım nedir?"</p>
      <?php 
      try {
         $sonuc = db("sonuclar")
            ->where("title","NOT LIKE","%KAS Sınavı%")
            ->where("uid",$u->id);

         if(getisset("id")) {
            $sonuc = $sonuc->where("id",get("id"));
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
                           $sorular = explode(",",$sonuc->sorular);
                           $soruNo = 0;
                     ?>

                     @foreach($analiz AS $dersAdi => $dersArray)
                     <?php $cevaplar = str_split($dersArray['cevaplar']); ?>
                        <?php $k = 1; ?>
                        @foreach($cevaplar AS $cevap)
                        <?php $goster = false;

                        ?>
                        <?php if(ctype_lower($cevap) || $cevap=="*") { ?>
                        <tr>
                              <td class="text-center"></td>
                              <td class="font-w600 text-success">{{slug_to_title($dersAdi)}}</td>
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