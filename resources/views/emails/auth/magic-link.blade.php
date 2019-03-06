@component('mail::message')
# Hello!

You asked us to send you a magic link for quickly signing in to your Passion Students account.

@component('mail::button', ['url' => $link])
Sign In to Passion Students
@endcomponent

Note: Your magic link will expire in 24 hours, and can only be used one time.

@component('mail::subcopy')
If you're having trouble clicking the Sign In button, copy and paste the URL below into your web browser:
<br>[{{ $link }}]({{ $link }})
@endcomponent

@endcomponent
