<style>
<!--
    ul, li {
        list-style: none;
        margin: 0;
        padding: 0;
    }
-->
</style>
<page>
    {{ $ticket->person->name }} <br>
    {{ $ticket->person->grade }} <br>
    {{ $ticket->leader }} <br>
    {{ $ticket->squad }} <br>
    {{ $ticket->bus }}
    <page_header style="text-align:center">
        <img src="{{ public_path('img/ticket/wristband/icon.png') }}" style="height:100%">
    </page_header>
</page>
