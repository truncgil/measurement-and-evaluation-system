<?php 
$u = u();
if(oturumisset("redirect")) {
    $url = oturum("redirect");
    unset($_SESSION['redirect']);
    yonlendir($url);
}

$level = $u->level; 


$kazanimlar = kazanimlar(); 
$users = usersArray(u()->id);
$colors = colors();
?>
@extends('admin.master')

@section("title")
	{{e2("Dashboard")}}
@endsection
@section('content')



<?php 
?>
<div class="bg-gd-dusk d-none">
    <div class="bg-black-op-25">
        <div class="content content-top content-full text-center">
            <div class="mb-20">
                <a class="img-link" href="">
                    <img class="img-avatar img-avatar-thumb" src="{{url("assets/user.png")}}" alt="">
                </a>
            </div>
            <h1 class="h3 text-white font-w700 mb-10">
                {{$u->name}} {{$u->surname}}
            </h1>
            <h2 class="h5 text-white-op">
                <a class="text-primary-light link-effect" href="javascript:void(0)">{{$u->level}}</a>
            </h2>
        </div>
    </div>
</div>
<style>
	.bg-image {
		display:none;
	}
</style>

<div class="content">

	<?php if($level=="Admin") { ?>
        @include("admin.dashboard.genel-raporlama")
        @include("admin.dashboard.soru-bankasi-raporlari")
        @include("admin.dashboard.branslara-atanmis-sorular") 
    <?php } ?>
   
         
    <?php $level = str_slug($u->level); ?>
    <?php 
    $veli = false;
   
    if($level=="veli") {
        $veli=true;
         ?>
         @include("admin.inc.ogrencileriniz")
         <?php 
    } ?>
    <?php if($level=="ogrenci") {
        $veli = false;
    } ?>
          <?php if(getisset("t")) {
              
             $t = get("t");
              ?>
             <?php if($level=="veli") {
                 ?>
                    @if(View::exists("admin.$level.$t"))
                        @include("admin.$level.$t")
                    @elseif(View::exists("admin.ogrenci.$t"))
                        @include("admin.ogrenci.$t")
                    @endif 
                 <?php 
             } else  { 
               ?>
               @if(View::exists("admin.$level.$t"))
                 @include("admin.$level.$t")
              @endif 
              <?php } ?>

              <?php 
         } else  { 
           ?>
           @if(View::exists("admin.dashboard.$level"))
                @include("admin.dashboard.$level")
             @endif
 		 	 
        
        
         <?php 
    } ?>
    <?php if($u->level=="Uzman") { ?>

        @include("admin.dashboard.uzman-raporlari")
    <?php } ?>
	
			
@endsection
