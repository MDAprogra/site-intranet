<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __("Gestion de l'accès aux indicateurs") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-4">
            <a href="{{ route('utilisateur') }}">
                <button class="hover:underline text-gray-800 font-bold py-2 px-4 rounded">
                    &larr; Retour
                </button>
            </a>
            <div class="bg-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="font-bold text-xl underline">{{ __("Accès aux indicateurs") }}</h1>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('indicateurs.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                        @foreach($AllIndic as $indicateur)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="indicateurs[]"
                                       value="{{ $indicateur->id }}"
                                    {{ $UserIndic->contains($indicateur->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="indicateur_{{ $indicateur->id }}">
                                    {{ $indicateur->name}}
                                </label>
                            </div>
                        @endforeach

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded mt-4">Mettre à jour</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
