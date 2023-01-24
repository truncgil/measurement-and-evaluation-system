<div class="content">
    <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title"><i class="fa fa-{{$c->icon}}"></i> {{e2($c->title)}}</h3>
            </div>
            <div class="block-content">
                <?php $hatalar = db("soru_hata_bildirimleri")->orderBy("id","DESC")->simplePaginate(100); ?>
                {{$hatalar->links()}}
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Soru Bilgisi</th>
                            <th>Öğrenci Bilgisi</th>
                            <th>Sorun Türü</th>
                            <th>Tarih</th>
                        </tr>
                        <?php foreach($hatalar AS $h)  { 
                          ?>
                         <tr>
                             <td>
                                <button data-toggle="collapse" class="btn btn-primary" data-target="#soru{{$h->id}}">{{$h->kid}}</button>

                                <div id="soru{{$h->id}}" style="width:400px" class="collapse">
                                    <?php $s = json_decode($h->json); ?>
                                    @include("admin.inc.soru")
                                </div>

                             </td>
                             <td>
                             <?php $ogrenci = json_decode($h->html); 
                             ?>
                                <button data-toggle="collapse" class="btn btn-danger" data-target="#ogrenci{{$h->id}}">{{$ogrenci->name}} {{$ogrenci->surname}}</button>

                                <div id="ogrenci{{$h->id}}" style="width:400px" class="collapse">
                                   
                                    @include("admin.inc.ogrenci-info")
                                </div>

                             </td>
                             <td>{{$h->title}}</td>
                             <td>{{df($h->created_at)}}</td>
                         </tr> 
                         <?php } ?>
                    </table>

                </div>
                {{$hatalar->links()}}

            </div>

            

        </div>

    </div>
</div>