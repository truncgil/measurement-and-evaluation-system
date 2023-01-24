{{col("col-12","Önerilen Kaynaklar Listesi")}} 
    <div class="row">
        <div class="col-md-12 mb-10">
            <script>
                $(document).ready(function(){
                    $("#ara").on("keyup", function() {
                        var value = $(this).val().toLowerCase();
                        $(".kaynaklar .kaynak").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                        });
                    });
                    window.setTimeout(function(){
                        $("#ara").trigger("keyup");
                    },100);
                });
            </script>
                <form action="" method="get">
                    <input type="hidden" name="t" value="kaynak-onerileri">
                    <div class="input-group">
                        <input type="search" name="q" placeholder="{{e2("Ara...")}}" value="{{get("q")}}" id="" class="form-control">
                        <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                </form>
        </div>
    </div>
    <?php 
    $kaynaklar = db("kaynaklar")
    ->where("y",1)
    ->where(function($query) use($u){
        if($u->alias!="") {
            $query->orWhere("alias","like","%{$u->alias}%");
        }
        $query->orWhereNull("alias");
        $query->orWhere("alias","");
        
    })
    ->orderBy("id","DESC");
    if(!getesit("q","")) {
        $q = "%{$_GET['q']}%";
        $kaynaklar = $kaynaklar->where(function($query) use($q){
            $query->orWhere("title","like",$q);
            $query->orWhere("brans","like",$q);
        });
    }
    $kaynaklar = $kaynaklar
    ->simplePaginate(20);
    ?>
    {{$kaynaklar->appends($_GET)->links()}}
    <div class="row kaynaklar">
        
        
        <?php 
        foreach($kaynaklar AS $k)  { 
         $j = j($k->json);
          ?>
             {{col("col-md-4 text-center kaynak",$k->brans . " " . $k->title)}} 
             <img src="{{picture2($k->cover,256)}}" alt="{{$k->title}}" class="img-fluid">
               
             <div class="btn-group mt-5">
                 <a href="?t=kaynak-onerileri&id={{$k->id}}" class="btn btn-primary" title=""><i class="fa fa-users"></i> {{e2("Atanan Öğrenciler")}}</a>
                 
                 
             </div>
             {{_col()}} 
         <?php } ?>
         
    </div>
    {{$kaynaklar->appends($_GET)->links()}}
{{_col()}}