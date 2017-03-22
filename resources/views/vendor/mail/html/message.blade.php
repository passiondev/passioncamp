@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ url('img/camp-logo.png') }}" width="300" alt="Passion Camp">
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @if (isset($subcopy))
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endif
    {{-- Footer --}}
    @slot('footer')
        <tr>
            <td>
                <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content-cell" align="center">
                            <a href="http://268generation.com">
                                <img src="{{ url('img/conferences-logo.png') }}" width="25" alt="Passion Conferences">
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endslot
@endcomponent
