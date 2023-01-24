<?php 
$hash = md5(env("SECRET_KEY").get("id"));
if(!getesit("hash",$hash)) exit();
use App\Contents; 
$id = get("id");
$everyone = true;
$sonuc = db("sonuclar")->where("id",$id)->first();
$u = u2($sonuc->uid);

$title = "{$u->name} {$u->surname} {$sonuc->title} Sınav Karnesi";
$description = "Dijimind tarafından sunulan {$u->name} {$u->surname} isimli öğrencinin 
 {$sonuc->title} Sınav Karnesi";
$keywords = "";

?>

@extends('admin.master')

@section("title",$title)
@section("description",$description)
@section("keywords",$keywords)


@section('content')

<nav class="navbar navbar-light bg-light p-10 mb-10">
  <a class="navbar-brand" href="#">
    <img src="{{url("assets/img/logo.svg")}}" width="250" class="d-inline-block align-top" alt="">
  </a>
</nav>
<div class="container ">

    @include("admin.inc.ogrenci-info")
    <?php $s =  $sonuc; ?>
    <div class="row">
     {{col("col-12","")}} 
     @include("admin.inc.ogrenci-puan-siralama")
     {{_col()}}
     </div>
    @include("admin.ogrenci.analizlerim")
</div>
@endsection

