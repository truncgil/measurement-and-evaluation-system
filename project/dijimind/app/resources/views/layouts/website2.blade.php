<?php 
$ver = "1.0";
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
// OR
use Artesaos\SEOTools\Facades\SEOTools;
if(getisset("ajax")) {
	?>
	@include("admin-ajax.{$_GET['ajax']}")
	<?php
	exit();
} ?>
<?php if(getisset("ajax2")) { //blade ajax system
	?>
	@include("{$_GET['ajax2']}")
	<?php
	exit();
} ?>
@php
	use App\Contents;
	oturumAc();
	if(isset($_GET['l'])) {
		app()->setLocale($_GET['l']);
		oturum("locale",$_GET['l']);
	}

@endphp
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta name="facebook-domain-verification" content="yc7gjlgfb6ssbvgb8ouv0f3yqidpkk" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
       @yield("title")
       </title>
    <!-- favicons Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="https://app.dijimind.com/assets2/images/favicons/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="https://app.dijimind.com/assets2/images/favicons/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="https://app.dijimind.com/assets2/images/favicons/favicon-16x16.png" />
    <link rel="manifest" href="https://app.dijimind.com/assets2/images/favicons/site.webmanifest" />
    <meta name="description" content="DİJİMİND AKADEMİ  ||  rehberlikte dijital akıl " />
	<meta name="keywords" content="dijimind akademi, tyt konuları, ayt konuları, tyt 2022, tyt çalışma programları, sınav koçluğu, lgs konuları, lgs 2022, lgs puan hesaplama, tyt puan hesaplama, ayt puan hesaplama, yks konuları dağılımı, tyt ders ve konuları, tyt kaç günde biter, ders çalışma programları, lgs ders çalışma programı, tyt çalışma programı 2022, ayt çalışma programı 2022, 8sınıf çalışma programı, dijital rehberlik, sanal koçluk, dijital koçluk, online ders programı hazırlama" />
	<meta name="author" content="Medyamim">
	<meta name="robots" content="index, follow" />
	<meta name="revisit-after" content="3 days" />

    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/swiper/swiper.min.css" />

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@300;400;700&display=swap" rel="stylesheet">

    <script src="https://app.dijimind.com/assets2/vendors/jquery/jquery-3.5.1.min.js"></script>

    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/animate/animate.min.css" />
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/jarallax/jarallax.css" />
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/jquery-magnific-popup/jquery.magnific-popup.css" />
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/nouislider/nouislider.min.css" />
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/nouislider/nouislider.pips.css" />
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/odometer/odometer.min.css" />
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/moniz-icons/style.css?{{$ver}}">
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/tiny-slider/tiny-slider.min.css" />
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/reey-font/stylesheet.css" />
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/owl-carousel/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/owl-carousel/owl.theme.default.min.css" />
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/twentytwenty/twentytwenty.css" />
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/bxslider/jquery.bxslider.css" />
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/bootstrap-select/css/bootstrap-select.min.css" />
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/vegas/vegas.min.css" />
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/jquery-ui/jquery-ui.css" />
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/vendors/timepicker/timePicker.css" />
    <!-- template styles -->
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/css/moniz.css?{{$ver}}" />
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/css/moniz-responsive.css?{{$ver}}" />

    <!-- RTL Styles -->
    <link rel="stylesheet" href="https://app.dijimind.com/assets2/css/moniz-rtl.css?{{$ver}}">

    <!-- color css -->
    <link rel="stylesheet" id="jssDefault" href="https://app.dijimind.com/assets2/css/colors/color-default.css">
    <link rel="stylesheet" id="jssMode" href="https://app.dijimind.com/assets2/css/modes/moniz-normal.css?{{$ver}}">
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-KRKN85F');</script>
    <!-- End Google Tag Manager -->
    <script src="//code.jivosite.com/widget/jItk5NGHcQ" async></script>
</head>

