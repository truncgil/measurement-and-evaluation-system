<?php 
function sinav_cache_remove() {
    global $_SESSION;
    unset($_SESSION['kid']);
    unset($_SESSION['cache_sure']);
    unset($_SESSION['sure']);
    unset($_SESSION['sorular']);
    unset($_SESSION['cache']);
    unset($_SESSION['index_cache']);
    unset($_SESSION['sinav_id']);
    unset($_SESSION['sinav_type']);
    unset($_SESSION['title']);
    unset($_SESSION['pdf']);
}
?>