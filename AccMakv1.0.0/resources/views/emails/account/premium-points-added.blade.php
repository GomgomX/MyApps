<x-mail::message>
    
<h3>Made a transaction!</h3>
<p>You have successfully purchased {{number_format($points)}} Premium Points for ${{$amount}} via {{$paying_method}} on <a href={{config('app.url')}}><b>{{$server_config['serverName']}}</b></a>. Spend them at the Shop Offer.</p>
</p>

@php $url = config('app.url').'shop/shopoffer' @endphp
<x-mail::button :url="$url">
Shop Offer
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
