<?php 
function add_score($score,$uid="") {
    if($uid=="") $uid = u()->id;
    $id = ekle2([
       
        "uid" =>  $uid,
        "adet" =>  $score,
    ],"score");
    return $id;
}

function get_score($uid="") {
    if($uid=="") $uid = u()->id;
    return db("score")->where("uid",$uid)->sum("adet");
}
function get_score_alias() {
    return db("score")->whereIn("uid",u()->alias_ids)->sum("adet");
}
function get_rozet_alias() {
    return 100;
}
function get_rozet() {
    return 100;
}
 ?>