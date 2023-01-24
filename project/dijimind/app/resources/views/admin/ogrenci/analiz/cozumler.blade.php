<?php 
$taksonomi_map = taksonomi_map();
$sinav = db("sinavlar")
->where("id",$sonuc->sinav_id)
->first();
$analiz = j($sonuc->analiz);
$ogrenci_cevaplari = [];

foreach($analiz AS $brans => $deger) {
    $ogrenci_cevaplari[$brans]  = str_split(strtoupper($deger['cevaplar']));
}
$kitapcik = $sonuc->kitapcik;
$sorulari_goster = true;
//dump($sonuc);

if($sinav) {
    
    $j = j($sinav->json);
    $cozum_tarih_fark = 0;
    
    if(isset($j['date2'])) {
        $cozum_tarihi = str_replace("T"," ",$j['date2']);
       
        $cozum_tarih_fark = zf2($cozum_tarihi);
    }
    if($cozum_tarih_fark>0) {
        col("col-12");
        bilgi("Sınav çözümleri $cozum_tarih_fark gün sonra aktif olacaktır.");
        _col();
        $sorulari_goster = false;

    }
}
if($sorulari_goster)  { 
 
     $sinav_sorular = $sonuc->sorular;
     if($sinav_sorular=="") {
         if($sinav) {
             $sorular = db("soru_bankasi")->where("group",$sinav->title);

             if($kitapcik=="B") {
                $sorular = $sorular->orderBy("b_sira","ASC");
             }
                 
             $sorular = $sorular->get();
         }
         
     } else {
         $sinav_sorular_array = explode(",",$sinav_sorular);
         $sorular = 
         db("soru_bankasi")
            ->whereIn("id",$sinav_sorular_array)
            ->orderByRaw("FIELD(id, $sinav_sorular)")
            ->get();
      
         
     }
    
     $soruVarGoster = true;
     if($sorular->count()==0) $soruVarGoster = false;   
     
    
    //  dump($sorular);
         $readonly = true;
if($sinav) {
    $dersler = j($sinav->dersler);
 
    $dersler_order = [];
    foreach($dersler AS $alan => $deger) {
        $dersler_order[$deger['optik']] = $deger;
    }
    
    ksort($dersler_order);
    $dersler = $dersler_order;
}
if($kitapcik=="B") {
    /*
    usort($sorular, function ($a, $b) {
        return $a->b_sira <=> $b->b_sira;
    });
    */
}


   ?>
   <?php if($soruVarGoster)  { 
     ?>
   <div class="col-12">
     <div class="filtre">
         <div class="input-group">
             <script>
                 $(function(){
                     var owl = $(".sorular").owlCarousel({
                         items: 1,
                         lazyLoad: true,
                         video:true,
                         nav: true,
                         touchDrag: false
                     });
                     $(".goto").on("click",function(){
                         var cursor = eval($('#brans').val()) + eval($('#soru').val());
                         cursor  -= 2;  
                         console.log(cursor);
                         
                         owl.trigger('to.owl.carousel', [cursor, 0, true]);
                     });
                     $(".video-btn").on("click",function(){
                         var link = $(this).attr("data-src");
                         var title = $(this).attr("title");
                         $("#video_iframe").attr("src",link);
                         $('#video_modal .modal-title').html(title);
                         $("#video_modal").modal();
                     });
 
                 });
             </script>
             <?php if($sinav)  { 
               ?>
              <select name="" id="brans" class="form-control select2">
                  <?php 
                  $cursor = 0;
                  foreach($dersler AS $alan => $deger)  { 
                      if($deger['optik']==1) {
                          $start = 1;
                          $cursor += $deger['soru'];
                      } else {
                          $start = $cursor + 1;;
                          $cursor += $deger['soru'];
                          
                          
                      }
                  ?>
                  <option value="{{$start}}">{{$deger['isim']}}</option> 
                  <?php } ?>
              </select> 
              <?php } ?>
             <input type="number" class="form-control" placeholder="{{e2("Soru Numarası")}}" value="1" name="soru" id="soru">
             <button class="btn btn-primary goto"><i class="fa fa-arrow-right"></i></button>
         </div>
     </div>
   </div>
   <?php 
   //if($kitapcik=="A")  { 
   if(true)  { 
     ?>
     <div class="row">
         <div class="col-12">
           @include("admin.ogrenci.sinava-basla.sinav-form")
           @include("admin.ogrenci.sinava-basla.css")
         </div>
     </div> 
      
    <?php } ?>
   <script>
      $(function(){
          var owl = $(".sorular").owlCarousel({
              items: 1,
              lazyLoad: true,
              nav: true,
              touchDrag: false
          });
          owl.on('changed.owl.carousel', function(event) {
             // console.log("changed.owl.carousel");
             var i = event.page.index;
            // $('.sorular .owl-item:eq('+i+') img').imageZoom();
  
            //  console.log(event.page.index);
          });
          
          $('.soru-container').on("click",function(){
              $(this).toggleClass("zoom-img");
             
              
          });
          $(".close").on("click",function(){
             $('#video_iframe').removeAttr('src');
            
          });
         
         
       });
   </script>
   <?php  ///dump($sorular) ?>
   <style>
       <?php 
       $k = 1;
       
       foreach($sorular AS $s) {
        
            if(is_null($s->a_sira)) {
                $s->a_sira = $k;
            }
        
            $a_cevap_sirasi = $s->a_sira;
            if($kitapcik=="A") {
                $cevap_sirasi = $s->a_sira;
                
            } else {
                $cevap_sirasi = $s->b_sira;
                
            }
            if(isset($ogrenci_cevaplari[str_slug($s->brans)][$cevap_sirasi-1])) {
                //Log::debug("$s->brans " . $ogrenci_cevaplari[str_slug($s->brans)][$cevap_sirasi-1]);
                $this_ogrenci_cevap = $ogrenci_cevaplari[str_slug($s->brans)][$cevap_sirasi-1];
            
                ?>
     
                #soru{{$s->id}} input#cevap{{$a_cevap_sirasi}}_{{$s->cevap}}:after { background:#9ccc65 !important;}
                <?php if($s->cevap!=$this_ogrenci_cevap && $this_ogrenci_cevap!="*")  {  
                 //   Log::debug("$s->brans " . $cevap_sirasi. " " . $this_ogrenci_cevap);
                    ?>
                     #soru{{$s->id}} input#cevap{{$a_cevap_sirasi}}_{{$this_ogrenci_cevap}}:after { background:#ffca28 !important;  } 
                     #soru{{$s->id}} .yanlis-cozdun { display:block !important;}
                 <?php } elseif($this_ogrenci_cevap!="*" && $s->cevap==$this_ogrenci_cevap) {
                 //   Log::debug("$s->brans " . $cevap_sirasi. " " . $this_ogrenci_cevap);
                      ?>
                       #soru{{$s->id}} .dogru-cozdun { display:block !important;}
                      <?php 
                 } elseif($this_ogrenci_cevap=="*") {
                   // Log::debug("$s->brans " . $cevap_sirasi. " " . $this_ogrenci_cevap);
                      ?>
                      #soru{{$s->id}} input#cevap{{$a_cevap_sirasi}}_Bos:after { background:#ffca28 !important;  } 
                      #soru{{$s->id}} .bos-cozdun { display:block !important;}
                      <?php 
                 }
            }
            
            $k++; 
       } ?>
       .cozum-text {
           text-align:center;
           border-radius:10px;
           padding:10px;
           margin:10px 0;
       }
       .optik {
           transform: scale(1.5);
       }
       /*
       .sorun-bildir {
           display:none;
       }
       */
       .cozumler .row {
           overflow:hidden;
       }
   </style> 
   <?php } else {
     ?>
     <div class="col-12">
        {{ bilgi("Soru bankası ile ilişkili herhangi bir soru olmadığından sorular gösterilemiyor.")}}
     </div>
     <?php 
    } ?>
   <div class="modal" id="video_modal">
   <div class="modal-dialog modal-lg">
     <div class="modal-content">
 
       <!-- Modal Header -->
       <div class="modal-header">
         <h4 class="modal-title"></h4>
         <button type="button" class="close" data-dismiss="modal">&times;</button>
       </div>
 
       <!-- Modal body -->
       <div class="modal-body">
         <iframe width="100%" style="height:80vh"
             src="https://www.youtube.com/embed/{{$id}}?autoplay=1&showinfo=0" id="video_iframe" frameborder="0" 
             allowfullscreen></iframe>
      </div>
 
       <!-- Modal footer -->
       <div class="modal-footer">
 
       </div>
 
     </div>
   </div>
 </div> 
 <?php }  ?>
