<?php 

$ogrenci = db("users")
                ->where("level","Öğrenci")
                ->where("alias",$u->alias)
                ->where("id",get("id"))
                ->first();

if($ogrenci->anne) {
    $sifre = rand(111111,999999);
    $sonuc = db("users")
        ->where("level","Veli")
        ->where("email",$ogrenci->anne)
        ->where("phone",$ogrenci->anne)
        ->update([
            'password' => Hash::make($sifre)
        ]);
    if($sonuc!=0) {
        $text = "Sayın Veli, çocuğunuz {$ogrenci->name} {$ogrenci->surname} 
        Şifreniz sıfırlanmıştır. 
        K. Adı: {$ogrenci->anne}
        Şifre: $sifre
        Giriş yapmak için: 
        https://app.dijimind.com/login
        ";
        @sms($text,$ogrenci->anne);
        echo "Annenin şifresi güncellendi ve SMS olarak gönderildi! \n";
    } else {
        echo "Anne profili bulunamadığından şifre güncellemesi yapılamadı. \n";
    }
    
    
}
if($ogrenci->baba) {
    $sifre = rand(111111,999999);
    $sonuc = db("users")
        ->where("level","Veli")
        ->where("email",$ogrenci->baba)
        ->where("phone",$ogrenci->baba)
        ->update([
            'password' => Hash::make($sifre)
        ]);
    if($sonuc!=0) {
        $text = "Sayın Veli, çocuğunuz {$ogrenci->name} {$ogrenci->surname} 
        Şifreniz sıfırlanmıştır. 
        K. Adı: {$ogrenci->baba}
        Şifre: $sifre
        Giriş yapmak için: 
        https://app.dijimind.com/login
        ";
        @sms($text,$ogrenci->baba);
        echo "Babanın şifresi güncellendi ve SMS olarak gönderildi! \n";
    } else {
        echo "Baba profili bulunamadığından şifre güncellemesi yapılamadı. \n";
    }
    
    
}
?>