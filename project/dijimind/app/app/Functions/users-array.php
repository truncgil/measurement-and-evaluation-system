<?php 
function usersArray($ust="") {

    $users = db("users");
    if($ust!="") {
        $users = $users->where("ust",$ust);
    }
    $users = $users->get();
    $users = dbArray($users,"id");
    return $users;
}
function usersLevel($level="") {

    $users = db("users");
    if($level!="") {
        $users = $users->where("level",$level);
    }
    $users = $users->get();
    $users = dbArray($users,"id");
    return $users;
}
?>