<div class="row">
    <?php if(getisset("id"))  { 
      ?>
     @include("admin.kurum.kaynak-onerileri.detay") 
     <?php } else  { 
       ?>
     @include("admin.kurum.kaynak-onerileri.form")
     @include("admin.kurum.kaynak-onerileri.liste") 
      <?php } ?>
</div>