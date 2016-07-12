@if (Auth::check())
    <a class="item {{ request()->route()->getName() == 'profile' ? 'active' :'' }}" href="{{ route('profile') }}">Profile</a>
@endif

@if (session()->has('printer'))
    <a class="item {{ request()->route()->getName() == 'printer.index' ? 'active' :'' }}" href="{{ route('printer.index') }}"><i class="ui print icon"></i></a>
@endif
