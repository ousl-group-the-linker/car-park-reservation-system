@component('mail::message')
# Reset Account Password

We have received a password reset request for your account {{ $email }}, below you can find the reset link. it is
only valid for 1 hour.

@component('mail::button', ['url' => $resetUrl])
Change Password
@endcomponent

If you have truble clicking on the above button, copy past followng link to your browser.

<a href="{{$resetUrl}}">{{$resetUrl}}</a>

If you did not initiate this request, someone may try to access it, if so, you can ignore this email and no further
actions are necessary.

Requested Date & Time<br>
`{{$date}}`

Thanks,<br>
{{ config('app.name') }}
@endcomponent


