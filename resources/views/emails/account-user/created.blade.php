@component('mail::message')
# Hey {{ $user->person->first_name }}!

You've been added to the Passion Camp account for <strong>{{ $user->organization->church->name }}</strong>. Click the link below to create your account and sign in to the Passion Camp Portal.

@component('mail::button', ['url' => route('complete.registration', [$user, $user->hash])])
Create Your Account
@endcomponent

@endcomponent