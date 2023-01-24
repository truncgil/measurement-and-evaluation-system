<?php
	use App\Imports\ogrencilerImport;
	use Maatwebsite\Excel\Facades\Excel;
$u = u();
    if(getisset("user-add")) {
		try {
			$rand = rand(111111,999999);
        	ekle([
				'alias' => $u->alias,
				'level' => "Öğrenci",
				"y" => 1,
				"recover" => $rand,
				"password" => Hash::make($rand)
        	],"users");

			if(!postesit("anne","")) {
				$sifre = rand(111111,999999);
				$uye_bilgi = [
					"name" => "Anne",
					"surname" => post("surname"),
					"level" => "Veli",
					"password" => Hash::make($sifre),
					"email" => post("anne"),
					"phone" => post("anne")
				];
				if(isset($admin)) {
					$uye_bilgi['alias'] = $u->alias;
				}
				try {
					ekle2($uye_bilgi,"users");
					$text = "Sayın Veli, çocuğunuz {$_POST['name']} {$_POST['surname']} sizi annesi olarak belirlemiştir. 
					Çocuğunuzun tüm aktivitelerini takip etmek için: 
					K. Adı: {$_POST['anne']}
					Şifre: $sifre
					Giriş yapmak için: 
					https://app.dijimind.com/login
					";
					@sms($text,post("anne"));
					bilgi("Anne profili oluşturuldu ve anne telefonuna bildirim gönderildi");
				} catch (\Throwable $th) {
					//throw $th;
				}
						
			}
			if(!postesit("baba","")) {
				$sifre = rand(111111,999999);
				$uye_bilgi = [
					"name" => "Baba",
					"surname" => post("surname"),
					"level" => "Veli",
					"password" => Hash::make($sifre),
					"email" => post("baba"),
					"phone" => post("baba")
				];
				if(isset($admin)) {
					
					$uye_bilgi['alias'] = $u->alias;
				}
				try {
					ekle2($uye_bilgi,"users");
					$text = "Sayın Veli, çocuğunuz {$_POST['name']} {$_POST['surname']} sizi babası olarak belirlemiştir. 
					Çocuğunuzun tüm aktivitelerini takip etmek için: 
					K. Adı: {$_POST['baba']}
					Şifre: $sifre
					Giriş yapmak için: 
					https://app.dijimind.com/login
					";
					@sms($text,post("baba"));
					bilgi("Baba profili oluşturuldu ve baba telefonuna bildirim gönderildi");
				} catch (\Throwable $th) {
					//throw $th;
				}
						
			}
		} catch (\Throwable $th) {
			bilgi("Öğrenci eklenemedi hata mesajı:". $th->getMessage());
		}
        
    }
    if(getisset("user-delete")) {
        db("users")->where("id",get("id"))
        ->where("alias",$u->alias)
        ->delete();
    }

	

    $users = db("users");
    $users = $users->where("level","Öğrenci");
    $users = $users->where("alias",$u->alias);
	//print2($users->toSql());
	if(getisset("order")) {
		$order = explode(":",get("order"));
		$users = $users->orderBy($order[0],$order[1]);
	} else {
		$users = $users->orderBy("id","DESC");
	}
	if(getisset("q2")) {
		$q = "%{$_GET['q2']}%";
		$users = $users->where(function($query) use($q) {
			$query->orWhere("name","like",$q);
			$query->orWhere("surname","like",$q);
			$query->orWhere("email","like",$q);
			$query->orWhere("sinif","like",$q);
			$query->orWhere("alan","like",$q);
		});
	}
	if($u->level!="Kurum") {
		$users = $users->where("ust",$u->id);
	}
	

    $users = $users->simplePaginate(5);
	

	$admin = true;
?>
<script>
	$(function(){
		$(".ogrenci-form .comment-form__input-box *").addClass("form-control mt-10");
		$(".uye-ol").addClass("btn btn-primary mt-10");
		$("").remove();
	});
