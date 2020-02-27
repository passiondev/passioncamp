<ul class="list-group">
    @foreach ($notifications as $notification)
        <li
            wire:key="{{ $notification->getKey() }}"
            class="
                list-group-item list-group-item-info justify-content-between
            "
            style="border-color:rgba(0,0,0,.125)"
        >
            {{ $notification->data['subject'] }}
            {{ $notification->read() ? 'read' : 'unread' }}
            <button type="button" class="close"  wire:click="markAsRead('{{ $notification->getKey() }}')" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </li>
    @endforeach
</ul>
