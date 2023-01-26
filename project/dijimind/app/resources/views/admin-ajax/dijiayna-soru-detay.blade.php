<?php 
$readonly = true;
$s = db("soru_bankasi")->where("id",get("id"))->first();
if(is_null($s->a_sira)) {
    $s->a_sira = $k;
} 

if(!isset($s->siraType)) $s->siraType = 0;
if(!isset($s->type)) $s->type = 0;
?>
<div class="p-2" id="soru{{$s->id}}" data-index="{{@$s->$siraType}}">
            <script>console.log("{{$s->type}}")</script>
            <div class="json{{$s->id}} d-none"><?php echo json_encode_tr($s); ?></div>
            <div class="row">
                {{col("col-md-12 text-center","{$s->brans} Soru",3)}} 
                <div class="soru-container">
            <?php if($s->paragraf_grup!="") {
                $paragraf = db("soru_bankasi")->where("id",$s->paragraf_grup)->first();
                 ?>
                 <img src="{{url($paragraf->cover)}}"  class="img-soru img-fluid" alt=""> 
                 <?php 
            } ?>
                <?php if($s->cover!="")  { 
                ?>
                    <img src="{{url($s->cover)}}"  class="img-soru img-fluid" alt=""> 
                <?php } else {
                    ?>
                    {{$s->html}}
                    
                    <?php 
                } ?>
                    </div>
                    
                {{_col()}}
                <div class="col-md-4 d-none">
                    <div class="row">
                        {{col("col-md-12 text-center","Cevabınız")}}
                            <div class="optik mt-10">
                                <?php if($readonly) {
                                    $cevap_sira = $s->a_sira;
                                } else {
                                    $cevap_sira = $k;
                                } ?>
                                {{cevap_input2($cevap_sira,$readonly)}} 
                            </div>
                        {{_col()}}
                        {{col("col-md-12 text-center sorun-bildir","Sorun Bildir",5)}}
                            <p>Eğer bu soru ile ilgili bir sorun veya hata olduğunu düşünüyorsanız lütfen bize bildiriniz.</p>
                           
                                <div data-id="{{$s->id}}" class="btn btn-outline-danger min-width-125 hata-bildir-btn">Soru görünmüyor</div>
                                <div data-id="{{$s->id}}" class="btn btn-outline-danger min-width-125 hata-bildir-btn">Bu soru hatalı</div>
                                <div data-id="{{$s->id}}" class="btn btn-outline-danger min-width-125 hata-bildir-btn">Cevap anahtarı yanlış</div>
                           
                        {{_col()}}
                        <?php if($readonly) {
                            
                            if(sinav_type($u->sinif)=="LGS") {
                                $bos_katsayi = 0.33;
                                $dogru_katsayi = 1.33;
                                $bos_dogru_katsayi = 1;
                            } else {
                                $bos_katsayi = 0.25;
                                $dogru_katsayi = 1.25;
                                $bos_dogru_katsayi = 1;
                            }
                            $kat_sayi = kat_sayi();
                            $taksonomik_duzey = taksonomi_to_level($s->taksonomik_duzey);
                           
                            $this_kat_sayi = $kat_sayi[str_slug($s->brans)];
                             ?>
                             <div class="col-12">
                                <div class="cozum-text dogru-cozdun d-none bg-success text-white">
                                    Tebrikler <strong>{{$taksonomik_duzey}}</strong> düzeyindeki bu soruyu doğru çözdünüz. <br>
                                    <div class="badge bg-dark text-success" style="font-size:30px">
                                    👍 +{{$dogru_katsayi*$this_kat_sayi}} Puan
                                    </div>
                                </div>
                                <div class="cozum-text yanlis-cozdun d-none bg-danger text-white">
                                    Maalesef <strong>{{$taksonomik_duzey}}</strong> düzeyindeki bu soruyu yanlış çözdünüz <!--ve {{$dogru_katsayi*$this_kat_sayi}} puan kaybettiniz-->
                                    <br>
                                    <div class="badge bg-dark text-danger" style="font-size:30px">
                                    👎 -{{$dogru_katsayi*$this_kat_sayi}} Puan
                                    </div>
                                </div>
                                <div class="cozum-text bos-cozdun d-none bg-warning text-white">
                                    Maalesef <strong>{{$taksonomik_duzey}}</strong> düzeyindeki bu soruyu boş bıraktınız <!--ve {{$bos_dogru_katsayi*$this_kat_sayi}} puan kaybettiniz.-->
                                    <br>
                                    <div class="badge bg-dark text-danger" style="font-size:30px">
                                    👎 -{{$bos_dogru_katsayi*$this_kat_sayi}} Puan
                                    </div>
                                </div>
                                <div class="cozum-text bg-warning text-white">
                                    Bu soru {{$s->konu}} / {{$s->kazanim}} ile ilgilidir.
                                </div>
                                <div class="cozum-text bg-primary text-white d-none">
                                    Sınava giren öğrencilerin %... kadarı bu soruyu doğru olarak cevapladı 
                                </div>
                             </div>
                             <?php 
                             if($s->video!="")  { 
                              
                              $url = $s->video;
                              preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);
                              if(isset($matches[0])) {
                              $id = $matches[0];
                              ?>
                            <div class="col-12  text-center">
                                    <div class="btn btn-danger btn-block btn-hero fa-2x video-btn" title="{{$s->brans}} Soru {{$s->a_sira}}" data-src="https://www.youtube.com/embed/{{$id}}?autoplay=0&showinfo=0">
                                        <i class="fa fa-brands fa-video fa-2x"></i> <br>
                                        Video Çözümü
                                    </div>
                            </div><?php } ?>
                            
                             
                              <?php } ?>
                             <?php 
                        } ?>
                    </div>
                </div>
                
            </div>
        </div>