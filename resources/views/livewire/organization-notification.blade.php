<ul class="list-group">
    @foreach ($notifications as $notification)
        <li
            wire:key="{{ $notification->getKey() }}"
            class="
                list-group-item justify-content-between
            ">
            {{ $notification->data['subject'] }}
            {{ $notification->read() ? 'read' : 'unread' }}
            <button type="button" class="close"  wire:click="markAsRead('{{ $notification->getKey() }}')" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </li>
    @endforeach
</ul>
