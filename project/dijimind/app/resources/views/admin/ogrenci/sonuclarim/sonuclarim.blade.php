{{col("col-12","Sonuçlarım",4)}}
        <a name="sonuclarim"></a>
        <div id="accordion2" role="tablist" aria-multiselectable="true">
            <?php $sonuclar = db("sonuclar")
            ->where(function($query) use($u) {
                $query = $query->orWhere("uid",$u->id);
                $query = $query->orWhere("tc_kimlik_no",$u->email);
            });
            if(getisset("type")) {
                $sonuclar = $sonuclar->where("title","LIKE","%".get("type")."%");
            }
            $sonuclar = $sonuclar
            ->orderBy("id","DESC")
            ->simplePaginate(10); 
            //print2($u);
            
            ?>
            <div class="btn-group mb-5">
                <a href="?t=sonuclarim" class="btn btn-primary">{{e2("Tümü")}}</a>
                <a href="?t=sonuclarim&type=KAS#sonuclarim" class="btn btn-success">{{e2("Kas Sınavlarım")}}</a>
                <a href="?t=sonuclarim&type=DENEME#sonuclarim" class="btn btn-info">{{e2("Deneme Sınavlarım")}}</a>
            </div>
            {{$sonuclar->appends($_GET)->links()}}
            <?php foreach($sonuclar AS $s)  { 
              ?>
             <div class="block block-bordered block-rounded mb-2">
                 <div class="block-header" role="tab" id="accordion2_h1">
                     <a class="font-w600" data-toggle="collapse" data-parent="#accordion2" href="#accordion{{$s->id}}" aria-expanded="true" aria-controls="accordion2_q1">{{$s->title}} 
                         <div class="badge badge-success">{{$s->type}}</div>
                         <div class="badge badge-danger">{{df($s->created_at)}}</div>
                         <div class="badge badge-info">{{$s->kitapcik}}</div>
                    </a>
                 </div>
                 <div id="accordion{{$s->id}}" class="collapse " role="tabpanel" aria-labelledby="accordion2_h1">
                     <div class="block-content">
                     <div class="text-center mb-10">
                        <a href="{{url("admin?t=analizlerim&id=".$s->id)}}" class="btn btn-primary btn-hero">{{e2("Detaylı Analizler")}}</a>
                        <a href="{{url("admin?t=cozumlerim&id=".$s->id)}}" class="btn btn-danger btn-hero">{{e2("Çözümlerim")}}</a>
                    </div>
                            @include("admin.inc.ogrenci-puan-siralama")
                         
                            <?php $j = j($s->analiz);
                            $ders_listesi=false;
                          
                            ?>
                            <div class="row">
                                @include("admin.ogrenci.analiz.genel-basari")
            
                     </div>
                 </div>
                 </div>
             </div> 
             <?php } ?>
             {{$sonuclar->appends($_GET)->links()}}
           
            
        </div>
    {{_col()}}