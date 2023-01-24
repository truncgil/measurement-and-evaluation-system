<?php 
if(!isset($ders_listesi)) $ders_listesi=true;
?>
<script>
    $(function(){
        $(".ders-sec").on("click",function(){
            $(".ders-sec").each(function(){
                console.log($(this).is(":checked"));
                var id = $(this).val();
                if($(this).is(":checked")) {
                   
                    $("."+id).removeClass("d-none");
                } else {
                    $("."+id).addClass("d-none");
                }
                console.log($(this).val());
            });
        });
    });
</script>
<?php if($ders_listesi)  { 
  ?>
 <div class="col-12">
     <div class="push ders-listesi">
     
         <div class="btn-group d-none ders-listesi" role="group" aria-label="Button group with nested dropdown">
             <div class="table-responsive text-center">
                 <?php foreach($j AS $alan => $deger)  { 
                     $title = slug_to_title($alan);
                 ?>
                 <div class="btn-group m-1" role="group">
                     <button type="button" class="btn btn-outline-primary">
                     <input type="checkbox" name="" class="ders-sec" value="{{$alan}}" id="">    
                     {{$title}}</button>
                     
                 </div> 
                 <?php } ?>
             </div>
         </div>
     </div>
 </div> 
 <?php } ?>
<?php 
$p = 1;
$gtoplam = array();
$gtoplam['dogru'] = 0;
$gtoplam['yanlis'] = 0;
$gtoplam['bos'] = 0;
$gtoplam['net'] = 0;
$gtoplam['yuzde'] = 0;

foreach($j AS $alan => $deger) {
    $title = slug_to_title($alan);
    $toplam = $deger['dogru'] + $deger['yanlis'] + $deger['bos'];
    $yuzde = $deger['net']*100/$toplam;
    $gtoplam['dogru'] += $deger['dogru'];
    $gtoplam['yanlis'] += $deger['yanlis'];
    $gtoplam['bos'] += $deger['bos'];
    $gtoplam['net'] += $deger['net'];
    $gtoplam['yuzde'] += $yuzde;
             ?>
    {{col("col-md-4 ders-adi $alan d-none",$title,$p)}}
   
             <div class="table-responsive">
                 <table class="table table-bordered">
                     <tr>
                         <td>{{e2("Doğru")}}</td>
                         <th>{{$deger['dogru']}}</th>
                     </tr>
                     <tr>
                         <td>{{e2("Yanlış")}}</td>
                         <th>{{$deger['yanlis']}}</th>
                     </tr>
                     <tr>
                         <td>{{e2("Boş")}}</td>
                         <th>{{$deger['bos']}}</th>
                     </tr>
                     <tr>
                         <td>{{e2("Net")}}</td>
                         <th>{{round($deger['net'],2)}} </th>
                     </tr>
                     <tr>
                         <td>{{e2("Başarı Yüzdesi")}}</td>
                         <th>%{{round($yuzde,2)}}</th>
                     </tr>
                     <tr>
                         <td>{{e2("Cevaplar")}}</td>
                         <th>{{$deger['cevaplar']}}</th>
                     </tr>
                 </table>
             </div>

           
    {{_col()}}
    <?php 
        $p++; } ?>
        {{col("col-md-12","ÖZET",10)}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th>{{e2("Dersler")}}</th>
                    <th>{{e2("D")}}</th>
                    <th>{{e2("Y")}}</th>
                    <th>{{e2("B")}}</th>
                    <th>{{e2("N")}}</th>
                    <th>{{e2("%")}}</th>
                    <th>{{e2("Cevaplar")}}</th>
                </tr>
                
                <?php foreach($j AS $alan => $deger)  {
                    $toplam = $deger['dogru'] + $deger['yanlis'] + $deger['bos'];
                    $yuzde = $deger['net']*100/$toplam; 
                    $title = slug_to_title($alan);
                  ?>
                 <tr>
                     <th>{{$title}}</th>
                     <td>{{$deger['dogru']}}</td>
                     <td>{{$deger['yanlis']}}</td>
                     <td>{{$deger['bos']}}</td>
                     <td>{{round($deger['net'],2)}}</td>
                     <td>%{{round($yuzde,2)}}</td>
                     <td>{{$deger['cevaplar']}}</td>
                 </tr> 
                 <?php } ?>
                 <tr class="table-success">
                    <th>{{e2("GENEL TOPLAM")}}</th>
                    <td>{{$gtoplam['dogru']}}</td>
                    <td>{{$gtoplam['yanlis']}}</td>
                    <td>{{$gtoplam['bos']}}</td>
                    <td>{{round($gtoplam['net'],2)}}</td>
                    <td>%{{round($gtoplam['yuzde']/$p,2)}}</td>
                    <td></td>
                </tr>
            </table>
            
        </div>
    {{_col()}}