<?php 
$optik = db("optik")->where("id",get("id"))->first();
//print2($optik);

$json = j($optik->json);
$dosya = file_get_contents($optik->files);

$satir = explode("\n",$dosya);
//echo $dosya;
 ?>
 <table class="table text-center table-bordered table-striped">
         
 <?php 
for($a=0;$a<count($satir);$a++) {
    $k = 0;
     ?>
     <tr>
            <td>{{$a+1}}</td>
     <?php 
    foreach($json['alan'] AS $alan) {
        $bas = $json['bas'][$k] - 1 ;
        $son = $json['son'][$k] - 1 ;
        ?> <td>
       <?php if($a==0) { ?>
       <strong>{{$alan}}:</strong> <br>
        <?php }  ?>
        <?php if($alan=="Öğrenci Adı") { ?>
                {{turkce(substr($satir[$a],$bas,$son-($bas)))}}
            <?php } else {
                if($son-$bas==0) {
                    $miktar = 1;
                } else {
                    $miktar = $son-$bas;
                }
                 ?>
                 {{substr($satir[$a],$bas,$miktar)}}
                 <?php 
                
            } ?>
        </td>
        
       
       
        
        <?php 
        $k++;
    }
   // break;
     ?>
           </tr> 
     <?php 
}
?>

        </table>