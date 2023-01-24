<link rel="stylesheet" href="{{url("assets/click-tap-image/dist/css/image-zoom.css")}}" />
<script src="{{url("assets/click-tap-image/dist/js/image-zoom.min.js")}}"></script>
<script>
// Set the date we're counting down to 100 80 distance * 100 / 80
var countDownDate = new Date();
var newDateObj = new Date();
newDateObj.setTime(countDownDate.getTime() + ({{$sure}} * 60 * 1000));
console.log(newDateObj);
countDownDate = newDateObj;
// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();
  var minutes = new Date().getMinutes();
//console.log(countDownDate);
  // Find the distance between now and the count down date
  var distance = countDownDate - now;
   // console.log(yuzde);
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    var kalan_dakika = distance / (1000 * 60);
    var kalan_saniye = distance / (1000);
  var yuzde = Math.floor(kalan_dakika * 100 / {{$sure}});
  console.log(yuzde);
    $(".sayac").css("width",yuzde+"%");
    $(".sayac").attr("aria-valuenow",yuzde);
  // Display the result in the element with id="demo"
  $(".kalan-sure").html(hours + "s "
  + minutes + "d " + seconds + "s ");
    $.get("?t=sinava-basla&cache_sure="+Math.round(kalan_saniye));
  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    $.get("?t=sinavi-bitir");
    $("#timeup").modal();
    $("#sinav-form").remove();
  //  document.getElementById("demo").innerHTML = "EXPIRED";
  }
}, 1000);
</script>
<script>
    $(function(){
      
        var owl = $(".sorular").owlCarousel({
            items: 1,
            lazyLoad: true,
            nav: true,
            touchDrag: false
        });
        owl.on('changed.owl.carousel', function(event) {
           // console.log("changed.owl.carousel");
           var i = event.page.index;
          // $('.sorular .owl-item:eq('+i+') img').imageZoom();

          //  console.log(event.page.index);
        });
        
        $('.soru-container').on("click",function(){
            $(this).toggleClass("zoom-img");
           
            
        });
        
        
        <?php if(oturumisset("index_cache")) {
             ?>
             //owl.goTo("{{oturum("index_cache")}}");
             $("#optik-form tr").removeClass("table-success");
             $("#optik-form tr:eq({{oturum("index_cache")}})").addClass("table-success");
             owl.trigger('to.owl.carousel', [{{oturum("index_cache")}}, 0, true]);
             <?php 
        } ?>
        <?php if(!getisset("pdf"))  { 
          ?>
         $(".goto").on("click",function(){
             $(window).scrollTop(0);
             owl.trigger('to.owl.carousel', [$(this).attr("data-index")-1, 0, true]);
             window.setTimeout(function(){
                 $("#optik-form").modal("hide");
             },500);
             
         });
         owl.on('changed.owl.carousel', function(event) {
             var currentItem = event.item.index;
             //window.location.hash = currentItem + 1;
             $(window).scrollTop(0);
             console.log(currentItem);
             $("#optik-form tr").removeClass("table-success");
             $("#optik-form tr:eq("+currentItem+")").addClass("table-success");
             $.get("?t=sinava-basla&index_cache="+currentItem);
         }); 
         <?php } ?>
        <?php if(oturumisset("cache")) {
            foreach($_SESSION['cache']['cevap'] AS $c => $d)  { 
             
              ?>
              $("#cevap{{$c}}_{{$d}}").prop("checked",true); 
             <?php } ?>
             <?php 
        } ?>
        //$("[data-action='sidebar_toggle']").trigger("click");
        function isaret_calc() {
            var isaretlenen_soru = $("#sinav-form input:checked[value!='']").length;
            var kalan_soru = $(".goto").length - isaretlenen_soru;
            $(".kalan-soru").html(kalan_soru);
            $(".isaretlenen-soru").html(isaretlenen_soru);
        }
        isaret_calc();
        $("#page-container").removeClass("sidebar-o-xs");
        $("#page-container").removeClass("sidebar-o");
        $(".optik input").on("click",function(){

            var bu = $("#sinav-form");
            var id = $(this).attr("data-id");
         //   console.log(id);
            $("#optik"+id+ " input[value='"+$(this).val()+"']").prop("checked",true);
            isaret_calc();
            $(".sinavi-bitir").hide();
            $.ajax({
                type : bu.attr("method"),
                url : bu.attr("action"),
                data : bu.serialize(),
                success: function(d){
                  $(".sinavi-bitir").show();
                
                }

            });
        });
        
        $("#optik-form .cevap-isaret input").on("click",function(e){
          <?php if(!getisset("pdf"))  { 
            ?>
             return false; 
           <?php } else {
              ?>
             
              var bu = $("#sinav-form");
              var id = $(this).attr("data-id");
              id = id.replace("optik","");
             console.log(id);
              $(".optik input[data-id='"+id+"'][value='"+$(this).val()+"']").prop("checked",true);
              isaret_calc();

          //    $(".modal-footer .sinavi-bitir").removeClass("displayBlockImportant");
           //   $(".modal-footer .sinavi-bitir").css("display","none");

              $.ajax({
                  type : bu.attr("method"),
                  url : bu.attr("action"),
                  data : bu.serialize(),
                  success: function(d){
             //       $(".modal-footer .sinavi-bitir").addClass("displayBlockImportant");
                  
                  }

              });
              <?php 
           } ?>
        })
        $(".sinavi-bitir").on("click",function(){
            $("#optik-form").modal("hide");
            $("#sinavi-bitir").modal();
        });
        <?php if(getisset("random")) {
          $cevaplar = explode(",","A,B,C,D,E");
          $sorular = oturum("sorular");
          for($k=1;$k<=count($sorular);$k++)  { 
           $rnd_cevap = $cevaplar[rand(0,4)];
            ?>
            $("#cevap{{$k}}_{{$rnd_cevap}}").prop("checked",true);
            $("#cevap{{$k}}_{{$rnd_cevap}}").attr("checked",true);
            $("#cevap{{$k}}_{{$rnd_cevap}}").trigger("click");
            console.log("#cevap{{$k}}_{{$rnd_cevap}}");
           <?php } ?>
           <?php 
        } ?>
    });
</script>
<style>
  .displayBlockImportant {
    display:block !important;
  }
  
  .displayNoneImportant {
    display:none !important;
  }
  
</style>