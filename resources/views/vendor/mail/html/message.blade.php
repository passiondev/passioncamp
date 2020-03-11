@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<img src="https://res.cloudinary.com/passionconf/image/upload/c_scale,f_auto,h_150,q_auto/v1582922237/passioncamp2020/PCA20-001_Logo-White.png" class="logo" alt="Passion Camp">
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
<a href="https://passionconferences.com"><img src="{{ url('img/conferences-logo.png') }}" width="25" alt="Passion Conferences"></a>
@endcomponent
@endslot
@endcomponent
