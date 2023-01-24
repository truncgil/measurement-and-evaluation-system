<?php
$users = usersArray();
 $soru_bankasi = db("soru_bankasi");
 if(u()->level!="Admin") { // Eğer admin haricinde bir kişi bilgi almak istiyorsa kendine ait olan bilgileri sunsun
	 $soru_bankasi = $soru_bankasi->where("uid",u()->id);
 }
 $soru_bankasi = $soru_bankasi->get(); 
$gunluk_soru = array();
$brans_soru = array();
$brans_kisiler = array();
foreach($soru_bankasi AS $sb) {
	$label = date("d.m.Y",strtotime($sb->created_at));
	$brans_label = $sb->brans;
	if(!isset($gunluk_soru[$label])) $gunluk_soru[$label] = 0;
	if(!isset($brans_soru[$brans_label])) $brans_soru[$brans_label] = 0;
	if(!isset($brans_kisiler[$sb->uid][$label])) $brans_kisiler[$sb->uid][$label] = 0;
	if($sb->paragraf_grup=="") {
		$gunluk_soru[$label]++;
		$brans_soru[$brans_label]++;
		$brans_kisiler[$sb->uid][$label]++;
	}
}
$gunluk_soru_values = implode(",",$gunluk_soru);
$gunluk_soru_labels =  implode_key(",",$gunluk_soru);

//print2($brans_soru);
$brans_soru_values = implode(",",$brans_soru);
$brans_soru_labels =  implode_key(",",$brans_soru);


$soru_atama = db("soru_atama")->get();
$atama_sayisi = array();
foreach($soru_atama AS $sa) {
	$label = date("d.m.Y",strtotime($sa->created_at));
	if(!isset($atama_sayisi[$label])) $atama_sayisi[$label] = 0;
	$atama_sayisi[$label]++;
}
$atama_sayisi_values = implode(",",$atama_sayisi);
$atama_sayisi_labels =  implode_key(",",$atama_sayisi);

?>
		<h2 class="content-heading">{{e2("Soru Bankası Raporları")}}</h2>
		<div class="row">
			<div class="col-md-4">
				<div class="block block-themed">
					<div class="block-header bg-{{$colors[30]}}">{{e2("Branşlara Göre Soru Toplamı")}}</div>
					<div class="block-content">
						<?php charts($brans_soru_labels,$brans_soru_values,"Branş Soru Sayıları","bar") ?>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="block block-themed">
					<div class="block-header bg-{{$colors[25]}}">{{e2("Günlük Soru Ekleme Geçmişi")}}</div>
					<div class="block-content">
						<?php charts($gunluk_soru_labels,$gunluk_soru_values,"Günlük Soru Girişi","line") ?>
					</div>
				</div>
			</div>
			
			<?php chartsByData("soru_atama","date","Soru Atama Sayısı","line",13,"col-md-4")  ?>
			<div class="col-12">
				<h2 class="content-heading">{{e2("Kişi Bazlı Günlük Soru Bankası Raporları")}}</h2>
			</div>
			<?php 
			$k=20;
			foreach($brans_kisiler AS $alan => $deger) { 
				$k++;
				if(isset($users[$alan])) 

				 { 
				 
 				$user = $users[$alan];
 				$key = implode_key(",",$deger);
 				$values = implode(",",$deger);
 				?>
 				
 			<div class="col-md-4">
 
 				<div class="block block-themed">
 					<div class="block-header bg-{{$colors[$k]}}">{{$user->name}} {{$user->surname}} {{e2("Günlük Soru Ekleme Geçmişi")}}</div>
 					<div class="block-content">
 						<?php charts($key,$values,"Günlük Soru Girişi","line") ?>
 					</div>
 				</div>
 			</div> 
				 <?php } ?>
			<?php } ?>
			<?php chartsByData("soru_bankasi","group","Test / Sınav Gruplarına Göre Sorular","bar",13,"col-md-12")  ?>
		</div>