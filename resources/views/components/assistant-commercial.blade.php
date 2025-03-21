<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Devis et Livraisons') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-md rounded-lg border border-gray-700">
                <div class="p-8 text-gray-200">

                    {{-- Bouton Retour --}}
                    <div class="mb-6">
                        <a href="{{ route('indicateur') }}"
                           class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-semibold py-2 px-4 rounded inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Retour
                        </a>
                    </div>

                    {{-- Encadré d'informations --}}
                    <div class="mb-8 p-4 bg-blue-900 border border-blue-800 rounded-md">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400 mr-2" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm text-blue-300">
                                Statistiques des devis et livraisons : visualisez le nombre de dossiers et de bons de livraison par semaine et par mois.
                            </span>
                        </div>
                    </div>

                    {{-- Conteneur pour les tableaux côte à côte --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Tableau Données par Semaine (Dossiers et BL) --}}
                        <div>
                            <h3 class="text-lg font-semibold mb-4 text-gray-200">Semaine</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-gray-800 border border-gray-700">
                                    <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-700 text-gray-300">Semaine</th>
                                        <th class="py-2 px-4 border-b border-gray-700 text-gray-300">Nombre de Dossiers</th>
                                        <th class="py-2 px-4 border-b border-gray-700 text-gray-300">Nombre de BL</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($NbDossierWeek as $semaine => $nombresDossiers)
                                        <tr>
                                            <td class="py-2 px-4 border-b border-gray-700">{{ $semaine }}</td>
                                            <td class="py-2 px-4 border-b border-gray-700">{{ array_sum($nombresDossiers) }}</td>
                                            <td class="py-2 px-4 border-b border-gray-700">{{ array_sum($NbBLWeek[$semaine] ?? [0]) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Tableau Données par Mois (Dossiers et BL) --}}
                        <div>
                            <h3 class="text-lg font-semibold mb-4 text-gray-200">Mois</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-gray-800 border border-gray-700">
                                    <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-700 text-gray-300">Mois</th>
                                        <th class="py-2 px-4 border-b border-gray-700 text-gray-300">Nombre de Dossiers</th>
                                        <th class="py-2 px-4 border-b border-gray-700 text-gray-300">Nombre de BL</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($NbDossierMonth as $mois => $nombresDossiers)
                                        <tr>
                                            <td class="py-2 px-4 border-b border-gray-700">{{ $mois }}</td>
                                            <td class="py-2 px-4 border-b border-gray-700">{{ array_sum($nombresDossiers) }}</td>
                                            <td class="py-2 px-4 border-b border-gray-700">{{ array_sum($NbBLMonth[$mois] ?? [0]) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
