<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Modifier un utilisateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-4">
            <div class="bg-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex justify-between items-center">
                    <h1 class="font-bold text-xl underline">{{ __("Modifier l'utilisateur") }}</h1>
                    <a href="{{ route('utilisateur') }}" class="text-red-600 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('utilisateur.update', ['user' => $user->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="sr-only">Nom</label>
                            <input type="text" name="name" id="name" placeholder="Nom" value="{{ $user->name }}"
                                   class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('name') border-red-500 @enderror">
                            @error('name')
                            <div class="text-red-500 mt-2 text-sm">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" placeholder="Email" value="{{ $user->email }}"
                                   class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('email') border-red-500 @enderror">
                            @error('email')
                            <div class="text-red-500 mt-2 text-sm">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        @if($user->role != 'admin')
                            <div class="mb-4">
                                <label for="role" class="sr-only">Rôle</label>
                                <select name="role" id="role" class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('role') border-red-500 @enderror">
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrateur</option>
                                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Utilisateur</option>
                                </select>
                                @error('role')
                                <div class="text-red-500 mt-2 text-sm">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        @endif

                        <div>
                            <button type="submit"
                                    class="bg-[#a6b9a8] hover:bg-[#607066] text-white font-bold py-2 px-4 rounded">
                                Modifier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
