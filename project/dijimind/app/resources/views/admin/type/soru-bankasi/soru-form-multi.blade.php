

<?php // print2($_POST); 

$action = "?ekle";
$button_text = "Soruyu Havuza Ekle";
if(getisset("test-ekle")) {
   // print2($_FILES);
    /*
    $images = new Imagick("test.pdf"); 
print_r($images);
foreach($images as $i=>$image) {
    $image->setResolution(300,300);
    //etc 
    $image->writeImage("page".$i.".jpg"); 
} 
 */
    if($_FILES['pdf']['name']!="") {
      //  ini_set('max_execution_time', 0);
      //  set_time_limit(0);

        $id = date("dmYHis") ."-" . rand();
        $alias = u()->alias;
        $upload_path = "/$alias/$id";
        $path = "storage/app/files/pdf/$upload_path";
        $pdf = upload("pdf","pdf/$upload_path");
        //echo $pdf;
       
        $_SESSION['title'] = post("title");
        $_SESSION['pdf'] = $pdf;
        $file = $pdf;
    }
    if($_FILES['file']['name']!="") {
        try {
            $file = upload("file","/test");
            oturumAc();
            $_SESSION['title'] = post("title");
        } catch (\Throwable $th) {
            bilgi("Lütfen dosya seçiniz");
        }
    }
        
    
} elseif(oturumisset("files")) {
    $files = oturum("files");
    //print2($files);
    $file = $files[0];
} ?>

<form action="?test-ekle&multi" enctype="multipart/form-data" method="post" >
{{csrf_field()}}
    {{e2("Test / Deneme Sınavı Adı")}}:
    <input type="text" name="title" required value="{{oturum("title")}}" id="grouptitle" class="form-control">
    {{e2("Görsel")}}:
    <input type="file" name="file" class="form-control" accept="image/*" id="">
    {{e2("PDF")}}:
    <input type="file" name="pdf" accept="application/pdf,application/vnd.ms-excel" class="form-control" id="">
    <br>
    <button class="btn btn-primary mb-5">Yükle</button>
    <br>
    <br>
    <br>
    <br>
</form>
<?php if(isset($file)) { ?>

@include("admin.type.soru-bankasi.pdfjs")
@include("admin.type.soru-bankasi.cropper")
<script>
    $(function(){
        $(".serialize-extend").on("submit",function(){
            for (var i in CKEDITOR.instances) {
                CKEDITOR.instances[i].updateElement();
            };
            var bu = $(this);
            bu.find(".kaydet-btn").html("İşlem yapılıyor...");
            $.ajax({
                type : $(this).attr("method"),
                url : $(this).attr("action"),
                data : $(this).serialize(),
                success: function(){
                    bu.find(".kaydet-btn").html("Değişiklikler Kaydedildi");
                    $(".preloader").addClass("d-none");
                    Codebase.helpers('notify', {
                        align: 'right',             // 'right', 'left', 'center'
                        from: 'top',                // 'top', 'bottom'
                        type: 'success',               // 'info', 'success', 'warning', 'danger'
                     //   icon: 'fa fa-check mr-5',    // Icon class
                        message: '{{e2("Soru havuza başarıyla kaydedilmiştir")}}'
                    });
                    /*
                    $(".darkroom-button-default:eq(0)").click();
                    window.setTimeout(function(){
                        $(".darkroom-button-default:eq(4)").click();
                    },400);
                    */
                }

            });
            
            return false;

        });
    });
</script>
<form action="{{$action}}" class="serialize-extend" method="post">
    <div>
        <input type="hidden" name="group" id="group">
        <textarea name="html" id="result" class="d-none" cols="30" rows="10"></textarea>
    </div>
    <?php if(getisset("soru-duzenle")) {
         ?>
         <input type="hidden" name="id" value="{{$soru->id}}">
         <?php 
    } ?>
{{csrf_field()}}


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
        <br>Branş: <br>
        
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
        <option value="">Taksonomik Düzey Seçiniz</option>
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
<select name="type" id="" class="form-control">
    <option value="Herkese Açık">{{e2("Herkese Açık")}}</option>
    <option value="Konu Tarama Testi" selected>{{e2("Konu Tarama Testi")}}</option>
</select>
<br>

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

<button class="btn btn-primary kaydet-btn">{{e2($button_text)}}</button>
</form>
<?php } ?>
