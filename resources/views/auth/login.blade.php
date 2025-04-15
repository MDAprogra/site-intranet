<x-guest-layout>
    <div class="max-w-md mx-auto bg-white p-8 rounded-xl shadow-md">
        <h2 class="text-3xl font-semibold text-gray-800 text-center mb-6">
            {{ __('Connexion à votre compte') }}
        </h2>

        <x-auth-session-status class="mb-4 text-sm text-green-600" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    {{ __('Adresse e-mail') }}
                </label>
                <input
                        id="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    {{ __('Mot de passe') }}
                </label>
                <input
                        id="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
            </div>

            <div class="flex items-center">
                <input
                        id="remember_me"
                        type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        name="remember"
                >
{{--                <label for="remember_me" class="ml-2 block text-sm text-gray-600">--}}
{{--                    {{ __('Se souvenir de moi') }}--}}
{{--                </label>--}}
            </div>

            <div class="flex items-center justify-between">
{{--                @if (Route::has('password.request'))--}}
{{--                    <a class="text-sm text-indigo-600 hover:underline" href="{{ route('password.request') }}">--}}
{{--                        {{ __('Mot de passe oublié ?') }}--}}
{{--                    </a>--}}
{{--                @endif--}}

                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Connexion') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>