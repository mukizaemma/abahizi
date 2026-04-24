<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <div class="mb-8 text-center">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white tracking-tight">
                {{ __('Welcome back') }}
            </h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                {{ __('Sign in to continue to') }}
                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $setting->company ?? config('app.name') }}</span>
            </p>
        </div>

        @php
            $isAdminLogin = isset($guard) && $guard === 'admin';
        @endphp

        <form method="POST" action="{{ $isAdminLogin ? route('admin.login') : route('login.store') }}" class="space-y-5">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full rounded-lg" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div>
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full rounded-lg" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="flex items-center justify-between pt-1">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium text-center sm:text-left" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @else
                    <span></span>
                @endif

                <x-button class="w-full sm:w-auto justify-center rounded-lg px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500 text-white dark:bg-indigo-500 dark:hover:bg-indigo-600 shadow-lg shadow-indigo-500/25">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
