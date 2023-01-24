<?php 
function max_sira() {
    return 2393283;
}
function dijimind_sira() {
    return 2393283;
}
function kurum_siralama($sonuc,$column) {
    $sinav = db("sonuclar")
        ->select($column,"id")
        ->where("title",$sonuc->title)
        ->orderBy($column,"DESC")
        ->get();

    $say = 0;
    foreach($sinav AS $s) {
        if($sonuc->id==$s->id) {
            break;
        }
        $say++;
    }
    return $say;

}
function kurum_siralama2($sonuc,$column) {
    $sinav = db("sonuclar")
        ->select($column,"id")
        ->where("title",$sonuc->title)
        ->orderBy($column,"DESC")
        ->get();


    $say = 0;
    foreach($sinav AS $s) {
        if($sonuc->id==$s->id) {
            break;
        }
        $say++;
    }
    return [
        'toplam' => $sinav->count(), 
        'sira' => $say
    ];

}


function siralama($puan = null, $type = "tyt"){
    $maxOgrenci = 2911600;
    
    if(is_null($puan)) return $maxOgrenci;
    /*
    {315-300}*[48323/20(aralıktaki puan)]
    */
    //$puan = round($puan,0);
    //echo $puan;
 
    $array = [
        50 =>[100, 2911511, $maxOgrenci],
        100 =>[	120,	2911511, 2911204 ],
        120 =>[140, 2911511, 2911204],
        140 =>[160, 2891765, 2669686],
        160 =>[180, 266968, 2179831],
        180 =>[	200,	2179831, 1711044 ],
        200 =>[	220,	1711044, 1322261 ],
        220 =>[	240,	1322261, 1006285 ],
        240 =>[	260,	1006285, 752024 ],
        260 =>[	280,	752024, 554023 ],
        280 =>[	300,	554023, 405646 ],
        300 =>[	320,	405646, 297237],
        320 =>[	340,	297237, 216201 ],
        340 =>[	360,	216201, 155969 ],
        360 =>[	380,	155969, 109614 ],
        380 =>[	400,	109614, 73044 ],
        400 =>[	420,	73044, 43782 ],
        420 =>[	440,	43782, 21531 ],
        440 =>[	460,	21531, 6963 ],
        460 =>[	480,	6963,904 ],
        480 =>[	500,	904,1 ],
        500 =>[	510,	1,	1 ]

    ];

    if($type == "yks_say") {
        $array = [
            50 =>[100, 1439833, 1429833],
            100 =>[	120,	1429833, 1427872 ],
            120 =>[140, 1427872, 1336740],
            140 =>[160, 1336740, 1049953],
            160 =>[180, 1049953, 752177],
            180 =>[	200,	752177, 543342 ],
            200 =>[	220,	543342, 415971 ],
            220 =>[	240,	415971, 329236 ],
            240 =>[	260,	329236, 264209 ],
            260 =>[	280,	264209, 213695 ],
            280 =>[	300,	213695, 173451 ],
            300 =>[	320,	173451, 141257],
            320 =>[	340,	141257, 114644 ],
            340 =>[	360,	114644, 91711],
            360 =>[	380,	91711, 71150 ],
            380 =>[	400,	71150, 52525 ],
            400 =>[	420,	52525, 35466 ],
            420 =>[	440,	35466, 20134 ],
            440 =>[	460,	20134, 7942 ],
            460 =>[	480,	7942,1336 ],
            480 =>[	500,	1336,1 ],
            500 =>[	510,	1,	1 ]
    
        ];
    }

    if($type == "yks_soz") {
        $array = [
            50 =>[100, 1594079, 1494079],
            100 =>[	120,	1494079, 1493305 ],
            120 =>[140, 1493305, 1464760],
            140 =>[160, 1464760, 1349744],
            160 =>[180, 1349744, 1151486],
            180 =>[	200,	1151486, 910587 ],
            200 =>[	220,	910587, 674046 ],
            220 =>[	240,	674046, 472743 ],
            240 =>[	260,	472743, 314183 ],
            260 =>[	280,	314183, 198050 ],
            280 =>[	300,	198050, 118713 ],
            300 =>[	320,	118713, 67076],
            320 =>[	340,	67076, 34714 ],
            340 =>[	360,	34714, 15716],
            360 =>[	380,	15716, 6322 ],
            380 =>[	400,	6322, 2545 ],
            400 =>[	420,	2545, 1032 ],
            420 =>[	440,	1032, 432 ],
            440 =>[	460,	432, 141 ],
            460 =>[	480,	141,26 ],
            480 =>[	500,	26,1 ],
            500 =>[	510,	1,	1 ]
    
        ];
    }

    if($type == "yks_ea") {
        $array = [
            50 =>[100, 1880135, 1780135],
            100 =>[	120,	17801359, 1779240 ],
            120 =>[140, 1779240, 1739891],
            140 =>[160, 1739891, 1561709],
            160 =>[180, 1561709, 1274374],
            180 =>[	200,	1274374, 969208 ],
            200 =>[	220,	969208, 708493 ],
            220 =>[	240,	708493, 507198 ],
            240 =>[	260,	507198, 358086 ],
            260 =>[	280,	3580863, 250004 ],
            280 =>[	300,	250004, 172499 ],
            300 =>[	320,	172499, 114643],
            320 =>[	340,	114643, 69047 ],
            340 =>[	360,	69047, 33854],
            360 =>[	380,	33854, 14865 ],
            380 =>[	400,	14865, 7581 ],
            400 =>[	420,	7581, 3590 ],
            420 =>[	440,	3590, 1543 ],
            440 =>[	460,	1543, 502 ],
            460 =>[	480,	502, 71 ],
            480 =>[	500,	71,1 ],
            500 =>[	510,	1,	1 ]

        ]; 
    }

    $result;

    //test each set against the $puan variable,
    //and set/reset the $result value
    if($puan<=100) $puan = 101;
    if($puan>510) $puan = 510; 
    $k = 0; 
    foreach($array as $key => $value)
    {
        if($puan > $key)
        {
            //{315-300}*[48323/20(aralıktaki puan)]
            
        
            $p1 = $key;
           
            $p2 = $value[0];
            $start = $value[1];
            $end = $value[2];
            $result = $start-(($puan-$p1)/($p2-$p1))*($start-$end);
           // $result = $start - $result;
        }
        $k++;
    }

    $result = round($result,0);
    $result = abs($result);
   // $result =number_format($result,0,",",".");
    return $result;
   

}
 ?>