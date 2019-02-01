@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ url('img/camp-logo.svg') }}" width="300" alt="Passion Camp">
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            <a href="http://268generation.com"><img src="{{ url('img/conferences-logo.png') }}" width="25" alt="Passion Conferences"></a>
        @endcomponent
    @endslot
@endcomponent
