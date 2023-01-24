<?php $analiz = db("soru_atama")->where("uid",u()->id)->get();
	$dizi = array();
	$dizi2 = array();
	foreach($analiz AS $a) {

		//her branş için ayrı ayrı ele alalım
		if(!isset($dizi[$a->brans]['atanan'])) $dizi[$a->brans]['atanan'] = 0;
		if(!isset($dizi[$a->brans]['cozumlenen'])) $dizi[$a->brans]['cozumlenen'] = 0;
		if(!isset($dizi[$a->brans]['toplam'])) $dizi[$a->brans]['toplam'] = 0;
		if($a->cevap!="") {
			$dizi[$a->brans]['cozumlenen']++;
		} else {
			$dizi[$a->brans]['atanan']++;
		}
		$dizi[$a->brans]['toplam']++;

		//her uzman için ayrı ayrı ele alalım

		if(!isset($dizi2[$a->user]['atanan'])) $dizi2[$a->user]['atanan'] = 0;
		if(!isset($dizi2[$a->user]['cozumlenen'])) $dizi2[$a->user]['cozumlenen'] = 0;
		if(!isset($dizi2[$a->user]['toplam'])) $dizi2[$a->user]['toplam'] = 0;
		if($a->cevap!="") {
			$dizi2[$a->user]['cozumlenen']++;
		} else {
			$dizi2[$a->user]['atanan']++;
		}
		$dizi2[$a->user]['toplam']++;
		 
	}
	$branslar = $dizi;
	$uzmanlar = $dizi2;
//	print2($analiz);
	?>

			<h2 class="content-heading">{{e2("Branşlara Göre Atanmış Sorular")}}</h2>
			<div class="row">
			<?php 
			$k=-1;
			foreach($branslar AS $alan => $deger)  { 
				$k++;
				if($k>count($colors)-1) $k=0;
					if($alan!="") {
					$toplam = $deger['toplam'];
					$atanan = $deger['atanan'];
					$cozumlenen = $deger['cozumlenen'];
					$atanan_percent = round($atanan*100/$toplam,0);
					$cozumlenen_percent = round($cozumlenen*100/$toplam,0);	
					?>
					<div class="col-md-3">
						<div class="block block-themed">
							<div class="block-header bg-{{($colors[$k])}}">
								<div class="block-title">
									<span>{{$alan}}</span>
								</div>
								<div class="block-options">
									<div class="badge badge-success">{{$toplam}}</div>
								</div>
							</div>
							<div class="block-content">
								<?php charts("Çözümlenen,Atanan","$cozumlenen,$atanan"); ?>
								<div class="progress">
									<div class="progress-bar bg-success" style="width:{{$cozumlenen_percent}}%">
										{{e2("Çözümlenen")}} {{$cozumlenen}}
									</div>
									<div class="progress-bar bg-warning" style="width:{{$atanan_percent}}%">
										{{e2("Atanan")}} {{$atanan}}
									</div>
								
								</div>
							</div>
						</div>
						
					</div>
					<?php } ?>
				<?php } ?>
			</div>
			<h2 class="content-heading">{{e2("Uzmanlara Göre Atanmış Sorular")}}</h2>
			<div class="row">
			<?php 
		
			foreach($uzmanlar AS $alan => $deger)  { 
				$k++;
				if($k>count($colors)-1) $k=0;
					$uzman_adi = $users[$alan]->name . " " . $users[$alan]->surname ;
					$toplam = $deger['toplam'];
					$atanan = $deger['atanan'];
					$cozumlenen = $deger['cozumlenen'];
					$atanan_percent = round($atanan*100/$toplam,0);
					$cozumlenen_percent = round($cozumlenen*100/$toplam,0);	
					?>
					<div class="col-md-3">
						<div class="block block-themed">
							<div class="block-header bg-{{($colors[$k])}}">
								<div class="block-title">
									<span>{{$uzman_adi}}</span>
								</div>
								<div class="block-options">
									<div class="badge badge-success">{{$toplam}}</div>
								</div>
							</div>
							<div class="block-content">
								<?php charts("Çözümlenen,Atanan","$cozumlenen,$atanan"); ?>
								<div class="progress">
									<div class="progress-bar bg-success" style="width:{{$cozumlenen_percent}}%">
										{{e2("Çözümlenen")}} {{$cozumlenen}}
									</div>
									<div class="progress-bar bg-warning" style="width:{{$atanan_percent}}%">
										{{e2("Atanan")}} {{$atanan}}
									</div>
								
								</div>
							</div>
						</div>
						
						
					</div>
				<?php } ?>
				</div>