@component('mail::message')
@markdown($occurrence->confirmation)

You will receive another email with instructions for completing a waiver and release. Each student must have a signed waiver to participate.

If you have any questions, please contact our team at [students@passioncitychurch.com](mailto:students@passioncitychurch.com).

**The Passion Students Team**

---

@include('order.receipt')

@endcomponent
