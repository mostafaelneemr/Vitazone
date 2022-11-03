@component('mail::message')
Hello {{$user->name}} ,

This is the Reset Code For Your Account : <code style="color: green">{{$code}}</code>


Thanks,<br>
{{ config('app.name') }}
@endcomponent
