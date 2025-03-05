<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('PAO') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-10"> {{-- Agrandissement de l'encadré --}}
            <div class="bg-white overflow-hidden shadow-md rounded-lg border border-gray-200">
                <div class="p-9">

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
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-8"
                         role="alert">
                        <strong class="font-bold">Information:</strong>
                        <span class="block sm:inline">Statistiques de la PAO : en cliquant sur une semaine, les données qui la composent seront mises en surbrillance.</span>
                    </div>

                    <div class="grid grid-cols-2 w-full">
                        <div class="overflow-x-auto w-full">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Semaine
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nombre de dossiers
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $totalDossiers = 0;
                                    @endphp
                                    @foreach ($CompteDossierSemaine as $semaine => $nombre)
                                        @php
                                            $totalDossiers += $nombre;
                                        @endphp
                                        <tr data-semaine="{{ $semaine }}" onclick="highlightWeek('{{ $semaine }}')" class="cursor-pointer hover:bg-gray-100">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $semaine }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                                {{ $nombre }} dossiers
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Tableau des résultats --}}
                        <div class="overflow-x-auto w-full">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Dossier
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Client
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Salarié
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Deviseur
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Temps Prévu (Devis)
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Temps Réel
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Écart Temps
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="resultTableBody">
                                    @foreach ($EcartPAO as $pao)
                                        <tr data-semaine="{{ $pao->semaine }}">
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $pao->opre_dossier }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $pao->endv_cclient }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $pao->opre_date }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $pao->opre_sal }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $pao->endv_init_dev }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $pao->tps_devis }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $pao->tps_reel }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $pao->ecart_tps }}</td>
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
    <script>
        function highlightWeek(week) {
            const rows = document.getElementById('resultTableBody').querySelectorAll('tr');
            rows.forEach(row => {
                if (row.dataset.semaine === week) {
                    row.classList.add('bg-yellow-200');
                } else {
                    row.classList.remove('bg-yellow-200');
                }
            });
        }
    </script>
</x-app-layout>
