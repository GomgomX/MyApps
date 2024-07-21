{{-- @extends('layout')

@section('content') --}}
<x-layout :pageTitle="$pageTitle">
{{-- <h1>{{$heading}}</h1> --}}

@include('partials._hero')
@include('partials._search')

<div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">

@php
$listingsAreSet = false
@endphp
@if(count($listings) != 0)
@php
$listingsAreSet = true
@endphp
    @foreach($listings as $listing)  {{-- $listings is returned as a collection object --}}
        {{-- <a href="listings/{{$listing['id']}}">{{$listing['title']}}</a> --}}
        <x-listing-card :listing="$listing"/>
        {{-- <x-listing-card listing="Hello"/> --}}
    @endforeach
@else
    <h2>No listings found</h2>
@endif

</div>

@if($listingsAreSet)
    <div class="mt-6 p-4">
        {{$listings->links()}}
    </div>
@endif
{{-- 
- We can use classes inside blade.php files directly without defining namespaces
- We can use pluralize strings using Str helper (Str::class)  
-> Str::plural('listing', $listings->count())
--}}

</x-layout>
{{-- @endsection --}}