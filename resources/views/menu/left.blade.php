@if (auth()->user())
    @if (auth()->user()->isSuperAdmin())
        <a class="item {{ request()->route()->getAction() == 'Super\OrganizationController@index' ? 'active' :'' }}" href="{{ action('Super\OrganizationController@index') }}">Churches</a>
        {{-- <a class="item {{ request()->route()->getAction() == 'hotel.index' ? 'active' :'' }}" href="{{ route('hotel.index') }}">Hotels</a> --}}
        <a class="item {{ request()->route()->getAction() == 'Super\UserController@index' ? 'active' :'' }}" href="{{ action('Super\UserController@index') }}">Users</a>
    @endif
{{--
    @if (auth()->user()->isChurchAdmin())
        <a class="item {{ request()->route()->getAction() == 'account.dashboard' ? 'active' :'' }}" href="{{ route('account.dashboard') }}">Dashboard</a>
    @endif

    @if (auth()->user()->isAdmin())
        <a class="item {{ request()->route()->getAction() == 'order.index' ? 'active' :'' }}" href="{{ route('order.index') }}">Registrations</a>
        <a class="item {{ request()->route()->getAction() == 'ticket.index' ? 'active' :'' }}" href="{{ route('ticket.index') }}">Attendees</a>
    @endif

    @if (auth()->user()->isSuperAdmin() || (auth()->user()->isChurchAdmin() && auth()->user()->organization->has('rooms')))
        <a class="item {{ request()->route()->getAction() == 'roominglist.index' ? 'active' :'' }}" href="{{ route('roominglist.index') }}">Rooming List</a>
    @endif

    @if (auth()->user()->isSuperAdmin())
        <a class="item {{ request()->route()->getAction() == 'roominglist.overview' ? 'active' :'' }}" href="{{ route('roominglist.overview') }}">RL Overview</a>
        <a class="item {{ request()->route()->getAction() == 'roominglist.export' ? 'active' :'' }}" href="{{ route('roominglist.export') }}">RL Export</a>
    @endif

    @if (auth()->user()->isChurchAdmin())
        <a class="item {{ request()->route()->getAction() == 'account.settings' ? 'active' :'' }}" href="{{ route('account.settings') }}">Account</a>
    @endif

    @if (auth()->user()->isChurchAdmin() && auth()->user()->organization->slug == 'pcc')
        <a class="item {{ request()->route()->getAction() == 'checkin.index' ? 'active' :'' }}" href="{{ route('checkin.index') }}">Check In</a>
    @endif

    @if (auth()->user()->isOrderOwner())
        <a class="item {{ request()->route()->getAction() == 'account' ? 'active' :'' }}" href="{{ route('account') }}">Account</a>
    @endif
 --}}
@endif



