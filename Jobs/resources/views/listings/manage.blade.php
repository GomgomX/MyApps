<x-layout :pageTitle="$pageTitle">
<x-card class="p-10">
    <header>
        <h1
            class="text-3xl text-center font-bold my-6 uppercase"
        >
            Manage Gigs
        </h1>
    </header>
    @php
        $thereAreListings = false;
    @endphp
    <table class="w-full table-auto rounded-sm">
        <tbody>
        @if(!$listings->isEmpty())
            @php
                $thereAreListings = true;
            @endphp
            @foreach($listings as $listing)
                <tr class="border-gray-300">
                    <td
                        class="px-4 py-8 border-t border-b border-gray-300 text-lg"
                    >
                        <a href="/listings/{{$listing->id}}">
                            {{$listing->title}}
                        </a>
                    </td>
                    <td
                        class="px-4 py-8 border-t border-b border-gray-300 text-lg"
                    >
                        <a
                            href="/listings/{{$listing->id}}/edit"
                            class="text-blue-400 px-6 py-2 rounded-xl"
                            ><i
                                class="fa-solid fa-pen-to-square"
                            ></i>
                            Edit</a
                        >
                    </td>
                    <td
                        class="px-4 py-8 border-t border-b border-gray-300 text-lg"
                    >
                        <form method="POST" action="/listings/{{$listing->id}}">
                            {{-- Cross-site request forgery preventation can also be defined as <input type="hidden" name="_token" value="{{ scrf_token() }}"> --}}
                            @csrf
                            {{-- When defining PUT, DELETE or PATCH routes that are called from HTML, we can use <input type="hidden" name="_method" value="patch"> --}}
                            @method('DELETE')
                            <button class="text-red-600">
                                <i
                                    class="fa-solid fa-trash-can"
                                ></i>
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="border-gray-300">
                <td
                    class="px-4 py-8 border-t border-b border-gray-300 text-lg"
                >
                    <p class="text-center">No listings found</p>
                </td>
            </tr>
        @endif
        </tbody>
    </table>
    @if($thereAreListings)   
        <div class="mt-6 p-4">
            {{$listings->links()}}
        </div>
    @endif
</x-card>
</x-layout>