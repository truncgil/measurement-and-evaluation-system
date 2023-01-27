<div class="js-chat-container content content-full invisible" data-toggle="appear" data-chat-height="auto">
    <!-- Multiple Chat (auto height) -->
    <div class="block mb-0">
        <ul class="js-chat-head nav nav-tabs nav-tabs-alt bg-body-light" data-toggle="tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="#chat-window1">
                    <img class="img-avatar img-avatar16" src="assets/media/avatars/avatar14.jpg" alt="">
                    <span class="ml-5">Ümit Tunç (TYT Şube 10)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#chat-window2">
                    <img class="img-avatar img-avatar16" src="assets/media/avatars/avatar7.jpg" alt="">
                    <span class="ml-5">Servet Demir (Rehber Öğretmen)</span>
                </a>
            </li>
            <li class="nav-item ml-auto">
                <a class="nav-link" href="#chat-people">
                    <i class="si si-users"></i>
                </a>
            </li>
        </ul>
        <div class="tab-content overflow-hidden">
            <!-- Chat Window #1 -->
            <div class="tab-pane fade show active" id="chat-window1" role="tabpanel">
                <!-- Messages (demonstration messages are added with JS code at the bottom of this page) -->
                <div class="js-chat-talk block-content block-content-full text-wrap-break-word overflow-y-auto" data-chat-id="1"></div>

                <!-- Chat Input -->
                <div class="js-chat-form block-content block-content-full block-content-sm bg-body-light">
                    <form action="be_comp_chat_multiple.html" method="post">
                        <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-comment text-primary"></i>
                                </span>
                            </div>
                            <input class="js-chat-input form-control" type="text" data-target-chat-id="1" placeholder="Mesajınızı buraya yazın ve enter tuşuna basın">
                        </div>
                    </form>
                </div>
                <!-- END Chat Input -->
            </div>
            <!-- END Chat Window #1 -->

            <!-- Chat Window #2 -->
            <div class="tab-pane fade" id="chat-window2" role="tabpanel">
                <!-- Messages (demonstration messages are added with JS code at the bottom of this page) -->
                <div class="js-chat-talk block-content block-content-full text-wrap-break-word overflow-y-auto" data-chat-id="2"></div>

                <!-- Chat Input -->
                <div class="js-chat-form block-content block-content-full block-content-sm bg-body-light">
                    <form action="be_comp_chat_multiple.html" method="post">
                        <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-comment text-primary"></i>
                                </span>
                            </div>
                            <input class="js-chat-input form-control" type="text" data-target-chat-id="2" placeholder="Type your message and hit enter..">
                        </div>
                    </form>
                </div>
                <!-- END Chat Input -->
            </div>
            <!-- END Chat Window #2 -->

            <!-- People -->
            <div class="tab-pane fade fade-left" id="chat-people" role="tabpanel">
                <div class="js-chat-people block-content block-content-full overflow-y-auto bg-pattern" style="background-image: url('assets/media/various/bg-pattern-inverse.png');">
                    <div class="row mb-0">
                        <div class="col-md-4">
                            <h3 class="h1 font-w300 text-success">{{e2("Çevrimiçi")}}</h3>
                            <ul class="nav-users push">
                                <?php for($k=1;$k<=10;$k++) { ?>
                                <li>
                                    <a href="javascript:void(0)">
                                        <img class="img-avatar" src="assets/icon.svg" alt="">
                                        <i class="fa fa-circle text-success"></i> Öğrenci {{$k}}
                                        <div class="font-w400 font-size-xs text-muted">LGS Hazırlık Öğrencisi</div>
                                    </a>
                                </li>
                                <?php } ?>
                                
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h3 class="h1 font-w300 text-warning">Meşgul</h3>
                            <ul class="nav-users mt-10 push">
                            <?php for($k=1;$k<=10;$k++) { ?>
                                <li>
                                    <a href="javascript:void(0)">
                                        <img class="img-avatar" src="assets/icon.svg" alt="">
                                        <i class="fa fa-circle text-success"></i> Öğrenci {{$k}}
                                        <div class="font-w400 font-size-xs text-muted">LGS Hazırlık Öğrencisi (Şu an deneme sınavında)</div>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h3 class="h1 font-w300 text-muted">Çevrimdışı</h3>
                            <ul class="nav-users mt-10 push">
                            <?php for($k=1;$k<=10;$k++) { ?>
                                <li>
                                    <a href="javascript:void(0)">
                                        <img class="img-avatar" src="assets/icon.svg" alt="">
                                        <i class="fa fa-circle text-success"></i> Öğrenci {{$k}}
                                        <div class="font-w400 font-size-xs text-muted">LGS Hazırlık Öğrencisi</div>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END People -->
        </div>
    </div>
    <!-- END Multiple Chat (auto height) -->
</div>
<!-- END Page Content -->

<script src="{{url("assets/admin/js/pages/be_comp_chat.min.js")}}"></script>
        <script>
            jQuery(function () {
                // Add demonstration headers and messages for Chat #1
                BeCompChat.addHeader(1, '5 saat önce');
                BeCompChat.addMessage(1, 'Selam Hocam!');
                BeCompChat.addMessage(1, 'Sence nasıl gidiyor. Dijimind Rapordan yola çıkarak bana şöyle güzel bir rota belirleyebilir misiniz?');
                BeCompChat.addMessage(1, 'Teşekkürler');
                BeCompChat.addHeader(1, '2 saat önce');
                BeCompChat.addMessage(1, 'Selam Ümit!', 'self');
                BeCompChat.addMessage(1, 'Elbette! önce şu raporunu bir inceleyeyim. Akabinde yazacağım. Beklemede kal. ', 'self');

                // Add demonstration headers and messages for Chat #2
                BeCompChat.addHeader(2, 'Dün');
                BeCompChat.addMessage(2, 'Sayın müdürüm selamlar!');
                BeCompChat.addMessage(2, 'Öğrenci raporlarını size mail attım.?');
                BeCompChat.addHeader(2, 'Bugün');
                BeCompChat.addMessage(2, 'Servet Hocam selamlar!', 'self');
                BeCompChat.addMessage(2, 'Dün çok yoğundu bakamadım. Mailin bana ulaştı. Çok teşekkürler emeğine sağlık!', 'self');
            });
        </script>