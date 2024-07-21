<x-mail::message>
    
<h3>Generated a recovery key!</h3>
<p>Recovery key has been generated for your account on <a href={{config('app.url')}}><b>{{$server_config['serverName']}}</b></a>.</p>
<p>Recovery key: <b>{{htmlspecialchars($key)}}</b>
</p>

@php $url = config('app.url').'news/latestnews' @endphp
<x-mail::button :url="$url">
Latest News
</x-mail::button>

Thanks,<br>
{{$server_config['serverName']}} Team
</x-mail::message>
