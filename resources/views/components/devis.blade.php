<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Devis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md rounded-lg border border-gray-200">
                <div class="p-8">

                    {{-- Bouton Retour --}}
                    <div class="mb-6">
                        <a href="{{ route('indicateur') }}"
                           class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Retour
                        </a>
                    </div>
                    {{-- Encadré d'informations --}}
                    <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-md">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm text-blue-700">
                                Voici les statistiques des devis. Vous pouvez consulter le nombre de devis par semaine et par mois. Les données par deviseur arriveront bientôt.
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Table Devis par semaine --}}
                        <div>
                            <h2 class="font-bold text-2xl mb-4">
                                {{ __("Devis par semaine") }}
                            </h2>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __("Semaine") }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __("Nombre de devis") }}
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($DevisSemaine as $semaine => $nombre)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $semaine }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $nombre }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Table Devis par mois --}}
                        <div>
                            <h2 class="font-bold text-2xl mb-4">
                                {{ __("Devis par mois") }}
                            </h2>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __("Mois") }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __("Nombre de devis") }}
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($DevisMois as $mois => $nombre)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $mois }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $nombre }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div> {{-- Fin de la grille --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const loadMoreButton = document.getElementById('loadMore');
        if (loadMoreButton) {
            loadMoreButton.addEventListener('click', function () {
                // Implémentez ici la logique pour charger plus d'éléments
                // Vous pouvez utiliser AJAX pour récupérer les données supplémentaires
                // et les ajouter au tableau.
                alert('Fonctionnalité "Afficher plus" à implémenter.');
            });
        }
    });
</script>
