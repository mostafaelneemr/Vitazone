@component('mail::message')
Hello {{$user->name}} ,

{{ $content }} : <code style="color: green">{{$code}}</code>


Thanks,<br>
{{ config('app.name') }}
@endcomponent
