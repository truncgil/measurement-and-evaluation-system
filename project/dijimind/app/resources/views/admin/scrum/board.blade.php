<?php if(getisset("gorev-ekle")) {
                $post = $_POST;
                $post['s'] = 0;
                unset($post['_token']);
                $post['uid'] = get("id");
                ekle2($post,"todo");
            }
$main_title = "Görevlerim"; 
$branslar = branslar();
if(!isset($onlykitap)) { //kaynak önerileri ile ilgili
    $onlykitap = false;
} else {
    $main_title = "Kitap Çalışması Görevlerim";
}
$u = u();
if($u->level!="Öğrenci") {
    if(isset($o)) {
        $u = $o;
    }
}
$boards = [];
$boards['todo']= [
    'title' => 'Yapacacaklarım',
    'color' => 'danger',
    'complete' => 0
];
$boards['doing']= [
    'title' => 'Yapmakta olduklarım',
    'color' => 'warning',
    'complete' => 2
];
$boards['complete']= [
    'title' => 'Bitirdiklerim',
    'color' => 'success',
    'complete' => 1
];
$scrum = [];
$scrum['todo'] = [];
$scrum['doing'] = [];
$scrum['complete'] = [];
$todo = db("todo")->where("uid",$u->id)->orderBy("s","ASC");
if($onlykitap) {
    $todo = $todo->where("type","Kaynak Önerisi");
}
$toplam = $todo->count();
$todo = $todo->get();
$dersGorevSayilari = [];
foreach($todo AS $t) {
    if($t->complete==0) {
        $scrum['todo'][] = $t;
    }
    if($t->complete==1) {
        $scrum['complete'][] = $t;
    }
    if($t->complete==2) {
        $scrum['doing'][] = $t;
    }
    if(!isset($dersGorevSayilari[$t->brans])) $dersGorevSayilari[$t->brans] = 0;
    $dersGorevSayilari[$t->brans]++;
}
function tarihe_gore($a,$b) {
    return strcmp($b->updated_at, $a->updated_at);
}
usort($scrum['complete'], "tarihe_gore");
?>
@include("admin.scrum.script")
<div class="row">
     {{col("col-12",$main_title)}} 
     <script>
$(document).ready(function(){
  $("#ara").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".scrumboard-items .scrumboard-item").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
            <input type="text" id="ara" placeholder="{{e2("Ara...")}}" class="form-control">
            <div class="progress mt-5 ">
                <?php foreach($boards AS $key => $b)  { 
                    $yuzde = @round(count($scrum[$key])*100/$toplam);
                  ?>
                 <div class="progress-bar bg-{{$b['color']}} progress-bar-striped progress-bar-animated" style="width:<?php echo $yuzde ?>%">
                     {{e2($b['title'])}} <br>
                     %{{$yuzde}}
                 </div> 
                 <?php } ?>
            </div>
     {{_col()}}
     <?php if(getesit("t","ogrenci-takibi"))  { 
       ?>
            @include("admin.inc.kazanim")
            {{col("col-12","Yeni Görev Ekle")}} 
            
            <form action="?t=ogrenci-takibi&id={{get("id")}}&gorev-ekle" method="post">
                @csrf
                {{e2("Branş")}}
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
                {{e2("Eklenecek Board")}} <br>
                <select name="complete" id="" onchange="
                    if($(this).val()=='1') 
                        {
                            $('.dyb-zone').removeClass('d-none');
                        } else {
                            $('.dyb-zone').addClass('d-none');
                        }
                    
                    " class="form-control">
                    <option value="0">Yapılacak</option>
                    <option value="2">Yapılmakta Olan</option>
                    <option value="1">Biten</option>
                </select>
                <div class="dyb-zone d-none">
                {{e2("Doğru")}}:
                <input type="text" name="dogru" id="" class="form-control">
                {{e2("Yanlış")}}:
                <input type="text" name="dogru" id="" class="form-control">
                {{e2("Boş")}}:
                <input type="text" name="dogru" id="" class="form-control">
                </div>

                {{e2("Görev Detay")}}:
                <input type="text" name="title" id="" class="form-control">

                
                <br>
                <button class="btn btn-primary btn-hero">Görev Ekle</button>
            
            </form>
                
            {{_col()}} 
      <?php } ?>
             {{col("col-12","Filtrele")}} 
                {{e2("Ders Adı")}}
                <select name="" id="" onchange="$('#ara').val($(this).val()).trigger('keyup');"  class="form-control select2">
                    <option value="">Tümü</option>
                    <?php foreach($dersGorevSayilari AS $brans => $sayi)  { 
                        ?>
                        <option value="{{$brans}}">{{$brans}} ({{$sayi}})</option> 
                        <?php } ?>
                </select>
             {{_col()}}
             <div class="scrumboard js-scrumboard">
                    <!-- Ideas Column -->
                    <?php foreach($boards AS $key => $b)  { 
                      ?>
                     <div class="scrumboard-col block block-themed">
                         <div class="block-header bg-{{$b['color']}}">
                             <h3 class="block-title font-w600">{{e2($b['title'])}} 
                                

                             </h3>
                             <div class="block-options">
                                <div class="badge badge-primary">{{count($scrum[$key])}}</div>
                             </div>
 
                         </div>
                         <div class="block-content block-content-full">
                         <?php if(getesit("t","ogrenci-takibi")) {
 ?>
 
 <?php 
                                     } else {

                                         ?>
                             <form class="w-100" method="post" data-toggle="sb-item-add">
                                 <div class="input-group">
                                     <div class="input-group-prepend">
                                         <span class="input-group-text">
                                             <i class="fa fa-lightbulb-o"></i>
                                         </span>
                                     </div>
                                     
                                                                              <input type="text" class="form-control" board="{{$b['complete']}}" placeholder="Yeni görev...">

                                         
                                 </div>
                             </form>
                             <?php 
                                     } ?>
                         </div>
                         <div class="scrumboard-items block-content" data-id="{{$b['complete']}}" id="{{str_slug($b['title'])}}">
                             <?php 
                             foreach($scrum[$key] AS $item)  { 
                             
                              ?>
                              @include("admin.scrum.item") 
                              <?php } ?>
                         </div>
                     </div> 
                     <?php } ?>
                    <!-- END Ideas Column -->

                   

                    

                    <!-- Add Column -->
                    <div class="scrumboard-col block block-themed block-transparent d-none">
                        <div class="block-header bg-gray">
                            <form class="w-100" method="post" data-toggle="sb-card-add">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text border-0">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control border-0" placeholder="Yeni board ekle..">
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- END Add Column -->
                </div>
                <!-- END Scrum Board -->
@include("admin.scrum.style")
</div>
<script>
    function detail(id,title) {
        $("#item_detail").modal();
        $("#item_detail .modal-title").html(title);
        $("#item_detail .modal-body").html("{{e2("Yükleniyor...")}}");
        $.get("?ajax=scrum-item-detail",{
            id : id
        },function(d) {
            $("#item_detail .modal-body").html(d);
        });
        
    }
</script>
<!-- Modal -->
<div id="item_detail" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{e2("Kapat")}}</button>
      </div>
    </div>

  </div>
</div>