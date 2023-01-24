 
 <?php $sinavlarim = db("sinavlar_ogrenci");
if(!isset($ogrenciId)) {
    $ogrenciId = $u->id;
}     

    $sinavlarim = $sinavlarim->where("uid",$ogrenciId);
    $sinavlarim = $sinavlarim->orderBy("s","ASC")
        ->get();  

    $todo = db("todo")->where("uid",$ogrenciId)->groupBy("group_hash")->get(); 
    $lock = [];
    $lock_c = 0;
    $open_c = 0;

    foreach($todo AS $t) {
        if($t->complete==1) {
            $type = "lock-open";
            $open_c++;
        } else {
            $type = "lock";
            $lock_c++;
        }
        if(!is_null($t->level)) {
            $lock[$t->brans][$t->level][$t->konu] = $type;
        } else {
            $lock[$t->brans][$t->konu] = $type;
        }
        
    }
    //dd($lock);
    $scrum = [];
    $scrum['Kilitli'] = [];
    $scrum['Kilidi Açılan'] = [];
    $scrum['Bitirilen'] = [];
    $dersSinavSayilari = [];


    foreach($sinavlarim AS $s) {


        $hash = null;
        
        if(isset($lock[$s->brans][$s->level][$s->konu])) {
            $hash = $lock[$s->brans][$s->level][$s->konu];
        } elseif(isset($lock[$s->brans][$s->konu])) {
            $hash = $lock[$s->brans][$s->level][$s->konu];
        }

        if(!is_null($hash)) {
            if($lock[$s->brans][$s->level][$s->konu]=="lock") {
                $scrum['Kilitli'][] = $s;
            } else {

                if($s->complete==1) {
                    $scrum['Bitirilen'][] = $s;
                } else {
                    $scrum['Kilidi Açılan'][] = $s;
                }
                
            }

            if(!isset($dersSinavSayilari[$s->brans])) $dersSinavSayilari[$s->brans] = 0;
            $dersSinavSayilari[$s->brans]++;
        }
            
            
            
        
    }

?>
<div class="col-12">
    <h1>{{e2("Kas Sınavlarım")}} <div class="badge badge-primary">{{count($sinavlarim)}}</div></h1>
</div> 
{{col("col-12","Filtrele")}} 
    {{e2("Ders Adı")}}
    <select name="" id="" onchange="$('#ara').val($(this).val()).trigger('keyup');"  class="form-control select2">
        <option value="">Tümü</option>
        <?php foreach($dersSinavSayilari AS $brans => $sayi)  { 
            ?>
            <option value="{{$brans}}">{{$brans}} ({{$sayi}})</option> 
            <?php } ?>
    </select>
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
{{_col()}}
        <div class="col-12">
            <input type="text" id="ara" placeholder="{{e2("Ara...")}}" class="form-control">

        </div>
 <div class="scrumboard js-scrumboard">
    <?php foreach($scrum AS $alan => $deger)  { 
        if($alan=="Kilitli") $color = "danger";
        if($alan=="Kilidi Açılan") $color = "warning";
        if($alan=="Bitirilen") $color = "success";
          ?>
        <div class="scrumboard-col block block-themed">
            <div class="block-header bg-{{$color}}">
                <h3 class="block-title font-w600">{{$alan}}
                
    
                </h3>
                <div class="block-options">
                <div class="badge badge-{{$color}}">{{count($deger)}}</div>
                </div>
    
            </div>
            <div class="block-content block-content-full">
                <div class="scrumboard-items block-content p-0" data-id="{{str_slug($alan)}}" id="{{str_slug($alan)}}">
                        <?php foreach($deger AS $item) {

                            $sinava_basla = false;
                            
                            if($alan=="Kilidi Açılan") {
                                $sinava_basla = true;
                            }
                             ?>
                             @include("admin.ogrenci.kas-sinavlarim2.scrum-item")
                             <?php 
                        } ?>
                </div>
            </div>
        </div> 
     <?php } ?>
</div>
    @include("admin.scrum.style")
    @include("admin.scrum.script")