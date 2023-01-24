<div class="row">
    {{col("col-md-12 text-center","",2)}}
        <img src="{{url("assets/success.gif")}}" class="img-fluid" alt="">
        <h1>{{oturum("paket_adi")}} Paketiniz Tanımlanmıştır</h1>
        <p>Öncelikle {{oturum("paket_adi")}} paketinizi satın aldığınız için teşekkürler.
        <br>
        Bu pakete ait sınavlarınız ile ilgili size bir takvim hazırladık. <br>
        Atanan sınavlar ve tarihleri aşağıdaki gibidir:</p>
        <?php echo oturum("sinav_icerigi"); ?>
    {{_col()}}
</div>