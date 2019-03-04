@component('mail::message')
@markdown($occurrence->confirmation)

If you have any questions, please contact our team at [students@passioncitychurch.com](mailto:students@passioncitychurch.com).

**The Passion Students Team**

---

@include('order.receipt')

@if ($order->balance > 0)
@component('mail::subcopy')
<a href="{{ route('magic.login') }}">Sign in to your account</a> to view your registration and pay your remaining balance.</a>
@endcomponent
@endif

@endcomponent
