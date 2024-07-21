<x-mail::message>

<h3>Password to account changed!</h3>
<p>Password to your account was changed on <a href={{config('app.url')}}><b>{{$server_config['serverName']}}</b></a>.</p>
<p>New password: <b>{{htmlspecialchars($password)}}</b></p>

@php $url = config('app.url').'account/accountmanagement' @endphp
<x-mail::button :url="$url">
Login
</x-mail::button>

Thanks,<br>
{{$server_config['serverName']}} Team
</x-mail::message>