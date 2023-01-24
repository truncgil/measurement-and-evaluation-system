<div class="row">
     {{col("col-12","Öğrenci Takibi",0)}} 
        <input type="text" placeholder="Öğrenci Ara..." id="ara" class="form-control">
     {{_col()}}
     
</div>
<script>
$(document).ready(function(){
  $("#ara").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#ogrenciler .ogrenci").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
  <?php if(getisset("q")) {
       ?>

       $("#ara").val("<?php echo(get("q")); ?>").trigger("keyup");
       <?php 
  } ?>
});
</script>
<div id="ogrenciler">
        <div class="row">
          <?php foreach(kurum_users("Öğrenci") AS $o) {
              $d = (Array) $o;
               ?>
               {{col("col-md-3 ogrenci text-center",$o->name . " " . $o->surname)}}
                    @include("admin.kurum.ogrenci-takibi.ogrenci-info")
               {{_col()}}

              
               <?php 
          } ?>
            </div>
      </div>