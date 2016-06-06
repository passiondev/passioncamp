@if (Auth::check())
    <a class="item {{ request()->route()->getName() == 'profile' ? 'active' :'' }}" href="{{ route('profile') }}">Profile</a>
@endif
