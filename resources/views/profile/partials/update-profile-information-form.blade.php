<section>
    <header>
        <h2 class="text-lg font-medium text-gray-200">
            {{ __('Informations du profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __("Mettez à jour les informations de profil et l'adresse e-mail de votre compte.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nom')" class="text-gray-200"/>
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200 focus:border-blue-500 focus:ring-blue-500" :value="old('name', $user->name)"
                          required autofocus autocomplete="name"/>
            <x-input-error class="mt-2 text-red-500" :messages="$errors->get('name')"/>
        </div>

        <div>
            @if(Auth::user()->role == 'admin')
                <x-input-label for="email" :value="__('Email')" class="text-gray-200"/>
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200 focus:border-blue-500 focus:ring-blue-500"
                              :value="old('email', $user->email)" required autocomplete="username"/>
                <x-input-error class="mt-2 text-red-500" :messages="$errors->get('email')"/>
            @endif
            {{--            27/02/2025 -- Mise en commentaire car pas actif--}}
            {{--            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())--}}
            {{--                <div>--}}
            {{--                    <p class="text-sm mt-2 text-gray-400">--}}
            {{--                        {{ __("Votre email n'est pas vérifiée") }}--}}

            {{--                        <button form="send-verification" class="underline text-sm text-gray-400 hover:text-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">--}}
            {{--                            {{ __('Cliqué ici pour la vérifié') }}--}}
            {{--                        </button>--}}
            {{--                    </p>--}}

            {{--                    @if (session('status') === 'verification-link-sent')--}}
            {{--                        <p class="mt-2 font-medium text-sm text-green-400">--}}
            {{--                            {{ __('Un nouveau lien de vérification a été envoyé à votre adresse e-mail.') }}--}}
            {{--                        </p>--}}
            {{--                    @endif--}}
            {{--                </div>--}}
            {{--            @endif--}}
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-blue-600 hover:bg-blue-700 text-gray-200">{{ __('Enregistrer') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-400"
                >{{ __('Enregistré !') }}</p>
            @endif
        </div>
    </form>
</section>
