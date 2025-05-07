<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Profil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-gray-800 shadow-md rounded-lg border border-gray-700">
                <div class="max-w-xl text-gray-200">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-gray-800 shadow-md rounded-lg border border-gray-700">
                <div class="max-w-xl text-gray-200">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{--            27/02/2025 -- Mis en commentaire pour empêcher les utilisateurs de supprimer leur compte--}}

            {{--            <div class="p-4 sm:p-8 bg-gray-800 shadow-md rounded-lg border border-gray-700">--}}
            {{--                <div class="max-w-xl text-gray-200">--}}
            {{--                    @include('profile.partials.delete-user-form')--}}
            {{--                </div>--}}
            {{--            </div>--}}
        </div>
    </div>
</x-app-layout>
