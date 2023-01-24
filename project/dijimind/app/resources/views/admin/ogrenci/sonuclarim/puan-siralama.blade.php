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
                                        @if($s->tyt!=0) {{$s->tyt}} @endif
                                        @if($s->lgs!=0) {{$s->lgs}} @endif
                                    </td>
                                </tr>
                               
                                
                            </table>