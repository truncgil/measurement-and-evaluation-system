<div class="row">
    {{col("col-md-12","Paketleriniz")}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-bordered">
                <tr>
                    <th>{{e2("Tarih")}}</th>
                    <th>{{e2("Tutar")}}</th>
                    <th>{{e2("Kişi Sayısı")}}</th>
                    <th>{{e2("Durum")}}</th>
                    <th>{{e2("İşlem")}}</th>
                </tr>
                <?php $paketler = db("kurum_paketleri")->where("kid",$u->id)->orderBy("id","DESC")->get(); 
                foreach($paketler AS $p)  { 
                    $kurum = @$users[$p->kid];
                    ?>
                    <tr>
                        <td>{{df($p->created_at)}}</td>
                        <td>{{price($p->price)}}</td>
                        <td>{{$p->max}}</td>
                        <td>{{$p->durum}}</td>
                        <td>
                          <?php if($p->durum=="Ödeme Bekliyor") {
                               ?>
                               <a href="{{url("odeme")}}" class="btn btn-success btn-hero">{{e2("Hemen Öde")}}</a>
                               <?php 
                          } ?>
                        </td>
                    </tr> 
                    <?php } ?>
            </table>
        </div>
    {{_col()}}
</div>