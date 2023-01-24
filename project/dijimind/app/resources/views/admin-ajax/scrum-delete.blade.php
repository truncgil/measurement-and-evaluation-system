<?php 
$u = u();
$db = db("todo");
$ajanda = db("ajanda");

if($u->level !="Öğrenci") {
    $db = $db->whereIn("uid",$u->alias_ids);
    $ajanda = $ajanda->whereIn("uid",$u->alias_ids);

} else {
    $db = $db->where("uid",$u->id);
    $ajanda = $ajanda->where("uid",$u->id);
}

$db = $db->where("id",post("id"))
    ->delete();
$ajanda = $ajanda->where("kid",post("id"))
    ->delete();

echo "$db $ajanda todo delete ok";
?>