</script>
<style>
.aciklama,.kvkk,.giris-yap, .ogrenci-form h2 {
	display:none;
}
.ogrenci-form form {
	margin:0 !important;
}
</style>
<?php 
$kurum_plani = kurum_plani();
?>
@include("admin.type.user-detay")
<?php if($u->level=="Kurum")  { 
  ?>
  {{col("col-12 ogrenci-form","Yeni Öğrenci Ekle")}} 
  	{{bilgi("Maksimum ekleyeceğiniz öğrenci sayısı: {$kurum_plani['max_ogrenci']}")}}
 	<?php if($kurum_plani['kalan_ogrenci']>0)  { 
 	  ?>
  		@include("inc.uyelik-formu") 
 	 <?php } else {
 		 bilgi("Öğrenci ekleme hakkınız dolmuştur");
 	 } ?>
  {{_col()}}
   {{col("col-12","Excelden Aktar")}} 
   			<div class="row">
 				  <div class="col-md-6">
 					<?php if(getisset("excel-aktar")) {
 							$dosya = upload2("excel","ogrenciler-excel-import/");
 							Excel::import(new ogrencilerImport, $dosya);
 						} ?>
 						<form action="?t=ogrenciler&excel-aktar" method="post" enctype="multipart/form-data">
 							{{csrf_field()}}
 							<small>Excel'den Aktar</small>
 							<div class="input-group">
 								<input type="file" name="excel" placeholder="{{e2("Excel'den Aktar")}}" class="form-control" id="">
 								<button class="btn btn-primary" title="{{e2("Öğrencileri Excel'den Aktar")}}"><i class="fa fa-file-upload"></i></button>
 							</div>
 						</form>
 				  </div>
 				  <div class="col-md-6">
 				  	<a class="btn btn-primary" href="?ajax=excel-ogrenciler">{{e2("Tüm Öğrencileri Excel Olarak İndir")}}</a>
 				  </div>
 			  </div>
    
   {{_col()}} 
 <?php } ?>
<div class="content">
<div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">
			
				<form action="" method="get">
					<div class="row">
						<div class="col-md-6">
							<div class="input-group">
								<div class="input-group-prepend">
									<button type="submit" class="btn btn-secondary">
										<i class="fa fa-search"></i>
									</button>
								</div>
								<input type="hidden" name="t" value="ogrenciler">
								<input type="text" class="form-control"  name="q2" value="{{get("q2")}}" placeholder="{{e2("Kullanıcı Adı")}}">
							</div>
						</div>
					</div>
				</form>
			
			</h3>
			
	 		<select name="" onchange="location.href='?t=ogrenciler&order='+$(this).val()" class="d-none" id="order">
				 <option value="id:DESC" selected>{{e2("Sondan Başa Sırala")}}</option>
				 <option value="id:ASC">{{e2("Baştan Sona Sırala")}}</option>
				 <option value="name:ASC">{{e2("A-Z Sırala")}}</option>
				 <option value="name:DESC">{{e2("Z-A Sırala")}}</option>
			 </select>

			 <script>
				 $(function(){
					 <?php if(getisset("order"))  { 
					   ?>
 						$("#order").val("{{get("order")}}"); 
					  <?php } ?>
				 });
			 </script>
		</div>
		
<a name="users"></a>
        <div class="block-content">
