{{-- @extends('layout')

@section('content') --}}
<x-layout :pageTitle="$pageTitle">
@include('partials._search')
{{-- <h2>{{$value['title']}}</h2>
<p>{{$value['desc']}}</p> --}}
        <a href="/" class="inline-block text-black ml-4 mb-4"><i class="fa-solid fa-arrow-left"></i>Back</a>
            <div class="mx-4">
                <x-card>
                    <div class="flex flex-col items-center justify-center text-center">
                        <img
                            class="w-48 mr-6 mb-6"
                            src="{{$value->logo ? asset('storage/'.$value->logo) : asset('images/no-image.png')}}"
                            alt=""
                        />

                        <h3 class="text-2xl mb-2">{{$value->title}}</h3>
                        <div class="text-xl font-bold mb-4">{{$value->company}}</div>
                        <x-listing-tags :tagsField="$value->tags"/>
                        <div class="text-lg my-4">
                            <i class="fa-solid fa-location-dot"></i>{{$value->location}}
                        </div>
                        <div class="border border-gray-200 w-full mb-6"></div>
                        <div>
                            <h3 class="text-3xl font-bold mb-4">
                                Job Description
                            </h3>
                            <div class="text-lg space-y-6">{{$value->desc}}
                                <a href="mailto:{{$value->companyEmail}}" class="block bg-laravel text-white mt-6 py-2 rounded-xl hover:opacity-80"><i class="fa-solid fa-envelope"></i>Contact Employer</a>

                                <a
                                    href="{{$value->website}}"
                                    target="_blank"
                                    class="block bg-black text-white py-2 rounded-xl hover:opacity-80"
                                    ><i class="fa-solid fa-globe"></i> Visit
                                    Website</a
                                >
                            </div>
                        </div>
                    </div>
                </x-card>
				@php $user = auth()->user(); @endphp
				@if($user && $user->id == $value->user_id)
					<x-card class="mt-4 p-2 flex space-x-6">
						<a href="/listings/{{$value->id}}/edit">
							<i class="fa-solid fa-pencil"></i> Edit
						</a>
						{{-- 
						- We can also use route helper to inject the url to the form by name that's defined in the route file (web.php)
						-> form="{{route('listing')}}"
						- If we have a wildcard or a parameter to pass we can insert it as well
						-> form="{{route('listing', $value->id)}}" or form="{{route('listing', $value)}}"
						--}}
						<form method="POST" form="/listings/{{$value->id}}">
							@csrf
							@method('DELETE')
							<button class="text-red-500"><i class="fa-solid fa-trash"></i> Delete</button>
						</form>
					</x-card>
				@endif
            </div>
 </x-layout>
{{-- @endsection --}}