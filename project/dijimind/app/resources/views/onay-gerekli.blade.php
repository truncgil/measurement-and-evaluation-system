<?php use App\Contents; 

$title = "Üyelik Onayı Gerekli";
$description = "Bu bölüm için üyeliğinizi onaylamanız gerekmektedir";
$keywords = "";

?>

@extends('admin.master')

@section("title",$title)
@section("description",$description)
@section("keywords",$keywords)


@section('content')
<style>
    .bg-image {
        display:none;
    }
</style>
<?php 

$u = u(); ?>
<div class="bg-white">
    <div class="hero-inner">
        <div class="content content-full">
            <div class="py-30 text-center">
                <img src="https://dijimind.com/landing/assets/maskot.png" width="300" alt="">
                <div class="h1 text-primary">{{$title}}</div>
                <p>{{$description}}</p>
                <?php if(getisset("onay-gonder")) {
                    
                  
                  
                    $kod = rand(1111,9999);
                    db("users")
                    ->where("id",$u->id)
                    ->update([
                        "kod" => $kod
                    ]);
                    $text = "Sayın {$u->name} {$u->surname}, Üyeliğinizi onaylamak için $kod kodunu kullanabilirsiniz.";
                    @mailsend($u->email,"Üyeliğinizi Onaylayın",$text);
                    @sms($text,$u->phone);
                    bilgi("Onay kodunuz {$u->email} mail adresinize ve {$u->phone} telefon numaranıza gönderilmiştir","success");
                } ?>
                <?php 
                $uye_onay = false;
                if(getisset("onayla")) {
                    
                    $q2 = db("users")->where("id",$u->id)
                    ->where("kod",post("kod2"));
                    $varmi = $q2->first();
                    if($varmi) {
                        $q2->update([
                            "y" => 1
                        ]);
                        bilgi("Üyeliğiniz başarılı bir şekilde onaylanmıştır.");
                        $uye_onay = true;
                    } else {
                        bilgi("Girmiş olduğunuz kod geçersizdir. Lütfen tekrar deneyiniz.","danger");
                    }

                } ?>
                <?php if(!$uye_onay)  { 
                  ?>
                <a href="?onay-gonder" class="">
                     <i class="fa fa-envelope mr-10"></i> {{e2("Onay kodunu e-mail ve sms olarak gönder.")}}
                 </a>
                 <br>
                   
                     <form action="?onayla" method="post">
                         @csrf
 
                             <div class="" style="width:300px;margin:0 auto;">
                                 <input type="number" name="kod2" id="" placeholder="{{e2("Onay Kodunuzu buraya giriniz")}}" class="form-control">
                                 <button class="btn btn-hero btn-rounded btn-primary">{{e2("Onayla")}}</button>
                             </div>
                         
     
                     </form>  
                 <?php } ?>
                
                
                
            </div>
        </div>
    </div>
</div>
@endsection

