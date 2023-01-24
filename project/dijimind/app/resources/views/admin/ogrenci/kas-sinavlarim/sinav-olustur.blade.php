<div class="col-12">
<?php 
$u = u();
$kazanim_to_konu = kazanim_to_konu();
//print2($kazanim_to_konu);
if(getisset("sinav-olustur")) {
    $db = db("sinavlar_ogrenci")->where("uid",u()->id)
    ->where("id",get("sinav-olustur"))
    ->first();
  //  print2($db);
    $level = $db->level;
    $taksonomi_map = taksonomi_map();
    

    $j = j($db->json);
    if($db) {
        
        $konu = ($db->konu);
        $brans = ($db->brans);
        
        
        $sorular_dizi = [];
        $soru_adedi = $j['adet'];
        /*
[10:39, 16.02.2022] Servet Demir: Junior KAS için = %70 Junior + %30 Pratisyen
[10:40, 16.02.2022] Servet Demir: Pratisyen KAS için %20 Junior + %70 Pratisyen +%10 Master
[10:40, 16.02.2022] Servet Demir: Master KAS için %10 Junior +%20 Pratisyen + %70 Master
        */
        $bilgi = [
        //    "y" => [1,0],
            "brans" => $db->brans,
            "konu" => $konu, 
        ];
        if($level=="Junior") {
            $bilgi["taksonomik_duzey"] = $taksonomi_map['Junior'];
            $bilgi["sayi"] = 8;
            print2($bilgi);
            $sorular_dizi = sinav_olustur($bilgi,true, true);

            $bilgi["taksonomik_duzey"] = $taksonomi_map['Pratisyen'];
            $bilgi["sayi"] = 4;
            print2($bilgi);
            $sorular_dizi2 = sinav_olustur($bilgi,true, true);

            $sorular_dizi = array_merge($sorular_dizi,$sorular_dizi2);
        }
        if($level=="Pratisyen") {
            
            $bilgi["taksonomik_duzey"] = $taksonomi_map['Junior'];
            $bilgi["sayi"] = 3;
            $sorular_dizi =  sinav_olustur($bilgi,true, true);
            
            
            $bilgi["taksonomik_duzey"] = $taksonomi_map['Pratisyen'];
            $bilgi["sayi"] = 7;
            
            $sorular_dizi2 =  sinav_olustur($bilgi,true, true);

            $bilgi["taksonomik_duzey"] = $taksonomi_map['Master'];
            $bilgi["sayi"] = 2;
            
            
            $sorular_dizi3 =  sinav_olustur($bilgi,true, true);
            $sorular_dizi = array_merge($sorular_dizi,$sorular_dizi2,$sorular_dizi3);
        }
        if($level=="Master") {
            $bilgi["taksonomik_duzey"] = $taksonomi_map['Junior'];
            $bilgi["sayi"] = 2;
            
            $sorular_dizi =  sinav_olustur($bilgi,true, true);
            

            $bilgi["taksonomik_duzey"] = $taksonomi_map['Pratisyen'];
            $bilgi["sayi"] = 3;
            
            
            $sorular_dizi2 =  sinav_olustur($bilgi,true, true);

            $bilgi["taksonomik_duzey"] = $taksonomi_map['Master'];
            $bilgi["sayi"] = 7;
            
            
            $sorular_dizi3 =  sinav_olustur($bilgi,true, true);
            $sorular_dizi = array_merge($sorular_dizi,$sorular_dizi2,$sorular_dizi3);
        }
        
       //print2($sorular_dizi); exit();

        $soru_sayisi = count($sorular_dizi);
        //echo $soru_sayisi;
        if($soru_sayisi<$j['adet']) {
            if(isset($kazanim_to_konu[$brans][$konu])) {
                $konu = $kazanim_to_konu[$brans][$konu];
                $bilgi["taksonomik_duzey"] = $j['sorular'];
                $bilgi["sayi"] = $j['adet'];
                
                $sorular_dizi =  sinav_olustur($bilgi,true, true); 
            }
            
        }
       // exit();
        if($soru_sayisi>0) {
            $sure = $soru_sayisi * 1.5; // çıkan soru adedine göre her soruya 1.5 puan veriyoruz.
            $_SESSION['sorular'] = $sorular_dizi;
            $_SESSION['sure'] = $sure;
            $_SESSION['kid'] = get("sinav-olustur");
       //     print2($db);
            $title = "";
            unset($_SESSION['cache_sure']);
            $title = "40 Soruluk LGS Deneme Sınavı";
            $_SESSION['title'] = $db->title;
            $pdf = "";
            if(getisset("pdf")) {
                $pdf = "&pdf";
            }
            yonlendir("?t=sinava-basla".$pdf);
        } else {
            $soru_level = implode(",",$j['sorular']);
            @mailSend("info@dijimind.com","Yeterli soru bulunamadı","
            {$u->name} {$u->surname} {$db->title} için soru havuzunda {$db->level} seviyesinde {$soru_level} seviyelerinde soru bulunamadığından ötürü sınava giremedi. İvedilikle ilgilenmeniz gerekmektedir.");
            bilgi("Dijimind'a mesaj iletilmiştir. Bir sorun oluştu ve şu an üzerinde çalışıyoruz. Dilerseniz farklı KAS sınavlarınızı çözebilirsiniz. Yaşattığımız bu aksaklıktan dolayı özür dileriz. ","warning");
        }
        
    }
    

}
?>
</div>