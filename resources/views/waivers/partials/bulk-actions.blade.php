<div>
    <button
        class="btn btn-sm btn-outline-primary"
        onclick="event.preventDefault(); document.getElementById('bulk-send').submit()"
    >Send all</button>

    <form action="{{ action('WaiverBulkSendController', ['organization' => request('organization')]) }}" method="post" id="bulk-send">
        @csrf
    </form>
</div>
<div class="ml-4">
    <button
        class="btn btn-sm btn-outline-primary"
        onclick="event.preventDefault(); document.getElementById('bulk-remind').submit()"
    >Remind all</button>

    <form action="{{ action('WaiverBulkRemindController', ['organization' => request('organization')]) }}" method="post" id="bulk-remind">
        @csrf
    </form>
</div>
