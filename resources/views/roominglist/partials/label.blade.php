<style>
<!--
    ul, li {
        list-style: none;
        margin: 0;
        padding: 0;
    }
-->
</style>
<page backtop="60px">
    <page_header style="text-align:center">
        <img src="{{ public_path('img/roominglist/label/icon.png') }}" style="width:50px">
    </page_header>
    <page_footer style="font-size:8pt; text-align:center">
        {{ $room->name }}
    </page_footer>
    <div style="text-align:center">
        <span style="font-size:12pt">{{ $room->organization->church->name }}</span>
        <br><br>
        @if ($room->roomnumber)
            <span style="font-size: 18pt">{{ $room->roomnumber }}</span>
        @else
            <br><span style="border-bottom:1px solid #000000;width:40%;">____________</span>
        @endif
    </div>
    <br>
    <div style="font-size:9pt; text-align:center">
        @foreach ($room->tickets->assigendSort() as $ticket)
            {{ $ticket->person->name }}
            @if ($ticket->squad)
                <i>{{ strtoupper($ticket->squad) }}</i>
            @endif
            <br>
        @endforeach
    </div>
</page>
