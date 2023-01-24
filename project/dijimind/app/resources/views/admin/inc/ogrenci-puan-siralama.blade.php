<table class="table table-bordered table-striped">
                                <tr>
                                    <th>{{e2("Sınav Tarihi")}}</th>
                                    <td>{{date("d.m.Y H:i",strtotime($s->created_at))}}</td>
                                </tr>
                                <tr>
                                    <th>{{e2("Kitapçık")}}</th>
                                    <td>{{$s->kitapcik}}</td>
                                </tr>
                                <tr>
                                    <th>{{e2("Puan")}}</th>
                                    <td>
                                        @if($s->tyt!=0) 
                                            {{$s->tyt}} 
                                        @endif

                                        @if($s->yks_say!=0) 
                                            <div title="Sayısal" class="badge badge-success">{{$s->yks_say}} 
                                                <small>Sayısal</small>
                                            </div>
                                        @endif

                                        @if($s->yks_soz!=0) 
                                            <div title="Sözel" class="badge badge-danger">{{$s->yks_soz}} 
                                                <small>Sözel</small>
                                            </div>
                                        @endif

                                        @if($s->yks_ea!=0) 
                                            <div title="Eşit Ağırlık" class="badge badge-info">{{$s->yks_ea}}
                                                <small>Eşit Ağırlık</small>
                                            </div>
                                        @endif

                                        @if($s->lgs!=0) {{$s->lgs}} @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{e2("Kurum Sıralaması")}}</th>
                                    <td>
                                        @if($s->tyt!=0) 
                                                @php
                                                    $siralama = kurum_siralama2($s,"tyt");
                                                @endphp
                                                {{$siralama['sira']}}  / {{$siralama['toplam']}}
                                        
                                        @endif
                                        @if($s->lgs!=0) 
                                                @php
                                                    $siralama = kurum_siralama2($s,"lgs");
                                                @endphp
                                                {{$siralama['sira']}}  / {{$siralama['toplam']}}
                                        @endif

                                        @if($s->yks_say!=0) 
                                            <div title="Sayısal" class="badge badge-success">
                                           
                                                @php
                                                    $siralama = kurum_siralama2($s,"yks_say");
                                                @endphp
                                                {{$siralama['sira']}}  / {{$siralama['toplam']}}
                                                <small>Sayısal</small>
                                            </div>

                                        @endif

                                        @if($s->yks_soz!=0) 
                                            <div title="Sözel" class="badge badge-danger">
                                            @php
                                                $siralama = kurum_siralama2($s,"yks_soz");
                                            @endphp
                                            {{$siralama['sira']}}  / {{$siralama['toplam']}}
                                            <small>Sözel</small>
                                            </div>
                                        @endif

                                        @if($s->yks_ea!=0) 
                                            <div title="Eşit Ağırlık" class="badge badge-info">
                                            
                                                @php
                                                    $siralama = kurum_siralama2($s,"yks_ea");
                                                @endphp
                                                {{$siralama['sira']}}  / {{$siralama['toplam']}}
                                                <small>Eşit Ağırlık</small>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{e2("Türkiye Sıralaması")}} 
                                        <?php if($s->lgs==0)  { 
                                          ?>
                                         <br>
                                         <small>{{nf(siralama(),"",0)}}</small> 
                                         <?php } ?>
                                    </th>
                                    <td>
                                        @if($s->tyt!=0) {{nf(siralama($s->tyt),"",0)}} @endif
                                       

                                        @if($s->yks_say!=0) 
                                            <div title="Sayısal" class="badge badge-success">
                                            {{nf(siralama($s->yks_say,"yks_say"),"",0)}} 
                                            <small>Sayısal</small>
                                            </div>
                                        @endif

                                        @if($s->yks_soz!=0) 
                                            <div title="Sözel" class="badge badge-danger">
                                            {{nf(siralama($s->yks_soz,"yks_soz"),"",0)}}   
                                            <small>Sözel</small>  

                                            </div>
                                        @endif

                                        @if($s->yks_ea!=0) 
                                            <div title="Eşit Ağırlık" class="badge badge-info">
                                            {{nf(siralama($s->yks_ea,"yks_ea"),"",0)}}
                                            <small>Eşit Ağırlık</small>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                               
                                
                            </table>