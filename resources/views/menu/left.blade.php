@if (auth()->user() && auth()->user()->is_super_admin)
    <a class="item {{ request()->route()->getName() == 'admin.organization.index' ? 'active' :'' }}" href="{{ route('admin.organization.index') }}">
        Churches
    </a>
    <a class="item {{ request()->route()->getName() == 'hotel.index' ? 'active' :'' }}" href="{{ route('hotel.index') }}">Hotels</a>
    <a class="item {{ request()->route()->getName() == 'user.index' ? 'active' :'' }}" href="{{ route('user.index') }}">Users</a>
@else
    <a class="item {{ request()->route()->getName() == 'account.dashboard' ? 'active' :'' }}" href="{{ route('account.dashboard') }}">Dashboard</a>
@endif

<a class="item {{ request()->route()->getName() == 'order.index' ? 'active' :'' }}" href="{{ route('order.index') }}">Registrations</a>
<a class="item {{ request()->route()->getName() == 'ticket.index' ? 'active' :'' }}" href="{{ route('ticket.index') }}">Attendees</a>
@if (auth()->user()->is_super_admin || auth()->user()->organization->rooms->count())
    <a class="item {{ request()->route()->getName() == 'roominglist.index' ? 'active' :'' }}" href="{{ route('roominglist.index') }}">Rooming List</a>
@endif

@unless (auth()->user() && auth()->user()->is_super_admin)
    <a class="item {{ request()->route()->getName() == 'account.settings' ? 'active' :'' }}" href="{{ route('account.settings') }}">Account</a>
@endif
