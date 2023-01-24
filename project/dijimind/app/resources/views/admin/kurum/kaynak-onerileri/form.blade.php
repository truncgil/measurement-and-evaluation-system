<?php $u = u(); ?>
<?php if(getisset("kaynak-ekle")) {
    $cover = "";
    if(isset($_FILES['cover']['name'])) {
        $cover = upload("cover","kaynaklar/");
        $cover = str_replace("storage/app/files/","",$cover);
    }
   // dd($cover);
   $alias = "";
   if($u->level=="Kurum") {
    $alias = $u->alias;
   }
    ekle([
        "title" => post("title"),
        "cover" => $cover,
        "brans" => post("brans"),
        "alias" => $alias,
        "y" => 1,
        "json" => json_encode_tr($_POST)
    ],"kaynaklar");
} ?>
{{col("col-12","Kaynak Ekle")}} 
        <form action="?t=kaynak-onerileri&kaynak-ekle" method="post" enctype="multipart/form-data">
            @csrf
            {{e2("Kaynak Adı")}}
            <select name="title" id="" class="form-control select2">
                <?php $kaynaklar = db("kaynaklar")->where("y",1)
                ->where(function($query) use($u){
                    $query->orWhereNull("alias");
                    $query->orWhere("alias","like",$u->alias);
                   
                })
                ->where("y",1)
                ->select("title")
                ->get(); ?>
                <?php foreach($kaynaklar AS $k)  { 
                  ?>
                 <option value="{{$k->title}}">{{$k->title}}</option> 
                 <?php } ?>
            </select>
            {{e2("Branşı")}}
            <select name="brans" id="" class="form-control select2">
                <?php foreach(branslar() AS $b)  { 
                  ?>
                 <option value="{{$b->title}}" >{{$b->title}}</option> 
                 <?php } ?>
            </select>
            {{e2("Kapak Foto")}}
            <input type="file" name="cover" required id="" class="form-control">
            {{e2("Konular")}}
            <table class="table table-bordered table-striped kitap-table">
                <thead>
                    <tr>
                        <th>{{e2("Konular")}}</th>
                        <th>{{e2("Sayfa Başlangıcı")}}</th>
                        <th>{{e2("Sayfa Bitişi")}}</th>
                    </tr>
                </thead>
                <tbody>
                    <div id="clone">
                    <tr>
                        <td>
                            <input type="text" name="konu[]" id="" class="form-control">
                        </td>
                        <td>
                            <input type="number" name="bas[]" id="" class="form-control">
                        </td>
                        <td>
                            <input type="number" name="son[]" id="" class="form-control">
                        </td>
                    </tr>
                    </div>
                </tbody>
            </table>
            
            <div class="float-right">
                <div class="btn btn-primary satir-ekle"><i class="fa fa-plus"></i> {{e2("Satır Ekle")}}</div>
            </div>
            <div class="clearfix"></div>
            <button class="btn btn-primary btn-hero mt-10">{{e2("Gönder")}}</button>
            <script>
                $(function(){
                    var rowIdx = 0;
  
                    $(".satir-ekle").on("click",function(){
                        var table = $('.kitap-table');
                        lastRow = table.find('tbody tr:last');
                        console.log(lastRow);
                        rowClone = lastRow.clone();
                        table.find('tbody').append(rowClone);
                        table.find('tbody tr:last select').prop("selectedIndex", 0);
                        table.find('tbody tr:last input').val("");
                    });
                });
            </script>
        </form>
     {{_col()}}