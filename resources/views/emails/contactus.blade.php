@component('mail::message')
# {{ $type }}

@component('mail::table')
| Name          | Email         | Phone        |
| :------------ |:-------------:| ------------:|
| {{ $name }}   | {{ $email }}  | {{ $phone }} |
@endcomponent

@component('mail::panel')
{{ $body }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent