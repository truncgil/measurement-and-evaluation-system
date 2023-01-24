
<?php 
$prefix = "";
if($u->level == "Kurum") {
    $prefix = "t=optik-okuma&";
} ?>
<div class="content">
    <div class="block">
            <div class="block-header block-header-default" >
                <h3 class="block-title"><i class="fa fa-plus"></i> {{e2("Sınav Oku")}}</h3>
            </div>
            <div class="block-content">
           
                <form action="?{{$prefix}}oku" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-md-6">
                    <div class="btn btn-default">Okunacak Veri</div> 
                        <div class="input-group">
                            
                            <input type="file" class="form-control" name="dosya" id="">
                        </div>
                        
                    </div>
                    <div class="col-md-6 d-none">
                    <div class="btn btn-default">Uygulama Adı</div>
                        <div class="input-group">
                            
                            <input type="text" class="form-control" name="title"  id="">
                        </div>
                    </div>
                    <?php if($u->level == "Admin")  { 
                      ?>
                     <div class="col-md-6">
                     <div class="btn btn-default">Etki Alanı</div>
                         <div class="input-group">
                             
                             <input type="text" class="form-control" name="alias"  id="">
                         </div>
                     </div> 
                     <?php } ?>
                    <div class="col-md-6 d-none">
                        <div class="input-group">
                            <div class="btn btn-default">Sınıf Seviyesi</div>
                            <select name="level" id=""  class="form-control">
                                <option value="">Sınıf Seviyesi Seçiniz</option>
                            <?php for($k=2;$k<=13;$k++) { ?>
                                <option value="{{$k}}">{{$k}}. Sınıf</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                    Uygulama Tarihi <br>
                        <div class="input-group">
                           
                            <input type="date" class="form-control" name="date" id="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="btn btn-default">Sınav Şablonu</div>
                            <select name="sinav" id="" required class="form-control select2">
                                <option value="">Seçiniz</option>
                            <?php $sinav = db("sinavlar");
                            if($u->level == "Kurum") {
                               
                                //$sinav = $sinav->whereIn("uid",$u->alias_ids);
                                $sinav = $sinav->where("y",1);
                                $sinav = $sinav->where(function($query) use($u) {
                                    $query->orWhere("alias",$u->alias);
                                });
                            }
                            $sinav = $sinav->get(); foreach($sinav AS $s) {  ?>
                                <option value="{{$s->id}}">{{$s->title}}</option>
                                <?php } ?>
                            </select>
                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="btn btn-default">Optik Şablonu</div>
                            <select name="optik" id="" required class="form-control select2">
                                <option value="">Seçiniz</option>
                            <?php $sinav = db("optik")->get(); foreach($sinav AS $s) {  ?>
                                <option value="{{$s->id}}">{{$s->title}}</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 text-center"> <br>
                        <button class="btn btn-primary"><i class="fa fa-cog"></i> Oku</button>
                    </div>
                </div>
                    
                </form>
                @include("admin.type.sinav-uygulamalari.sonuc-oku")
            </div>
    </div>
</div>