

<?php 
$paketler = paketler();
//print2($paketler); exit();
$title = "Üyelik Bilgisi"; 
$_SESSION['redirect'] = "odeme";
if(Auth::check()) {
    yonlendir("odeme");
}
?>
@extends('layouts.website2')

@section("title",$title)
@section("description","")
@section("keywords","")


 @section('content')
 @include("inc.page-header")
 <div class="container">
     <div class="block text-center">
     <div class="row">
         <div class="col-md order-2">
            @include("inc.paket-secimi")
         </div>
         <div class="col-md order-1">
            @include("inc.uyelik-formu")
         </div>
     </div>
     </div>
 </div>

<script type="text/javascript">
$(".il").change(function(){
	var ilceler = $('option:selected', this).attr('ilceler').split(",");
	console.log(ilceler);
	$(".ilce").html("");
	$(".ilce").append('<option value="">Seçiniz</option>');
	$.each(ilceler,function(a,b) {
		console.log(b);
		$(".ilce").append('<option value="'+b.toUpperCase()+'">'+b.toUpperCase()+'</option>');
	});
});
</script>
 @endsection