<?php 
$order_type = "ASC";
if(getisset("order")) {
	$order = explode(":",get("order"));
	if($order[1]=="ASC") {
		$order_type = "DESC";
	}
}
?>
			<div class="table-responsive">
			<table class="table table-bordered table-hover table-striped">
					<tr>
						<td><a href="?t=ogrenciler&order=id:{{$order_type}}">{{e2("Kimlik")}}</a></td>
					
						<td><a href="?t=ogrenciler&order=name:{{$order_type}}">{{e2("Adı")}}</a></td>
						<td><a href="?t=ogrenciler&order=surname:{{$order_type}}">{{e2("Soyadı")}}</a></td>
						
						<td><a href="?t=ogrenciler&order=alan:{{$order_type}}">{{e2("Alan")}}</a></td>
						<td><a href="?t=ogrenciler&order=sinif:{{$order_type}}">{{e2("Sınıf")}}</a></td>
						<td><a href="?t=ogrenciler&order=sube:{{$order_type}}">{{e2("Şube")}}</a></td>
						<!--
						<td>{{e2("Tür")}}</td>
						-->

						<td>{{e2("Onay")}}</td>
						<td>{{e2("İşlem")}}</td>
					</tr>
					@foreach($users AS $u)
						@php
							$permissions = explode(",",$u->permissions);
						@endphp
					<tr id="t{{$u->id}}">
						<td>{{$u->id}}
							<a class="dropdown-item" onclick="
							window.open('{{url("login-by-id?id=".$u->id)}}','_blank');
							" target="_blank">
								<i class="fa fa-lock"></i>
							</a>
						</td>
						<td><input type="text" name="name" value="{{$u->name}}" table="users" id="{{$u->id}}" class="name{{$u->id}} form-control edit" /></td>
						<td><input type="text" name="surname" value="{{$u->surname}}" table="users" id="{{$u->id}}" class="surname{{$u->id}} form-control edit" /></td>
						
						<td>
							<?php $alanlar = alanlar(); ?>
							<select name="alan" id="{{$u->id}}" class="form-control edit" table="users" >
								<option value="">Seçiniz</option>
								<?php foreach($alanlar AS $alan) { ?>
									<option value="{{$alan}}" <?php if($alan==$u->alan) echo "selected"; ?>>{{e2($alan)}}</option>
								<?php } ?>
							</select>
						</td>
                        <td><input type="text" name="sinif" value="{{$u->sinif}}" table="users" id="{{$u->id}}" class="sinif{{$u->id}} form-control edit" /></td>
                        <td><input type="text" name="sube" value="{{$u->sube}}" table="users" id="{{$u->id}}" class="sube{{$u->id}} form-control edit" /></td>
						<!--
						<td>
							<?php $tur = explode(",","Son Sınıf,Mezun,8. Sınıf") ?>
							<select name="tur" id="{{$u->id}}" class="form-control edit" table="users" >
								<option value="">Seçiniz</option>
								<?php foreach($tur AS $t) { ?>
									<option value="{{$t}}" <?php if($t==$u->tur) echo "selected"; ?>>{{e2($t)}}</option>
								<?php } ?>
							</select>
						</td>
								-->
<!--
						<td><a href="{{url('admin-ajax/password-update?id='.$u->id)}}" title="{{__('Kullanıcının şifresini sıfırla')}}" class="btn btn-default"><i class="fa fa-sync"></i> {{e2("Şifre Sıfırla")}}</button></td>
						<td>{{$u->recover}}</td>
								-->
						<td><input type="text" name="y" value="{{$u->y}}" table="users" id="{{$u->id}}" class="sinif{{$u->id}} form-control edit" /></td>
						<td>
						<div class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							{{e2("İşlemler")}}
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						  <a class="dropdown-item" href="?t=ogrenciler&user-detay={{$u->id}}"><i class="fa fa-list"></i> {{e2("Detaylar")}}</a>
							<a class="dropdown-item"onclick="
							window.open('{{url("login-by-id?id=".$u->id)}}','_blank');
							" target="_blank"><i class="fa fa-lock"></i> {{e2("Giriş Yap")}}</a>
							<a class="dropdown-item" href="{{url("admin?t=analizlerim&uid=".$u->id)}}" target="_blank"><i class="fa fa-chart-pie"></i> {{e2("Analiz")}}</a>
							<a class="dropdown-item" ajax="#t{{$u->id}}" teyit="{{$u->name}} {$u->surname} {{e2("Kullanıcısını silmek istediğinizden emin misiniz?")}}" href="{{url('admin-ajax/user-delete?id='.$u->id)}}">
							<i class="fa fa-times"></i>
							{{e2("Sil")}}</a>
						  </div>
						</div>
						</td>
					</tr>
					@endforeach
				</table>
				{{$users->fragment("users")->appends($_GET)->links() }}
			</div>
		</div>