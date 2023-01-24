<div class="row">
    <?php $u = u(); 
    $tarih = array();
    $brans = array();
    $sorgu = db("soru_atama")
    ->where("user",$u->id)
    ->get(); 
    $cozumlenen = 0;
    $cozumlenmeyen = 0;
    foreach($sorgu AS $s) {
        $label_tarih = date("d.m.Y",strtotime($s->created_at));
        if(!isset($tarih[$label_tarih])) $tarih[$label_tarih] = 0;
        
        $label = $s->brans;
        if(!isset($brans[$label])) $brans[$label] = 0;
        $brans[$label]++;
        if($s->cevap!="") {
            $cozumlenen++;
            $tarih[$label_tarih]++;
        } else {
            $cozumlenmeyen++;
        }
    }
    ?>
    {{col("col-md-4","Çözümlenen / Çözümlenmeyen Toplamları")}}
    <?php charts("Çözümlenen,Çözümlenmeyen","$cozumlenen,$cozumlenmeyen","Toplam","pie") ?>
    {{_col()}}
    {{col("col-md-4","Soru Çözümleme Geçmişiniz")}}
    <?php 
    
    $tarih_values = implode(",",$tarih);
    $tarih_labels =  implode_key(",",$tarih);
    ?>
    <?php charts($tarih_labels,$tarih_values,"Tarihindeki toplam çözülen","line") ?>
    {{_col()}}
    {{col("col-md-4","Toplam Size Atanan Soru Sayısı")}}
    <?php 
    
    $brans_values = implode(",",$brans);
    $brans_labels =  implode_key(",",$brans);
    ?>
    <?php charts($brans_labels,$brans_values,"Toplam çözülen","bar") ?>
    {{_col()}}
</div>