<body>



    <div class="preloader">
        <img class="" width="60" src="https://app.dijimind.com/assets2/images/loader.png" alt="" />
    </div>

    <!-- /.preloader -->
    <div class="page-wrapper">
    <header class="main-header clearfix">
            <nav class="main-menu main-menu-two clearfix">
                <div class="main-menu-two-wrapper clearfix">
                    <div class="main-menu-two-wrapper__left">
                        <div class="main-menu-two-wrapper__logo">
                            <a href="./anasayfa"><img src="assets2/images/logo.png" alt=""></a>
                        </div>
                        <div class="main-menu-two-wrapper__main-menu">
                            
                            <ul class="main-menu__list">
                                <li><a href="./anasayfa">ANASAYFA</a></li>
								
								<li><a href="hakkimizda">HAKKIMIZDA</a></li>

                                <li class="dropdown">
                                    <a href="dijimind">DİJİMİND </a>
                                    <ul>
                                        <li><a href="neden_dijimind">Neden Dijimind</a></li>
                                        <li><a href="dijimind_konsept">Dijimind Konsept</a></li>
                                        <li><a href="dijimind_analizleri">Dijimind Analizleri</a></li>
                                        <li><a href="dijimind_sistemi">Dijimind Sistemi</a></li>
                                    </ul>
                                </li>
								
								<li><a href="paketler">PAKETLER</a></li>
                                
                                <li><a href="blog">BLOG</a></li>
                                <li><a href="video">VİDEO</a></li>
                                
                                <li><a href="iletisim">İLETİŞİM</a></li>
								
								
                            </ul>
							<a href="#" class="mobile-nav__toggler">
                                <span></span>
                            </a>
                        </div>
                    </div>
					
					<div class="main-menu-wrapper__right">
                        <div class="main-menu-wrapper__right-contact-box">
                            <div class="main-menu-wrapper__social">
                                <?php if(Auth::check()) {
                                    $u = u();
 ?>
 <button onclick="location.href='{{url("admin")}}'" style="margin: 0 10px;
    padding: 5px 20px;" class="thm-btn"><span>{{$u->name}} {{$u->surname}}</span></button>
 <?php 
                                } else {
 ?>
 <a href="https://app.dijimind.com/login" target="_blank"><i><img src="assets2/images/giris.png" width="50" height="50" alt="Üye Girişi"></i></a>
 <?php 
                                } ?>
                            
                            <a href="https://www.facebook.com/dijimind" target="_blank"  class="clr-fb"><i class="fab fa-facebook"></i></a>
                            <a href="https://www.youtube.com/dijimind" target="_blank"  class="clr-dri"><i class="fab fa-youtube"></i></a>
                            <a href="https://www.instagram.com/dijimind/" target="_blank" class="clr-ins"><i class="fab fa-instagram"></i></a>
							
                        </div>
                        </div>
                    </div>
                    
                </div>
            </nav>
        </header>

        <div class="stricky-header stricked-menu main-menu">
            <div class="sticky-header__content"></div><!-- /.sticky-header__content -->
        </div><!-- /.stricky-header -->

        
          @yield("content")     
    </section>
     <!-- FOOTER -->
   <!--Site Footer One Start-->
   <footer class="site-footer">
            <div class="site-footer__top">
                <div class="site-footer-top-bg" style="background-image: url(assets2/images/backgrounds/birlikte.jpg)"></div>
                <div class="container" style="margin-top: -40px; margin-bottom: -40px">
                    <div class="row">
						
						<div class="col-xl-3  wow fadeInUp animated" data-wow-delay="100ms" style="visibility: visible; animation-delay: 100ms; animation-name: fadeInUp;">
                            <img src="assets2/images/maskot.png" width="250" alt="Dijimind Akademi">
                        </div>
						
                        <div class="col-xl-3  wow fadeInUp animated" data-wow-delay="100ms" style="visibility: visible; animation-delay: 100ms; animation-name: fadeInUp;">
                            <div class="footer-widget__column footer-widget__about">
                                <div>
                                    <a href="https://app.dijimind.com/anasayfa"><img src="assets2/images/logo.png" width="250" alt=""></a>
                                </div><br>
                                <p class="footer-widget__about-text">Dijimind olarak sınavlara hazırlığın ana bileşenin öğrencilerin doğru ve etkili bir ölçme değerlendirme aracına sahip olması gerektiği inancındayız. </p>
								
                                
                            </div>
                        </div>
						
						
						
						<div class="col-xl-3  wow fadeInUp animated" data-wow-delay="100ms" style="visibility: visible; animation-delay: 100ms; animation-name: fadeInUp;">
                            <div class="footer-widget__column footer-widget__about">
                                <h3 class="footer-widget__title">Kaynaklar</h3>
                                <a style="color: white" href="https://app.dijimind.com/mesafeli-satis-sozlesmesi"><i class="fa fa-arrow-alt-circle-right"></i> Mesafeli Satış Sözleşmesi</a><br>
								<a style="color: white" href="https://app.dijimind.com/tuketici-mevzuati-geregince-on-bilgilendirme-metni"><i class="fa fa-arrow-alt-circle-right"></i> Son Kullanıcı Lisans Söz.</a><br>
								<a style="color: white" href="https://app.dijimind.com/gizlilik-sozlesmesi"><i class="fa fa-arrow-alt-circle-right"></i> Gizlilik Sözleşmesi</a><br>
								<a style="color: white" href="https://app.dijimind.com/kisisel-verilere-iliskin-veri-sahibi-acik-riza-beyan-formu"><i class="fa fa-arrow-alt-circle-right"></i> Kişisel Verilerin Korunması</a><br><br>
                                <br><br>
                            </div>
                        </div>
						
						
                        
                        <div class="col-xl-3 wow fadeInUp animated" data-wow-delay="400ms" style="visibility: visible; animation-delay: 400ms; animation-name: fadeInUp;">
                            <div class="footer-widget__column footer-widget__about">
                                <h3 class="footer-widget__title">İletişim Bilgileri</h3>
                                <p class="footer-widget__about-text">DİJİMİND AKADEMİ<br>
													Beştepe Mh. Dumlupınar Blv.<br>
													No:6/1-A Yenimahalle / ANKARA</p>
                                <div class="footer-widget__contact-info">
									
                                    <p>
                                        <a href="tel:+908503056463" class="footer-widget__contact-phone">0850 305 6463</a>
										<a href="tel:+903129116463" class="footer-widget__contact-phone">0312 911 6463</a>
                                        <a href="tel:+905062736463" class="footer-widget__contact-phone">0506 273 6463</a>
                                       <a href="mailto:info@dijimind.com" class="footer-widget__about-text">info@dijimind.com</a>
                                    </p>
									<div class="footer-widget__about-social-list" style="margin-top: -20px">
                                    
                                    <a href="https://www.facebook.com/dijimind" target="_blank" class="clr-fb"><i class="fab fa-facebook"></i></a>
                                    <a href="https://www.youtube.com/dijimind" target="_blank" class="clr-dri"><i class="fab fa-youtube"></i></a>
                                    <a href="https://www.instagram.com/dijimind/" target="_blank" class="clr-ins"><i class="fab fa-instagram"></i></a>
									<a href="https://api.whatsapp.com/send?phone=905062736463" class="clr-ins"><i class="fab fa-whatsapp"></i></a>
                                </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="site-footer-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="site-footer-bottom__inner">
								
								<div align="center" id="ETBIS"><div id="3501882552673855"><a href="https://etbis.eticaret.gov.tr/sitedogrulama/3501882552673855" target="_blank"><img style="width:60px; height:72px; border: solid 5px #fff;     margin: 0 auto;     display: block;" src="data:image/jpeg;base64, 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"></a></div></div>
								
                                <div align="center"><img src="assets2/images/kartkoyu.png"></div>
                                <p class="site-footer-bottom__copy-right">© Copyright 2022 DİJİMİND AKADEMİ Design by <a>Medyamim</a></p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!--Site Footer One End-->


    </div><!-- /.page-wrapper -->


    <div class="mobile-nav__wrapper">
        <div class="mobile-nav__overlay mobile-nav__toggler"></div>
        <!-- /.mobile-nav__overlay -->
        <div class="mobile-nav__content">
            <span class="mobile-nav__close mobile-nav__toggler"></span>

            <div class="logo-box">
                <a href="anasayfa" aria-label="logo image"><img src="https://app.dijimind.com/assets2/images/logo.png" width="215"
                        alt="" /></a>
            </div>
            <!-- /.logo-box -->
            <div class="mobile-nav__container"></div>
            <!-- /.mobile-nav__container -->

            <ul class="mobile-nav__contact list-unstyled">
				<li style="align-items: center">
                <?php if(Auth::check()) {
                                    $u = u();
 ?>
 <button onclick="location.href='{{url("admin")}}'" style="margin: 0 10px;
    padding: 5px 20px;" class="thm-btn"><span>{{$u->name}} {{$u->surname}}</span></button>
 <?php 
                                } else {
 ?>
 <a href="https://app.dijimind.com/login" target="_blank"><i><img src="assets2/images/giris.png" width="50" height="50" alt="Üye Girişi"></i></a>
 <?php 
                                } ?>
                </li>
                <li>
                    <i class="fa fa-envelope"></i>
                    <a href="mailto:info@dijimind.com">info@dijimind.com</a>
                </li>
                <li>
                    <i class="fa fa-phone-alt"></i>
                    <a href="tel:+908503056463">0850 305 6463</a>
					
                </li>
				
				
            </ul><!-- /.mobile-nav__contact -->
			<hr>
            <div class="mobile-nav__top">
                <div class="mobile-nav__social"> 
                    <a href="https://www.facebook.com/dijimind" target="_blank" style="color: white; font-size: 2rem;" class="fab fa-facebook-square"></a>
                    <a href="https://www.youtube.com/dijimind" target="_blank" style="color: white; font-size: 2rem;" class="fab fa-youtube"></a>
                    <a href="https://www.instagram.com/dijimind/" target="_blank" style="color: white; font-size: 2rem;" class="fab fa-instagram"></a>
					<a href="https://api.whatsapp.com/send?phone=905062736463" target="_blank" style="color: white; font-size: 2rem;" class="fab fa-whatsapp"></a>
                </div><!-- /.mobile-nav__social -->
            </div><!-- /.mobile-nav__top -->



        </div>
        <!-- /.mobile-nav__content -->
    </div>
    <!-- /.mobile-nav__wrapper -->

    
    <!-- /.search-popup -->

    <a href="#" data-target="html" class="scroll-to-target scroll-to-top"><i class="fa fa-angle-up"></i></a>

    <script src="https://app.dijimind.com/assets2/vendors/swiper/swiper.min.js"></script>

    <script src="https://app.dijimind.com/assets2/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/jarallax/jarallax.min.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/jquery-ajaxchimp/jquery.ajaxchimp.min.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/jquery-appear/jquery.appear.min.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/jquery-circle-progress/jquery.circle-progress.min.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/jquery-magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/jquery-validate/jquery.validate.min.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/nouislider/nouislider.min.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/odometer/odometer.min.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/tiny-slider/tiny-slider.min.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/wnumb/wNumb.min.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/wow/wow.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/isotope/isotope.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/countdown/countdown.min.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/owl-carousel/owl.carousel.min.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/twentytwenty/twentytwenty.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/twentytwenty/jquery.event.move.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/bxslider/jquery.bxslider.min.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/bootstrap-select/js/bootstrap-select.min.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/vegas/vegas.min.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/jquery-ui/jquery-ui.js"></script>
    <script src="https://app.dijimind.com/assets2/vendors/timepicker/timePicker.js"></script>


    <script src="http://maps.google.com/maps/api/js?key=AIzaSyATY4Rxc8jNvDpsK8ZetC7JyN4PFVYGCGM"></script>


<script>
    $(function(){
        $(".window").on("click",function(){
			var url = $(this).attr("href");
			var title = $(this).attr("title");
			var myWindow = window.open(url, title, "width=600,height=800");
			return false;
		});
    });
</script>
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(87516813, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/87516813" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
    <!-- template js -->
    <script src="https://app.dijimind.com/assets2/js/moniz.js"></script>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KRKN85F"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
       
</body>

</html>
