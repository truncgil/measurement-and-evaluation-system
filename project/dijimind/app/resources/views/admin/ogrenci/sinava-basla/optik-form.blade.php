<div class="modal" id="sinavi-bitir">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">{{e2("Sınavı Bitir")}}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body text-center">
            <p>{{e2("Sınav Tamamlanacaktır. Devam etmek istediğinizden emin misiniz?")}} 
                <br>
                Kalan Süre: <strong class="kalan-sure"></strong> 
                <br>
                Kalan Soru: <strong class="kalan-soru"></strong>
               

            </p>

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <a href="?t=sinavi-bitir"  class="btn btn-success">{{e2("Sınavı Bitir")}}</a>
        <button type="button" class="btn btn-danger" data-dismiss="modal">{{e2("Vazgeç")}}</button>
      </div>

    </div>
  </div>
</div>
<div class="modal" id="optik-form">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Optik Form</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <?php 
        $dizi = array();
        $cache = array();
        if(oturumisset("cache")) {
            $cache = $_SESSION['cache']['cevap'];
        }
        foreach($sorular AS $s) {
            if(!isset($dizi[$s->brans])) $dizi[$s->brans] = 0;
            $dizi[$s->brans]++;
        }
        
        ?>
        <div class="text-center mb-10">
            <p>Sınavınızın bitmesine <span class="kalan-sure"></span> var. Sınavı şimdi bitirebilirsiniz. Ancak önesinde aşağıdaki optik formunuzu gözden geçirmenizi öneririz.</p>
            <div class="btn btn-success sinavi-bitir">{{e2("Sınavı Bitir")}}</div>
        </div>
        <div class="row">
            <?php
            $k=1;
            foreach($dizi AS $ders => $s)  { 
              ?>
             {{col("col-lg-4",$ders)}}
                <table class="table table-striped table-hover">
                    <?php for($a=1;$a<=$s;$a++)  { 
                        $cevap = @$cache[$k];
                      ?>
                     <tr class="goto" data-index="{{$k}}">
                         <td class="">
                             <strong>{{$a}}</strong>
                         </td>
                         <td class=" text-center"><div id="optik{{$k}}">
                            {{cevap_input2("optik".$k,"",$cevap)}}    
                       </div></td>
                         
                     </tr> 
                     <?php $k++; } ?>
                </table>
             {{_col()}} 
             <?php  } ?>
        </div>        
        <div class="text-center mb-10">
            <div class="btn btn-success sinavi-bitir">{{e2("Sınavı Bitir")}}</div>
        </div>
       
            
      </div>

      <!-- Modal footer -->

      <div class="modal-footer">
        <?php if(!getisset("pdf"))  { 
          ?>
         <button type="button" class="btn btn-danger" data-dismiss="modal">{{e2("Kapat")}}</button> 
         <?php } else {
            ?>
            <div class="text-center mb-10">
              <div class="btn btn-success sinavi-bitir" style="display:block !important">{{e2("Sınavı Bitir")}}</div>
          </div>
            <?php 
         } ?>
      </div>

    </div>
  </div>
</div>