@component('mail::message')
# Account Activation

Please activate your Newsletter!

@component('mail::button', ['color' => 'grey', 'url' => $data->link])
Activate Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
