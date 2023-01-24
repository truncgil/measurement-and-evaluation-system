<li>

<a href="{{url('admin?t=odemelerim')}}"><i class="si si-credit-card"></i><span

class="sidebar-mini-hide">{{__('Ödemeler')}}</span></a>

</li>
<li>

<a href="{{url('admin?t=todo')}}"><i class="fa fa-clipboard-check"></i><span

class="sidebar-mini-hide">{{__('Görevler')}}</span></a>

</li>
<li>

<a  href="{{url('admin?t=sinavlarim')}}"><i class="fa fa-pen"></i><span

class="sidebar-mini-hide">{{__('Sınavlar')}}</span></a>

</li>


<a  href="{{url('admin?t=sonuclarim')}}"><i class="fa fa-poll-h"></i><span

class="sidebar-mini-hide">{{__('Sonuçlar')}}</span></a>

</li>
<li>

<a  href="{{url('admin?t=analizlerim')}}"><i class="fa fa-search"></i><span

class="sidebar-mini-hide">{{__('Analizler')}}</span></a>

</li>

@include("admin.inc.menu.ogrenci")