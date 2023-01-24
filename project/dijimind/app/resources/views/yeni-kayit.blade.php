<?php 
use App\Contents; 
use App\User; 
$title = _("Yeni Üyelik");
$description = _("Bu sayfa ile üyelik bilgilerinizi girerek sistemimize kaydolabilirsiniz.");
$keywords = "yeni üyelik,register,create new account";

?>

@extends('layouts.website2')

@section("title",$title)
@section("description",$description)
@section("keywords",$keywords)


@section('content')
@include("inc.page-header")
<div class="container">
    <div class="row">
        <div class="col-12">
            @include("inc.uyelik-formu")
        </div>
    </div>
</div>
    

@endsection

