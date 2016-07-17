@if (Auth::check())
    <a class="item {{ request()->route()->getName() == 'profile' ? 'active' :'' }}" href="{{ route('profile') }}">Profile</a>
@endif

@if (auth()->user() && auth()->user()->organization && auth()->user()->organization->slug == 'pcc' && session()->has('printer'))
    <a class="item {{ request()->route()->getName() == 'checkin.printer.index' ? 'active' :'' }}" href="{{ route('checkin.printer.index') }}"><i class="ui print icon"></i></a>
@elseif (session()->has('printer'))
    <a class="item {{ request()->route()->getName() == 'roominglist.printer.index' ? 'active' :'' }}" href="{{ route('roominglist.printer.index') }}"><i class="ui print icon"></i></a>
@endif
