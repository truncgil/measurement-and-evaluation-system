<?php function sinav_type($sinif) {
    $lgs = explode(",","7,8");
    if(in_array($sinif,$lgs)) {
        return "LGS";
    } else {
        return "YKS";
    }
} ?>