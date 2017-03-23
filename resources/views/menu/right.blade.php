@if (Auth::check())
    <a class="item {{ request()->route()->getAction() == 'ProfileController@show' ? 'active' :'' }}" href="{{ action('ProfileController@show') }}">Profile</a>
@endif

{{--
@if (auth()->user() && auth()->user()->organization && auth()->user()->organization->slug == 'pcc' && session()->has('printer'))
    <a class="item {{ request()->route()->getAction() == 'checkin.printer.index' ? 'active' :'' }}" href="{{ route('checkin.printer.index') }}"><i class="ui print icon"></i></a>
@elseif (session()->has('printer'))
    <a class="item {{ request()->route()->getAction() == 'roominglist.printer.index' ? 'active' :'' }}" href="{{ route('roominglist.printer.index') }}"><i class="ui print icon"></i></a>
@endif
 --}}
