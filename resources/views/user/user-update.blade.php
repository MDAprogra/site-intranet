<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Modifier un utilisateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
{{--            <div class="mb-6">--}}
{{--                <a href="{{ route('utilisateur') }}" class="inline-flex items-center text-gray-200 hover:text-gray-900 transition duration-150">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />--}}
{{--                    </svg>--}}
{{--                    <span class="font-medium">Retour à la liste</span>--}}
{{--                </a>--}}
{{--            </div>--}}

            <div class="bg-gray-800 overflow-hidden shadow-md rounded-lg border border-gray-700">
                <div class="p-8 text-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="font-bold text-xl text-gray-200">{{ __("Modifier l'utilisateur") }}</h1>
                            <p class="text-gray-400 text-sm mt-1">Modifiez les informations de {{ $user->name }}</p>
                        </div>
                        <a href="{{ route('utilisateur') }}" class="text-gray-400 hover:text-gray-200 transition duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </div>

                    <form method="POST" action="{{ route('utilisateur.update', ['user' => $user->id]) }}" x-data="{ processing: false }">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Nom</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        placeholder="Nom de l'utilisateur"
                                        value="{{ $user->name }}"
                                        class="pl-10 bg-gray-700 border border-gray-600 text-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('name') border-red-500 @enderror"
                                        required
                                    >
                                </div>
                                @error('name')
                                <div class="text-red-500 mt-2 text-sm flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        placeholder="Adresse email"
                                        value="{{ $user->email }}"
                                        class="pl-10 bg-gray-700 border border-gray-600 text-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('email') border-red-500 @enderror"
                                        required
                                    >
                                </div>
                                @error('email')
                                <div class="text-red-500 mt-2 text-sm flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="bg-gray-700 p-4 rounded-lg border border-gray-600">
                                <div class="flex items-center">
                                    <div class="mr-4">
                                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-800">
                                            @if($user->role === 'admin')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            @endif
                                        </span>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-200">Rôle utilisateur</h3>
                                        @if($user->role === 'admin')
                                            <p class="text-sm text-gray-400">Cet utilisateur est administrateur et son rôle ne peut pas être modifié</p>
                                        @else
                                            <p class="text-sm text-gray-400">Sélectionnez le niveau d'accès approprié pour cet utilisateur</p>
                                        @endif
                                    </div>
                                </div>

                                @if($user->role != 'admin')
                                    <div class="mt-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="relative">
                                                <input type="radio" name="role" id="role_user" value="user" class="hidden peer" {{ $user->role === 'user' ? 'checked' : '' }} required>
                                                <label for="role_user" class="block p-4 w-full text-gray-200 bg-gray-800 border border-gray-700 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:text-blue-600 hover:bg-gray-700">
                                                    <div class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                        <div>
                                                            <div class="font-semibold">Utilisateur standard</div>
                                                            <div class="text-sm text-gray-400">Accès limité aux fonctionnalités de base</div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="relative">
                                                <input type="radio" name="role" id="role_admin" value="admin" class="hidden peer" {{ $user->role === 'admin' ? 'checked' : '' }}>
                                                <label for="role_admin" class="block p-4 w-full text-gray-200 bg-gray-800 border border-gray-700 rounded-lg cursor-pointer peer-checked:border-purple-600 peer-checked:text-purple-600 hover:bg-gray-700">
                                                    <div class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                        </svg>
                                                        <div>
                                                            <div class="font-semibold">Administrateur</div>
                                                            <div class="text-sm text-gray-400">Accès complet à toutes les fonctionnalités</div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        @error('role')
                                        <div class="text-red-500 mt-2 text-sm flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                @else
                                    <input type="hidden" name="role" value="admin">
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-700">
                            <a href="{{ route('utilisateur') }}" class="inline-flex items-center px-4 py-2 border border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-200 bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Annuler
                            </a>
                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-200 bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150"
                                :class="{ 'opacity-75 cursor-not-allowed': processing }"
                                :disabled="processing"
                                @click="processing = true"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span x-text="processing ? 'Enregistrement...' : 'Enregistrer les modifications'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
