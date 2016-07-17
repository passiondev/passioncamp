<table class="ui striped table">
    <tr class="{{ session('printer') == 'PDF' ? 'positive' : '' }}">
        <td>
            <h4 class="ui header">
                Print to PDF
            </h4>
        </td>
        <td></td>
        <td>
            @unless (session('printer') == 'PDF')
                <form action="{{ route("{$prefix}.printer.select", 'PDF') }}" method="POST">
                    {{ csrf_field() }}
                    <button type="submit" class="ui primary button">Select</button>
                </form>
            @endunless
        </td>
    </tr>
    @foreach ($printers as $printer)
        <tr class="{{ session('printer') == $printer->id ? 'positive' : '' }}">
            <td>
                <h5 class="ui header">
                    {{ $printer->name }}
                    <div class="sub header">{{ $printer->computer->name }}</div>
                </h5>
            </td>
            <td>
                <a href="{{ route("{$prefix}.printer.test", $printer->id) }}">test</a>
            </td>
            <td>
                @unless (session('printer') == $printer->id)
                    <form action="{{ route("{$prefix}.printer.select", $printer->id) }}" method="POST">
                        {{ csrf_field() }}
                        <button type="submit" class="ui primary button">Select</button>
                    </form>
                @endunless
            </td>
        </tr>
    @endforeach
</table>
