<style>
<!--
    ul, li {
        list-style: none;
        margin: 0;
        padding: 0;
    }
-->
</style>
<page style="font-size: 10pt" backtop="55px">
    <page_header style="text-align:center">
        <img src="{{ public_path('img/roominglist/label/icon.png') }}" style="width:50px">
    </page_header>
    <page_footer style="text-align:center">
        {{ $room->name }}
    </page_footer>
    <br><br>
    <div style="text-align:center">
        <span style="border-bottom:1px solid #000000;width:40%;">____________</span>
    </div>
    <br>
    @foreach ($room->tickets->assigendSort() as $ticket)
        <div style="text-align:center">{{ $ticket->person->name }}</div>
    @endforeach
</page>
