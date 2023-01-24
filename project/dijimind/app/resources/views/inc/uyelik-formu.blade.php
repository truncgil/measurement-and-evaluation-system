<?php 

if(getisset("kaydol")) {
                $varmi = db("users")->where("email",post("email"))->orWhere("phone",post("phone"))->first();
                if($varmi) {
                    bilgi("Bu e-posta ve telefon numarası ile daha önce kaydolunmuştur. Lütfen farklı bilgilerle giriş yapmayı deneyiniz veya şifremi unuttum bölümünden şifre hatırlatması isteyiniz.");
                } else {
                    $post = $_POST;
                    unset($post['_token']);
                    unset($post['create']);
                    unset($post['id']);
                    unset($post['r']);
                    $post['level'] = "Öğrenci";
                    if(isset($admin)) {
                        $post['alias'] = $u->alias;
                        $post['y'] = 1;
                    }
                    $parola = rand(111111,999999);
                    $post['password'] = Hash::make($parola);
                    if(!postisset("sinif")) {
                        if($post['tur']=="YKS") {
                            $post['sinif'] = 12;
                            $post['alan'] = "YKS";
                        } else {
                            $post['sinif'] = 8;
                            $post['alan'] = "LGS";
                        }
                    }
                 
                    

                    ekle($post,"users");
                    //if(!isset($admin)) {
                        $kim = db("users")->where("email",post("email"))->first();
                        //oturum("uid",$kim->id);
                        $email = post("email");
                        $hash = hash("sha512",$email);
                        
                        $link = "https://app.dijimind.com";
                        
                        $link = "$link/uyelik-onayla?hash=$hash&email=$email";
                        $kisalink = substr($link,0,20)."...";
                        $text = "Sayın {$post['name']} {$post['surname']}, Dijimind'a hoşgeldiniz. Size özel oluşturduğumuz üyelik şifreniz $parola üyeliğinizi onaylamak için şu bağlantıya tıklayınız. <br> <a href='$link'>$kisalink</a> ";
                        $text2 = "Sayın {$post['name']} {$post['surname']}, Size özel oluşturduğumuz üyelik şifreniz $parola Dijimind'a hoşgeldiniz üyeliğinizi onaylamak için şu bağlantıya tıklayınız. <br>$link";
                        @mailsend(post("email"),"Hoşgeldiniz",$text);
                        @sms($text2,post("telefon"));
                    //}
                    
                    if(!postesit("anne","")) {
                        $sifre = rand(111111,999999);
                        $uye_bilgi = [
                            "name" => "Anne",
                            "surname" => post("surname"),
                            "level" => "Veli",
                            "password" => Hash::make($sifre),
                            "email" => post("anne"),
                            "phone" => post("anne")
                        ];
                        if(isset($admin)) {
                            $uye_bilgi['alias'] = $u->alias;
                        }
                        try {
                            ekle2($uye_bilgi,"users");
                            $text = "Sayın Veli, çocuğunuz {$_POST['name']} {$_POST['surname']} sizi annesi olarak belirlemiştir. 
                            Çocuğunuzun tüm aktivitelerini takip etmek için: 
                            K. Adı: {$_POST['anne']}
                            Şifre: $sifre
                            Giriş yapmak için: 
                            https://app.dijimind.com/login
                            ";
                            @sms($text,post("anne"));
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                        
                        
                        
                    }
                    if(!postesit("baba","")) {
                        $sifre = rand(111111,999999);
                        $uye_bilgi = [
                            "name" => "Baba",
                            "surname" => post("surname"),
                            "level" => "Veli",
                            "password" => Hash::make($sifre),
                            "email" => post("baba"),
                            "phone" => post("baba")
                        ];
                        if(isset($admin)) {
                            
                            $uye_bilgi['alias'] = $u->alias;
                        }
                        try {
                            ekle2($uye_bilgi,"users");
                            $text = "Sayın Veli, çocuğunuz {$_POST['name']} {$_POST['surname']} sizi babası olarak belirlemiştir. 
                            Çocuğunuzun tüm aktivitelerini takip etmek için: 
                            K. Adı: {$_POST['baba']}
                            Şifre: $sifre
                            Giriş yapmak için: 
                            https://app.dijimind.com/login
                            ";
                            @sms($text,post("baba"));
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                        
                        
                        
                    }
                    if(!isset($admin)) {
                        Auth::loginUsingId($kim->id,true);
                        if(Auth::check()) {
                            //  echo "ok";
                            bilgi("Kayıt işleminiz başarılı şimdi yönlendiriliyorsunuz...","success"); 
                            if(!postesit("r","")) {
        
                                ?>
                                <script>
                                    location.href='{{url(post("r"))}}';
                                </script>
                                <?php 
                            } else {
                                ?>
                                <script>
                                    location.href='{{url("admin")}}';
                                </script>
        
                                <?php 
                            }
                            
                        } else {
                            echo "not login";
                        }
                    } else {
                        yonlendir("admin?t=ogrenciler");
                    }
                    
        
                
                    
                    
                }

             } ?>
             <?php if(!getisset("sinif")) {
                   ?>
                   <h2>{{e2("Kaçıncı sınıftasınız")}}</h2>
                   <hr>
                   <div class="m-10 text-center">
                       <div class="row">
                           <?php for($k=5;$k<=13;$k++) {
                                if(isset($admin)) {
                                    $url  = "?t=ogrenciler&sinif=$k";
                                } else {
                                    $url = "?sinif=$k";
                                }
                                ?>
                                <div class="col-md-4 mb-3">
                                    <a href="{{$url}}" class="thm-btn faqs-contact__btn">
                                        <span>{{$k}}. Sınıf</span>
                                    </a>
                                    
                                </div>
                                <?php 
                           } ?>
                           <div class="col-md-4 mb-3">
                               <?php 
                                if(isset($admin)) {
                                    $url  = "?t=ogrenciler&sinif=Mezun";
                                } else {
                                    $url = "?sinif=Mezun";
                                }
                               ?>
                                    <a href="{{$url}}" class="thm-btn faqs-contact__btn">
                                        <span>Mezun</span>
                                    </a>
                                    
                                </div>
                       </div>
                   </div>
                   <?php 
             } else { ?>
             <p class="aciklama">{{e2("Dijimind'a harika bir başlangıç yapmak için lütfen öğrenci bilgilerinizi doldurun")}}</p>
             <?php 
             if(isset($admin)) {
                $form_url = "?t=ogrenciler&kaydol";
            } else { 
                $form_url = "?kaydol";
            } 
             if(getisset("paket-sec")) {
                 oturumAc();
                 $_SESSION['paket'] = get("paket-sec");
                 

             } ?>
                <form action="{{$form_url}}" method="post" style="margin-bottom:200px;">
                    @csrf            
                    <?php if(getisset("r"))  { 
                      ?>                    
                     <input type="hidden" name="r" value="{{get("r")}}"> 
                     <?php } ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="comment-form__input-box">
                        <?php if(getesit("sinif","11")) {
                             ?>
                             <input type="hidden" name="sinif" value="11">
                             <input type="hidden" name="tur" value="YKS">
                             
                             <?php 
                        } else  { 
                          ?>
                          <?php if(getisset("sinif")) {
                              
                               ?>
                                <input type="hidden" name="sinif" value="{{get("sinif")}}">
                                <input type="hidden" name="tur" value="{{sinav_type(get("sinif"))}}">
                               <?php 
                          } else { ?>
                                     <select name="tur" required id="tur_sec" onchange='
                                     console.log($(this).val());
                                     if($(this).val()=="YKS") {
                                                     $("#alan_sec").removeClass("d-none");
                                                     $("#alan_sec_select").attr("required","required");
                                                 } else {
                                                     $("#alan_sec").addClass("d-none");
                                                     $("#alan_sec_select").removeAttr("required","required");
                                                 }
                                     ' class="">
                                         <option value="">{{e2("Sınav Türü Seçiniz")}}</option>
                                         <option value="YKS" <?php if(oturumesit("paket","YKS")) echo "selected"; ?>>{{e2("YKS (TYT - AYT)")}}</option> 
                                         <option value="LGS" <?php if(oturumesit("paket","LGS")) echo "selected"; ?>>{{e2("LGS")}}</option> 
                                        
                                     
                                     </select>
                                <?php } ?>
                         <?php } ?>
                                    
                            </div>
                        </div>
                        <?php if(true)  { 
                          ?>
                         <div class="col-md-6">
                             <div class="comment-form__input-box">
                                 <input type="text" required placeholder="İsminiz" name="name">
                             </div>
                         </div>
                         <div class="col-md-6">
                             <div class="comment-form__input-box">
                                 <input type="text" required placeholder="Soyisminiz" name="surname">
                             </div>
                         </div>
                         <?php if(isset($admin)) {
                              ?>
                         <div class="col-md-6">
                             <div class="comment-form__input-box">
                                 <input type="text" required placeholder="Sınıf" value="{{get("sinif")}}" name="sinif">
                             </div>
                         </div>
                         <div class="col-md-6">
                             <div class="comment-form__input-box">
                                 <input type="text" required placeholder="Şube" name="sube">
                             </div>
                         </div>     
                              <?php 
                         } ?>
                         <div class="col-md-6">
                             <div class="comment-form__input-box">
                                 <input type="email" required placeholder="E-Posta Adresiniz" name="email">
                             </div>
                         </div>
                         <div class="col-md-6">
                             <div class="comment-form__input-box">
                                 <input type="text" required placeholder="Telefon Numaranız" name="phone">
                             </div>
                         </div>
                         <div class="col-md-6">
                             <div class="comment-form__input-box">
                                 <input type="text" placeholder="Anne Telefonu" name="anne">
                             </div>
                         </div>
                         
                         <div class="col-md-6">
                             <div class="comment-form__input-box">
                                 <input type="text" placeholder="Baba Telefonu" name="baba">
                             </div>
                         </div>
                         <?php $sinif = get("sinif");
                         $goster = false; 
                         if($sinif=="Mezun") $goster = true;
                         if($sinif>8) $goster = true;
                         if($goster) { ?>
                         <div class="col-md-12  " id="alan_sec">
                             <div class="comment-form__input-box"> 
                               
                              
                                     <select name="alan" required id="alan_sec_select" class="">
                                        
                                         <option value="">{{e2("Alan Seçiniz")}}</option>
                                         <?php foreach(alanlar() AS $alan)  { 
                                           ?>
                                          <option value="{{$alan}}">{{e2($alan)}}</option>  
                                          <?php } ?>
                                     
                                     </select>
                                     
                             </div> 
                         </div> 
                         <?php } ?>
                         <div class="col-md-12 d-none">
                             <div class="comment-form__input-box">
                                 <textarea name="address" placeholder="Adresiniz (kargo geleceği için lütfen tam adresinizi giriniz)" id="" cols="30" rows="10"></textarea>
                             </div>
                         </div>
                         <div class="col-md-12">
                             <div class="comment-form__input-box">
                             <?php $il = explode("\n",file_get_contents(url("il-ilce"))); 
                             //print2($il);
                             ?>
 		
         
                                 <select name="il" placeholder="İl" required id="" class="il">
                                     <option value="">İl Seçiniz</option>
                                     <?php foreach($il AS $i) { 
                                         if(trim($i)!="")  { 
                                         
                                         $a = explode(":",$i);
                                      //   print2($a);
                                         $a[0] =mb_strtoupper(explode(" ",$a[0])[1]);
                                         
                                         if($a[0] !="") {
                                     ?>
                                     <option value="<?php echo($a[0]) ?>" ilceler="<?php echo($a[1]); ?>"><?php echo($a[0]) ?></option>
                                         <?php } ?>
                                     <?php } ?> 
                                         <?php } ?>
                                 </select>
                               
                             </div>
                         </div>
                         <div class="col-md-6 d-none">
                             <div class="comment-form__input-box">
                                 <select name="ilce" placeholder="İlçe"  id="" class="ilce">
                                             <option value="">İlçe Seçiniz</option>
                                 </select>
                               
                             </div>
                         </div>
                         <?php if(!isset($admin))  { 
                           ?>
                          <div class="col-12 ">
                              <div class="small">
                                  <div class="alert alert-info text-left" style="text-align:left;">
 
 
                                     
                                      <input type="checkbox" name="" required="" id="">
                                      <a href="https://app.dijimind.com/kisisel-verilere-iliskin-veri-sahibi-acik-riza-beyan-formu"
                                          class="window">KVKK Açık Rıza Beyan Formu</a> Okudum<br>
 
 
                                  </div>
                              </div>
                          </div> 
                          <?php } ?>
                         <button type="submit" style="margin:0 auto;display:block;width:300px;" class="thm-btn faqs-contact__btn uye-ol"><span>{{e2("Üyeliği Oluştur")}}</span></button>
                            <div class="text-center  giris-yap">
                            <a href="{{url("login")}}" class="">{{e2("Giriş Yap")}}</a>
                            </div>
                         <?php } ?>
                    </div>
                </form>
                <script>
                    $(function(){
                        $("form").on("submit",function(){
                            $("[type='submit']").attr("disabled","true");
                            $("[type='submit']").html("{{e2("İşlem Yapılıyor...")}}");

                        });
                    });
                </script>
                <?php } ?>