

<?php // print2($_POST); 
if(getisset("ekle")) {
    unset($_POST['_token']);
    if(postisset("cevap")) {
        $_POST['cevap'] = implode(",",$_POST['cevap']);
    }
    
    if(postisset("sinif_duzey")) {
        $_POST['sinif_duzey'] = implode(",",$_POST['sinif_duzey']);
    }
    $base = $_POST['html'];
    $base = str_replace("data:image/jpeg;base64,","",$base);
    $base = str_replace("data:image/png;base64,","",$base);
    if (base64_decode($base, true)) { //eğer gelen base64 ise işlemleri yap ve html verisini sil
		$grup = str_slug(post("group"));
		$path = "storage/app/files/soru_bankasi/$grup";
		@mkdir($path,0777,true);
		$file = "$path/".rand().".jpg";
		//echo $file;
		//$html = "<img src='$file'>";
		//echo $html;
		// create an image file
        $_POST['cover'] = $file;
        $_POST['html'] = "";
		$fp = fopen($file, "w+");
		
		// write the data in image file
		fwrite($fp, base64_decode($base));
		
		// close an open file pointer
		fclose($fp);
    }
  //  print2($_POST);
    ekle($_POST,"soru_bankasi");
    bilgi("Sorunuz havuza eklenmiştir.");
}
if(getisset("soru-sil")) {
    db("soru_bankasi")
    ->where("id",get("soru-sil"))
    ->where("uid",u()->id)
    ->delete();
    bilgi("Sorunuz havuzdan silinmiştir");
}
if(getisset("soru-guncelle")) {
    unset($_POST['_token']);
    if(postisset("cevap")) {
        $_POST['cevap'] = implode(",",$_POST['cevap']);
    }
    if(postisset("sinif_duzey")) {
        $_POST['sinif_duzey'] = implode(",",$_POST['sinif_duzey']);
    }
    //print2($_POST); exit();
    db("soru_bankasi")
    ->where("id",get("soru-guncelle"))
    //->where("uid",u()->id)
    ->update($_POST);
    bilgi("Soru başarıyla güncellenmiştir.");
}
$action = "?ekle";
$button_text = "Soruyu Havuza Ekle";
if(getisset("soru-duzenle")) {
    $action = "?soru-guncelle=" . get("soru-duzenle");
    $button_text = "Soruyu Güncelle";
    $soru = db("soru_bankasi")->where("id",get("soru-duzenle"))->first();
  //  print2($soru);
     ?>
     <script>
         $(function(){
            <?php foreach($soru AS $s => $d) {
                $d = trim($d);
                 $d = str_replace("\n","",$d);
               //  console.log($s);
                if($s!="" || $d!="") {
                    if($s!="html") {
                        if($s=="sinif_duzey") {
                           
 ?>
 $("#sinif_duzey").val([{{$d}}]);
 <?php 
                        } elseif($s=="cevap") {
                             ?>
                              $(".cevap-isaret input").prop("checked",false);
                              $(".cevap-isaret input").attr("checked",false);
                              $("#cevap_{{$d}}").prop("checked",true);
                              $("#cevap_{{$d}}").attr("checked",true);
                             <?php 
                        } elseif($s=="on_tanim") {
                             ?>
                             <?php if($d=="ok")  { 
                               ?>
                               $("#on_tanim").prop("checked",true);
                               $("#on_tanim").attr("checked",true); 
                              <?php } ?>
                           
                             <?php 
                        } elseif($s=="brans") {
 ?>                         window.setTimeout(function(){
$("#brans").val("{{$d}}").trigger("change");
                           
 },500);
                                 
 <?php                         
                        } elseif($s=="kazanim"){
 ?>
  window.setTimeout(function(){
 //$("#kazanim").val("{{$d}}");
                           
                        },3000);
 <?php 
                        } elseif($s=="konu"){
 ?>
  window.setTimeout(function(){
  $("#konu").val("{{$d}}").trigger("change");
                           
                        },1000);
 <?php 
                        } else {
                             ?>
                            $("[name='{{$s}}']").val("{{$d}}");
                             <?php 
                        }
                 ?>
                    
                 <?php 
                    }
                    
                }
            } ?>
         });

     </script>
     <?php 
}
?>
<form action="{{$action}}" method="post">
    <?php if(getisset("soru-duzenle")) {
         ?>
         <input type="hidden" name="id" value="{{$soru->id}}">
         <?php 
    } ?>
{{csrf_field()}}
Soru İçeriği
<?php if(getisset("soru-duzenle")) {
    if($soru->cover!="")  { 
     
      ?>
      <br>
      <img src="{{url($soru->cover)}}" style="max-width:100%;" alt="">
  
     <?php } ?>
     <?php 
} ?>
<textarea name="html" required id="" cols="30" rows="10" class="ckeditor"><?php if(getisset("soru-duzenle")) {
    echo $soru->html;
} ?></textarea>

