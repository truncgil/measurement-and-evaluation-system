<?php 
$ogrenci = db("users")
                ->where("level","Öğrenci")
                ->where("alias",$u->alias)
                ->where("id",get("id"))
                ->first();

if($ogrenci) {
    if($ogrenci->baba!="") {
        $sifre = rand(111111,999999);
        $uye_bilgi = [
            "name" => "Baba",
            "surname" => $ogrenci->surname,
            "level" => "Veli",
            "password" => Hash::make($sifre),
            "email" => $ogrenci->baba,
            "phone" => $ogrenci->baba
        ];
        if(isset($admin)) {
            
            $uye_bilgi['alias'] = $u->alias;
        }
        try {
            ekle2($uye_bilgi,"users");
            $text = "Sayın Veli, çocuğunuz {$ogrenci->name} {$ogrenci->surname} sizi babası olarak belirlemiştir. 
            Çocuğunuzun tüm aktivitelerini takip etmek için: 
            K. Adı: {$ogrenci->baba}
            Şifre: $sifre
            Giriş yapmak için: 
            https://app.dijimind.com/login
            ";
            @sms($text,$ogrenci->baba);
            echo "Baba profili oluşturuldu ve şifre sıfırlama gönderildi.";
        } catch (\Throwable $th) {
            //throw $th;
            echo "Öğrencinin zaten baba profili mevcuttur.";
        }
       
    } else {
        echo "Öğrencinin anne telefonu boş olduğundan profil oluşturulamadı";
    }

    if($ogrenci->anne!="") {
        $sifre = rand(111111,999999);
        $uye_bilgi = [
            "name" => "Baba",
            "surname" => $ogrenci->surname,
            "level" => "Veli",
            "password" => Hash::make($sifre),
            "email" => $ogrenci->anne,
            "phone" => $ogrenci->anne
        ];
        if(isset($admin)) {
            
            $uye_bilgi['alias'] = $u->alias;
        }
        try {
            ekle2($uye_bilgi,"users");
            $text = "Sayın Veli, çocuğunuz {$ogrenci->name} {$ogrenci->surname}  sizi annesi olarak belirlemiştir. 
            Çocuğunuzun tüm aktivitelerini takip etmek için: 
            K. Adı: {$ogrenci->anne}
            Şifre: $sifre
            Giriş yapmak için: 
            https://app.dijimind.com/login
            ";
            @sms($text,$ogrenci->baba);
            echo "Anne profili oluşturuldu ve şifre sıfırlama gönderildi.";
        } catch (\Throwable $th) {
            //throw $th;
            echo "Öğrencinin zaten anne profili mevcuttur.";
        }
       
    } else {
        echo "Öğrencinin anne telefonu boş olduğundan profil oluşturulamadı";
    }
} else {
    echo "Öğrenci bulunamadı";
}

?>