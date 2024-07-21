<x-layout :pageTitle="$pageTitle">
    <x-card class="p-10 max-w-lg mx-auto mt-24">
    <header class="text-center">
        <h2 class="text-2xl font-bold uppercase mb-1">
            Log In
        </h2>
        <p class="mb-4">Log in to post gigs</p>
    </header>

    <form method="POST" action="/users/authenticate">
        @csrf <!-- Cross-site request directive (directive means the function being used in blade files such as scrf or method('update')) with @ -->
        <div class="mb-6">
            <label for="email" class="inline-block text-lg mb-2"
                >Email</label
            >
            <input
                type="email"
                class="border border-gray-200 rounded p-2 w-full @error ('email')border-red-500 @enderror" {{-- we can use " error " or "if " condition like this --}}
                name="email"
                value="{{old('email')}}"
            />
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{$message}}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label
                for="password"
                class="inline-block text-lg mb-2"
            >
                Password
            </label>
            <input
                type="password"
                class="border border-gray-200 rounded p-2 w-full"
                name="password"
            />
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{$message}}</p>
            @enderror
        </div>

        <div class="mb-6">
            {{-- reCAPTCHA type Challenge (v2) -> I'm not a robot" Checkbox --}}
            {!! NoCaptcha::renderJs() !!}
            {!! NoCaptcha::display() !!}
            @error('g-recaptcha-response')
                <p class="text-red-500 text-xs mt-1">{{$message}}</p>   
            @enderror
        </div>

        <div class="mb-6">
            <button
                type="submit"
                class="bg-laravel text-white rounded py-2 px-4 hover:bg-black"
            >
                Sign In
            </button>
        </div>

        <div class="mt-8">
            <p>
                Don't have an account?
                <a href="/register" class="text-laravel"
                    >Register</a
                >
            </p>
        </div>
    </form>
</x-card>
</x-laylout>