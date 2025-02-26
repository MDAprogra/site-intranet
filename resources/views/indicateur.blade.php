<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Indicateurs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-4">
            <div class="bg-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="font-bold text-xl underline">{{ __("Vos indicateurs disponibles") }}</h1>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($indicateurs as $indicateur)
                            @if($indicateur->component)
                                <a href="{{ route('indicateur.show', $indicateur->component) }}" class="block relative">
                                    <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition duration-300">
                                        <h3 class="font-semibold text-lg text-gray-800">{{ $indicateur->name }}</h3>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-1/2 right-4 -translate-y-1/2 w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </a>
                            @else
                                <div class="bg-white rounded-lg shadow-md p-4 relative">
                                    <h3 class="font-semibold text-lg text-gray-800">{{ $indicateur->name }}</h3>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-1/2 right-4 -translate-y-1/2 w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.getElementById('ajouterIndicateur').addEventListener('click', function (event) {
        event.preventDefault(); // Empêche le lien de recharger la page
        document.getElementById('formulaireAjout').style.display = 'block';
    });
</script>