Cevap:  <br>

        <label class="cevap-isaret"><input type="checkbox" name="cevap[]" class=" "
                id="cevap_A" value="A" checked="checked">
            <span>A</span>
        </label><label class="cevap-isaret"><input type="checkbox" name="cevap[]"
                class=" " id="cevap_B" value="B">
            <span>B</span>
        </label><label class="cevap-isaret"><input type="checkbox" name="cevap[]"
                class=" " id="cevap_C" value="C">
            <span>C</span>
        </label><label class="cevap-isaret"><input type="checkbox" name="cevap[]"
                class=" " id="cevap_D" value="D">
            <span>D</span>
        </label><label class="cevap-isaret"><input type="checkbox" name="cevap[]"
                class=" " id="cevap_E" value="E">
            <span>E</span>
        </label>
        <br>{{e2("Branş")}}: <br>
        <?php $kazanimlar = kazanimlar(); ?>

    
                                <select name="brans" onchange="bransChange($(this))" id="brans" required class="brans select2 form-control">
                                    <option value="">{{e2("Branş Seçiniz")}}</option>
                                    <?php foreach($kazanimlar AS $brans => $deger) {
                                        ?>
                                        <option value="{{$brans}}">{{$brans}}</option>
                                        <?php 
                                    } ?>
                                    
                                </select>
                                {{e2("Konu")}}: <br>
                                <select name="konu" id="konu" onchange="konuChange($(this))" class="konu select2 form-control">
                                    
                                </select>
                                {{e2("Kazanım / Alt Kazanım")}}: <br>
                                <select name="kazanim" id="kazanim" class="kazanim select2 form-control">
                                </select>
{{e2("Taksonomik Düzey")}}
<select name="taksonomik_duzey" id="" class="form-control">
        <option value="">{{e2("Taksonomik Düzey Seçiniz")}}</option>
    <?php foreach(tak_list() AS $t) {
            ?>
            <option value="{{$t}}">{{$t}}</option>
            <?php 
    } ?>
    
</select>
{{e2("Sınıf Düzeyi")}}
<select name="sinif_duzey[]" id="sinif_duzey" class="form-control select2" multiple>
  <?php for($k=2;$k<=13;$k++) { 
       ?>
       <option value="{{$k}}">{{$k}}. Sınıf</option>
       <?php 
  } ?>
</select>
{{e2("Soru Türü")}} 
<?php $soru_tipler = soru_tipler(); ?>
                    <select name="type"  class="form-control " >
                        <option value="">{{e2("Soru Görünürlüğü")}}</option>
                        <?php foreach($soru_tipler AS $st)  { 
                          ?>
                         <option value="{{$st}}">{{e2($st)}}</option>
                          
                         <?php } ?>
                       
                    </select>
<label >
<input type="checkbox" title="{{e2("Eğer bu bir soru öncesinde kullanılan ön tanım ise işaretleyiniz")}}" value="ok" name="on_tanim" id="on_tanim"> {{e2("Ön Tanım ")}}</label> <br>

{{e2("Eğer paragraf sorusuna bağlı bir soru ise, paragrafın bulunduğu soru ID numarasını giriniz")}}
<input type="number" name="paragraf_grup" id="paragraf_grup" class="form-control">
{{e2("B Kitapçığı Sırası")}}
<input type="number" name="b_sira" id="b_sira" class="form-control">
{{e2("C Kitapçığı Sırası")}}
<input type="number" name="c_sira" id="c_sira" class="form-control">
{{e2("D Kitapçığı Sırası")}}
<input type="number" name="d_sira" id="d_sira" class="form-control">
<br>
<br>

<button class="btn btn-primary">{{e2($button_text)}}</button>
</form>
<script>
    $(function(){
        $(".select2").select2();
    });
</script>