<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 text-sm font-medium text-green-600">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" class="text-sm font-medium text-gray-700" />
                <x-input id="email" class="block w-full mt-1 px-4 py-3 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="contoh@email.com" />
            </div>

            <div>
                <x-label for="password" value="{{ __('Password') }}" class="text-sm font-medium text-gray-700" />
                <x-input id="password" class="block w-full mt-1 px-4 py-3 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="text-sm text-gray-600 ms-2">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 underline rounded-md hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Lupa password Anda?') }}
                    </a>
                @endif
            </div>

            <div>
                <x-button class="w-full justify-center py-3 text-base font-semibold rounded-lg">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    {{ __('Belum punya akun?') }}
                    <a href="{{ route('register') }}" class="font-medium text-indigo-600 underline hover:text-indigo-500">
                        {{ __('Daftar di sini') }}
                    </a>
                </p>
            </div>
        @endif

    </x-authentication-card>
</x-guest-layout>
