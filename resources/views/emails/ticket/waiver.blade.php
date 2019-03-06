@component('mail::message')

Hello!

We are so excited your student is planning to join us for Passion Camp! We are looking forward to all that Jesus is going to do in the lives of our students during our time together!

Please complete the liability waiver and release for {{ $student }} at the link below. Each student must have a signed waiver and release to participate.

@component('mail::button', ['url' => $waiverLink])
Complete Waiver
@endcomponent

@endcomponent
