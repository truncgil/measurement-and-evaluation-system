
<?php $title = "İletişim" ?>
@extends('layouts.website2')

@section("title","İletişim")
@section("description","")
@section("keywords","")


 @section('content')
  @include("inc.page-header")
  <!--Contact Page Start-->
  <script src="https://www.google.com/recaptcha/api.js"></script>
  <script>
   function onSubmit(token) {
     document.getElementById("contact-form").submit();
   }
 </script>
  <section class="contact-page" style="margin-top: -40px; margin-bottom: -40px">
            <div class="container">
                <div class="section-title text-center">
                    <span class="section-title__tagline">Bize Yazın</span>
                    <h2 class="section-title__title">Mesaj Formu</h2>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="contact-page__form">
                          <?php if(getisset("mesaj-gonder")) {

                            $post = [
                                'secret' => '6LeDVSskAAAAAN70e-Bwnxu2HwegyAq5A82BHKih',
                                'response' => $_POST['g-recaptcha-response'],
                            ];

                            $ch = curl_init();

                            curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                            $server_output = j(curl_exec($ch));

                            curl_close ($ch);


                            if($server_output['success']) {
                                if(postesit("email","")) {
                                    bilgi("Lütfen e-posta adresinizi giriniz","danger");
                                } else {
                                    @mailsend("info@dijimindakademi.com","İletişim Formuna Mail: ".post("Subject"),"
                                    Mesaj İçeriği: {$_POST['message']} <br>
                                    Adı Soyadı: {$_POST['name']} 
                                    E-Posta: {$_POST['email']} 
                                    Telefon: {$_POST['phone']}
                                    ");
                                    bilgi("Mesajınız alınmıştır. En kısa zamanda tarafınıza iletişime geçeceğiz. Teşekkürler");
                                }
                                
                            } else {
                                bilgi("Mesajınız gönderilemedi. ReCAPTCHA doğrulaması başarısız. ","danger");
                            }

                            
                          } ?>
                            <form action="?mesaj-gonder" id="contact-form" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="comment-form__input-box">
                                            <input type="text" placeholder="İsminiz" required name="name">
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="comment-form__input-box">
                                            <input type="email" placeholder="Email Adresiniz" required name="email">
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="comment-form__input-box">
                                            <input type="text" placeholder="Telefon Numaranız" required name="phone">
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="comment-form__input-box">
                                            <input type="text" placeholder="Konu" required name="Subject">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="comment-form__input-box">
                                            <textarea name="message" placeholder="Mesajınız" required></textarea>
                                        </div>
                                       
                                        <button
                                        data-sitekey="6LeDVSskAAAAAA34jwV1oMiBNQlAZPJ3cxs0VADL" 
                                        data-callback='onSubmit' 
                                        data-action='submit'
                                        type="submit" onclick="$(this).children('span').html('Lütfen Bekleyiniz...');"  class="g-recaptcha thm-btn faqs-contact__btn"><span>Mesajı Gönder</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Contact Page End-->
				
				<hr style="border-bottom:2px  solid #e94e1b; width:99%;">
				
				<section class="contact-page" style="margin-top: -70px; margin-bottom: -90px">
					<div class="container">
						 <div class="section-title text-center">
                    <span class="section-title__tagline">Telefon</span>
					<p>
                    <a href="tel://+908503056463"><font size="+3" color="#e94e1b"><strong>0850 305 6463 (MIND)</strong></font></a><br>
                    <a href="tel://+905062736463"><font size="+3" color="#e94e1b"><strong>0506 273 6463 (MIND)</strong></font></a><br>
					<a href="tel://+903129116463"><font size="+3" color="#e94e1b"><strong>0312 911 6463 (MIND)</strong></font></a></p>
                    
					<span class="section-title__tagline">Email</span><br>
                    <p class="reasons-one__text-1"><a href="mailto:info@dijimind.com">info@dijimind.com</a><br>
					<a href="mailto:destek@dijimind.com">destek@dijimind.com</a><br>
					<span class="section-title__tagline">Adres</span><br>
                    Beştepe Mh. Dumlupınar Blv. No:6/1-A Yenimahalle / ANKARA</p>
                </div>
					
					</div>
				</section>
				
 @endsection

