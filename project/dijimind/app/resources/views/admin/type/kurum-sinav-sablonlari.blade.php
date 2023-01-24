<div class="content">
<div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title"><i class="fa fa-{{$c->icon}}"></i> {{e2($c->title)}}</h3>
            <div class="block-options">
                <div class="block-options-item"> 
                    <a href="{{ url('admin-ajax/content-type-blank-delete?type='. $c->title) }}" teyit="{{__('Tüm boş '.$c->title.'  '._('') )}}" title="{{_('Boş Olan  İçeriklerini Sil')}}" class="btn btn-danger"><i class="fa fa-times"></i> </a>
                    <a href="{{ url('admin-ajax/content-type-add?type='. $c->title) }}" class="btn btn-success" title="Yeni {{$c->title}} {{_('İçeriği Oluştur')}}"><i class="fa fa-plus"></i> </a>
                </div>
            </div>
        </div>
		

        <div class="block-content">
			<div class="js-gallery ">
			@include("admin.inc.table-search")
			<div class="table-responsive">
            <table class="table table-striped table-hover table-bordered table-vcenter">
                <thead>
                    <tr>
                        <th>{{__("Sınav Adı")}}</th>
                        <th>{{__("Şablon Adı")}}</th>
						<!--
                        <th class="d-none">{{__("URL")}}</th>
                        <th>{{__("Kategorisi")}}</th>
                        <th  class="d-none" style="width: 15%;">{{__("Tip")}}</th>
						<th>{{__("Durum")}}</th>
						-->
						<th>{{__("Sıra")}}</th>
                        <th class="text-center" style="width: 100px;">{{__("İşlemler")}}</th>
                    </tr>
                </thead>
                <tbody>
				<?php  $sinavlar = db("sinavlar")->where("title","like","%ŞABLON%")->get(); ?>
				@foreach($alt AS $a)
					<tr class="">
                        <td>
                            <input type="text" name="title" value="{{$a->title}}" table="contents" id="{{$a->id}}" class="title{{$a->id}} form-control edit" />
						</td>
                        <td>
                            
                            <select name="json" table="contents" id="{{$a->id}}" class="form-control edit">
                                <option value="">Seçiniz</option>
                                <?php foreach($sinavlar AS $s)  {  ?>
                                    <option value="{{$s->id}}" <?php if($a->json==$s->id) echo "selected"; ?>>{{$s->title}}</option> 
                                <?php } ?>
                            </select>
                        </td>
						<!--
                        <td class="d-none">
						<div class="input-group">
							<div class="input-group-prepend">
									<div class="btn btn-default" onclick="$.get('{{url('admin-ajax/slug?title='.$a->breadcrumb)}}'+$('.title{{$a->id}}').val(),function(d){
										$('.slug{{$a->id}}').val(d).blur();
									})"><i class="si si-refresh"></i></div>
								</div>
								<input type="text" name="slug" value="{{$a->slug}}" table="contents" id="{{$a->id}}" class="slug{{$a->id}} form-control edit" />
							</div>
							
						</td>
						<td><input type="text" name="kid" value="{{$a->kid}}" table="contents" id="{{$a->id}}" class="form-control edit" /></td>
                        <td  class="d-none">
                          
							<select name="type" id="{{$a->id}}" class="select2 form-control edit" table="contents" >
							@foreach($types AS $t)
								<option value="{{$t->title}}" @if($t->title==$a->type) selected @endif>{{$t->title}}</option>
							@endforeach
							</select>
                        </td>
						<td>
							<select name="y" id="{{$a->id}}" class=" form-control edit" table="contents" >
								<option value="0" @if($a->y==0) selected @endif>{{__("Yayında Değil")}}</option>
								<option value="1" @if($a->y==1) selected @endif>{{__("Yayında")}}</option>
							</select>
						</td>
						-->
 						<td><input type="number" name="s" value="{{$a->s}}" table="contents" id="{{$a->id}}" class="form-control edit" /></td>
						
					  <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ url('admin/contents/'. $a->slug) }}" class="btn btn-secondary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="{{ url('admin/contents/'. $a->slug .'/delete') }}" teyit="{{$a->title}} {{__('içeriğini silmek istediğinizden emin misiniz?')}}" title="{{$a->title}} {{__('Silinecek!')}}" class=" btn  btn-secondary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Delete">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
				@endforeach
				
                                     
                                    </tbody>
            </table>
			{{$alt->fragment('alt')->links() }}
			</div>
			</div>
        </div>
		
    </div>
</div>