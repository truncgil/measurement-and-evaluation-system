<?php $sinav = db("sinavlar")->where("id",$sinav_id)->first();
if($sinav)  { 
    $j = j($sinav->dersler);
?>
    <div class="table-responsive mt-10" style="height:100px">
        <table class="table table-bordered table-striped table-sm">
            <?php foreach($j AS $alan => $deger)  { 
            ?>
            <tr>
                <th>{{$deger['isim']}}</th>
                <th>{{$deger['soru']}}</th>
            </tr> 
            <?php } ?>
        </table>
    </div> 
<?php } ?>