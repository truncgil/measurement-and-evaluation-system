<?php if(getisset("islem")) {
    db("contents")->where("type","katsayi-puan")->delete();
    ekle([
        "type" => "katsayi-puan",
        "title" => "katsayi-puan",
        "slug" => "katsayi-puan",
        "json" => json_encode_tr($_POST)
    ],"contents");

} ?>
<?php $c = c("katsayi-puan"); 
$j = @j($c->json);
?>
<form action="?islem" method="post" class="serialize">
    {{csrf_field()}}
    <?php $dizi = explode(",","TYT,YKS-SAY,YKS-EA,YKS-SÖZ,LGS,LGS6,LGS7");
    foreach($dizi AS $d) {
        $key = str_slug($d);
         ?>
         <h1 class="content-heading">{{$d}} Puan Hesaplama</h1>
         <?php $branslar = contents("Branşlar"); ?>
         <?php foreach($branslar AS $b) {
              ?>
              <span class="brans badge badge-primary btn-xs" data-id="{{$key}}" text="{{$b->title}}">[{{$b->title}}]</span>
              <?php 
         } ?>
         <textarea name="{{$key}}" id="{{$key}}" cols="30" rows="3" class="form-control" placeholder="Puan Hesaplama Formülünü Buraya Yazınız. Örn: [Branş Adı]*Çarpan+Katsayı">{{@$j[$key]}}</textarea>
         <?php 
    }
    ?>
    <button class="btn btn-primary" type="submit">{{e2("Kaydet")}}</button>
</form>
<script>
    $(function(){
        $(".brans").on("click",function(){
            var id = $(this).attr("data-id");
            var isaret = "";
            if($("#"+id).val()!="") {
                isaret = "+";
            }
            $("#"+id).val($("#"+id).val()+isaret+$(this).text());
        });
    });
</script>