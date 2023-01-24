<?php if(getisset("user-detay")) {
    if(isset($read_only)) {
         ?>
         <script>
             $(function(){
                $(".user-detay *").attr("readonly","readonly");
                $(".kurtarma").addClass("d-none");
             });
         </script>
         <?php 
    }
			 ?>
<?php $u = u2(get("user-detay"));


?>

{{col("col-12","Kullanıcı Detayları")}}
    <div class="row user-detay">
        <div class="col-md-6">
            Adı: <br>
            <input type="text" name="name" value="{{$u->name}}" table="users" id="{{$u->id}}" class="name{{$u->id}} form-control edit" />
        </div>
        <div class="col-md-6">
            Soyadı: <br>
            <input type="text" name="surname" value="{{$u->surname}}" table="users" id="{{$u->id}}" class="name{{$u->id}} form-control edit" />
        </div>
        <div class="col-md-6">
            Kullanıcı Adı / E-Posta: <br>
            <input type="text" name="email" value="{{$u->email}}" table="users" id="{{$u->id}}" class="name{{$u->id}} form-control edit" />
        </div>
        <div class="col-md-6">
            Telefon: <br>
            <input type="text" name="phone" value="{{$u->phone}}" table="users" id="{{$u->id}}" class="name{{$u->id}} form-control edit" />
        </div>
        <div class="col-md-6">
            Anne Telefonu: <br>
            <input type="text" name="anne" value="{{$u->anne}}" table="users" id="{{$u->id}}" class="name{{$u->id}} form-control edit" />
        </div>
        <div class="col-md-6">
            Baba Telefonu: <br>
            <input type="text" name="baba" value="{{$u->baba}}" table="users" id="{{$u->id}}" class="name{{$u->id}} form-control edit" />
        </div>
        <div class="col-md-12">
            Okul: <br>
            <input type="text" name="okul" value="{{$u->okul}}" table="users" id="{{$u->id}}" class="name{{$u->id}} form-control edit" />
        </div>
        <div class="col-md-3">
            Tür:
            <?php $tur = explode(",","Son Sınıf,Mezun,8. Sınıf") ?>
            <select name="tur" id="{{$u->id}}" class="form-control edit" table="users" >
                <option value="">Seçiniz</option>
                <?php foreach($tur AS $t) { ?>
                    <option value="{{$t}}" <?php if($t==$u->tur) echo "selected"; ?>>{{e2($t)}}</option>
                <?php } ?>
            </select>
        </div>
        <div class="col-md-3">
            Alan: <br>
            <?php $alanlar = alanlar(); ?>
							<select name="alan" id="{{$u->id}}" class="form-control edit" table="users" >
								<option value="">Seçiniz</option>
								<?php foreach($alanlar AS $alan) { ?>
									<option value="{{$alan}}" <?php if($alan==$u->alan) echo "selected"; ?>>{{e2($alan)}}</option>
								<?php } ?>
							</select>
        </div>
        
        <div class="col-md-3">
            Sınıf: <br>
            <input type="text" name="sinif" value="{{$u->sinif}}" table="users" id="{{$u->id}}" class="name{{$u->id}} form-control edit" />
        </div>
        <div class="col-md-3">
            Şube: <br>
         
            <input type="text" name="sube" value="{{$u->sube}}" table="users" id="{{$u->id}}" class="name{{$u->id}} form-control edit" />
        </div>
        <div class="col-md-12">
            Adres: <br>
            <textarea  name="address" value="" table="users" id="{{$u->id}}" class="name{{$u->id}} form-control edit" cols="30" rows="10">{{$u->address}}</textarea>
        </div>
        <div class="col-md-6">
            İl: <br>
            <input type="text" name="il" value="{{$u->il}}" table="users" id="{{$u->id}}" class="name{{$u->id}} form-control edit" />
        </div>
        <div class="col-md-6">
            İlçe: <br>
            <input type="text" name="ilce" value="{{$u->ilce}}" table="users" id="{{$u->id}}" class="name{{$u->id}} form-control edit" />
        </div>
        <div class="col-md-4">
            Seviye: <br>
            <input type="text" name="level" value="{{$u->level}}" table="users" id="{{$u->id}}" class="name{{$u->id}} form-control edit" />
        </div>
        <div class="col-md-12">
            Etki Alanı: <br>
            <input type="text" name="alias" value="{{$u->alias}}" table="users" id="{{$u->id}}" class="alias{{$u->id}} form-control edit" />
        </div>
        <div class="col-md-3 kurtarma">
            Kurtarma Şifresi: <br>
            <button  onclick="" title="{{__('Kullanıcının şifresini sıfırla')}}" class="btn btn-danger sifre-sifirla" ><i class="fa fa-sync"></i> {{e2("Şifre Sıfırla")}}</button>
            {{$u->recover}}
        </div>
        <div class="col-md-3">
            {{e2("Giriş bilgilerini gönder")}}
            <button class="btn btn-success sms-gonder">{{e2("SMS ve Mail Olarak Gönder")}}</button>
        </div>
        <div class="col-md-3">
            {{e2("Veli profillerini yoksa oluştur")}}
            <button class="btn btn-warning" onclick="
            var bu = $(this);
            bu.html('İşlem yapılıyor...');
            $.get('?ajax=ogrenci-veli-create&id={{$u->id}}',function(d){
                alert(d.trim());
                bu.html('İşlem yapıldı!');
            });
            
            "><i class="fa fa-parent"></i>{{e2("Veli profillerini oluştur")}}</button>
        </div>
        <div class="col-md-3">
            {{e2("Veli Şifre Sıfırla")}} <br>
            <button class="btn btn-info" onclick="
            var bu = $(this);
            bu.html('İşlem yapılıyor...');
            $.get('?ajax=ogrenci-veli-password-reset&id={{$u->id}}',function(d){
                alert(d.trim());
                bu.html('İşlem yapıldı!');
            });
            
            "><i class="fa fa-parent"></i>{{e2("Veli Şifre Sıfırla")}}</button>
        </div>
    </div>
{{_col()}}
			 <?php 
		} ?>

<script>
    $(function(){
        $(".sms-gonder").on("click",function(){
            var bu = $(this);
            $(this).html("{{e2("SMS ve E-Posta gönderiliyor...")}}");
            $.get("{{url("admin-ajax/send-sms-email-password?id=".$u->id)}}",function(d){
                bu.html("{{e2("SMS ve E-Posta gönderildi")}}");
            });
        });
        $(".sifre-sifirla").on("click",function(){
            if(confirm("{{e2("Şifreyi sıfırlamak istediğinizden emin misiniz?")}}")) {
                $(this).html('{{e2("Şifre sıfırlanıyor...")}}');$.get('{{url('admin-ajax/password-update?id='.$u->id)}}',function(){location.reload()});
            }
        });
    });
</script>