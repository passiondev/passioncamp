<roominglist-room :room="{{ json_encode($room) }}">
<div class="room card">
    <header class="card-header">
        <div class="d-flex justify-content-between align-items-baseline">
            <slot name="header">
                <h4>{{ room.name }}</h4>
            </slot>
            <slot name="actions"></slot>
        </div>
    </header>
    <div class="card-block">
        <div class="statistics justify-content-center mb-3">
            <div class="blue statistic">
                <div class="value">{{ assigned }}</div>
                <div class="label">Assigned</div>
            </div>
            <div class="green statistic">
                <div class="value">{{ room.capacity }}</div>
                <div class="label">Capacity</div>
            </div>
        </div>

        <div class="tickets">
            <div class="list-group" v-dragula="tickets" service="roominglist" style="min-height: 1rem">
                <roominglist-ticket v-for="ticket in tickets" :key="ticket.id" :ticket="ticket"></roominglist-ticket>
            </div>
        </div>
    </div>
    <div class="card-footer text-muted bg-white" v-if="room.description || room.notes">
        <h6 class="mb-0">{{ room.description }}</h6>
        <p class="card-text">{{ room.notes }}</p>
    </div>
    @if (strlen($room->description) || strlen($room->notes))
    @endif
</div>
</roominglist-room>
