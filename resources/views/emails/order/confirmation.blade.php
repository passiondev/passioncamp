@component('mail::message')
Passion Camp is going to be amazing. We are so excited your student will be joining us. We are looking forward to all that Jesus is going to do in the lives of our students during our time together!

If you have any questions, please contact our team at [students@passioncitychurch.com](mailto:students@passioncitychurch.com).

**The Passion Students Team**

---

@include('order.receipt')

@component('mail::panel')
## Help make Passion Camp possible!

We want as many students to experience Jesus at Passion Camp as possible this year! Would you consider partnering with us to help make Passion Camp a possibility for students who need financial assistance? We never want finances to keep a student from being able to join us. We are stunned every year by the generosity of our House!

[Partner with us and make a donation](https://forms.ministryforms.net/viewForm.aspx?formId=59aa564b-7b60-4746-ae4b-8220307a9aa6)
@endcomponent

@component('mail::subcopy')
[Sign in to your account]({{ route('magic.login') }}) to view your registration and pay your remaining balance.</a>
@endcomponent

@endcomponent
