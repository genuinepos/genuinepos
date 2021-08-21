@foreach ($posShortMenus as $sm)
    <li><a href="{{ route($sm->url) }}" title="{{$sm->name}}" class="head-tbl-icon"><span class="{{ $sm->icon }}"></span></a></li> 
@endforeach