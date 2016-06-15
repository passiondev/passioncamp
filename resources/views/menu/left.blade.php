@if (auth()->user())
    @if (auth()->user()->isSuperAdmin())
        <a class="item {{ request()->route()->getName() == 'admin.organization.index' ? 'active' :'' }}" href="{{ route('admin.organization.index') }}">Churches</a>
        <a class="item {{ request()->route()->getName() == 'hotel.index' ? 'active' :'' }}" href="{{ route('hotel.index') }}">Hotels</a>
        <a class="item {{ request()->route()->getName() == 'user.index' ? 'active' :'' }}" href="{{ route('user.index') }}">Users</a>
    @endif

    @if (auth()->user()->isChurchAdmin())
        <a class="item {{ request()->route()->getName() == 'account.dashboard' ? 'active' :'' }}" href="{{ route('account.dashboard') }}">Dashboard</a>
    @endif

    @if (auth()->user()->isAdmin())
        <a class="item {{ request()->route()->getName() == 'order.index' ? 'active' :'' }}" href="{{ route('order.index') }}">Registrations</a>
        <a class="item {{ request()->route()->getName() == 'ticket.index' ? 'active' :'' }}" href="{{ route('ticket.index') }}">Attendees</a>
    @endif

    @if (auth()->user()->isSuperAdmin() || (auth()->user()->isChurchAdmin() && auth()->user()->organization->has('rooms')))
        <a class="item {{ request()->route()->getName() == 'roominglist.index' ? 'active' :'' }}" href="{{ route('roominglist.index') }}">Rooming List</a>
    @endif

    @if (auth()->user()->isSuperAdmin())
        <a class="item {{ request()->route()->getName() == 'roominglist.export' ? 'active' :'' }}" href="{{ route('roominglist.export') }}">RL Export</a>
    @endif

    @if (auth()->user()->isChurchAdmin())
        <a class="item {{ request()->route()->getName() == 'account.settings' ? 'active' :'' }}" href="{{ route('account.settings') }}">Account</a>
    @endif
    @if (auth()->user()->isOrderOwner())
        <a class="item {{ request()->route()->getName() == 'account' ? 'active' :'' }}" href="{{ route('account') }}">Account</a>
    @endif
@endif



