<section>
    <header>
        <h2 class="text-lg font-medium text-gray-200">
            {{ __('Mise à jour du mot de passe') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __('Assurez-vous que votre compte utilise un mot de passe long et aléatoire pour rester en sécurité.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Mot de passe actuel')" class="text-gray-200"/>
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200 focus:border-blue-500 focus:ring-blue-500" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-red-500" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Nouveau mot de passe')" class="text-gray-200"/>
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200 focus:border-blue-500 focus:ring-blue-500" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-red-500" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmez votre nouveau mot de passe')" class="text-gray-200"/>
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200 focus:border-blue-500 focus:ring-blue-500" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-red-500" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-blue-600 hover:bg-blue-700 text-gray-200">{{ __('Enregistrer') }}</x-primary-button>

            @if (session('status') === 'password-updated')
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
