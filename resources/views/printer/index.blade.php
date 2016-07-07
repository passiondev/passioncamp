@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <table class="ui striped table">
            @foreach ($printers as $printer)
                <tr class="{{ session('printer') == $printer->id ? 'positive' : '' }}">
                    <td>
                        <h5 class="ui header">
                            {{ $printer->name }}
                            <div class="sub header">{{ $printer->computer->name }}</div>
                        </h5>
                    </td>
                    <td>
                        @unless (session('printer') == $printer->id)
                            <form action="{{ route('printer.select', $printer->id) }}" method="POST">
                                {{ csrf_field() }}
                                <button type="submit" class="ui primary button">Select</button>
                            </form>
                        @endunless
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@stop
