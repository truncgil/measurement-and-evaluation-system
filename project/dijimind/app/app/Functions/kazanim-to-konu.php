<?php 
function kazanim_to_konu() {
    $kazanimlar = kazanimlar();
    $kazanim_to_konu = [];
    foreach($kazanimlar AS $brans => $kazanim_item) {
        foreach($kazanim_item AS $konu => $kazanim) {
            foreach($kazanim AS $per_kazanim_item) {
                $per_kazanim_item = trim($per_kazanim_item);
                $kazanim_to_konu[$brans][$per_kazanim_item] = $konu;
            }
            
        }
    }
    return $kazanim_to_konu;
}
function kazanim_to_konu2() {
    $kazanimlar = kazanimlar();
    $kazanim_to_konu = [];
    foreach($kazanimlar AS $brans => $kazanim_item) {
        $brans = str_slug($brans);
        foreach($kazanim_item AS $konu => $kazanim) {
            foreach($kazanim AS $per_kazanim_item) {
                $per_kazanim_item = trim($per_kazanim_item);
                $kazanim_to_konu[$brans][$per_kazanim_item] = $konu;
            }
            
        }
    }
    return $kazanim_to_konu;
}
 ?>