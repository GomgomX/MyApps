<x-mail::message>

<h3>Created a new account!</h3>
<p>An account has been registered on <a href={{config('app.url')}}><b>{{$server_config['serverName']}}</b></a>.</p>
<p>Account Name: <b>{{htmlspecialchars($account)}}</b>
<p>Password: <b>{{htmlspecialchars($password)}}</b>
</p>

@php $url = config('app.url').'account/accountmanagement' @endphp
<x-mail::button :url="$url">
Login
</x-mail::button>

Thanks,<br>
{{$server_config['serverName']}} Team
</x-mail::message>
