<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Gestion de la PAO') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-10 bg-white border-b border-gray-200">

                    {{-- Bouton Retour --}}
                    <div class="mb-6">
                        <a href="{{ route('indicateur') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Retour aux Indicateurs
                        </a>
                    </div>

                    {{-- Encadré d'informations --}}
                    <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-md">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm text-blue-700">
                                Cliquez sur une semaine pour mettre en évidence les données correspondantes.
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Semaine
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dossiers
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
                                            {{ $nombre }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 table-auto border-collapse">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                        Dossier
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                        Prévu
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                        Réel
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                        Écart
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 text-sm" id="resultTableBody"> @foreach ($EcartPAO as $pao)
                                    <tr data-semaine="{{ $pao->semaine }}" class="hover:bg-gray-50 cursor-pointer" onclick="toggleDetails('details-{{ $loop->index }}')">
                                        <td class="px-6 py-4 whitespace-nowrap truncate">{{ $pao->opre_dossier }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $pao->opre_date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $pao->tps_devis }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $pao->tps_reel }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($pao->ecart_tps < 0)
                                                <span class="text-red-500">{{ $pao->ecart_tps }}</span>
                                            @else
                                                <span class="text-green-500">{{ $pao->ecart_tps }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" class="p-4 hidden" id="details-{{ $loop->index }}">
                                            <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                                                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                                    Informations supplémentaires pour le dossier {{ $pao->opre_dossier }}
                                                </h3>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <p class="text-sm text-gray-700">
                                                            <span class="font-medium">Client:</span> {{ $pao->endv_cclient }}
                                                        </p>
                                                        <p class="text-sm text-gray-700">
                                                            <span class="font-medium">Date:</span> {{ $pao->opre_date }}
                                                        </p>
                                                        <p class="text-sm text-gray-700">
                                                            <span class="font-medium">Salarié:</span> {{ $pao->opre_sal }}
                                                        </p>
                                                        <p class="text-sm text-gray-700">
                                                            <span class="font-medium">Deviseur:</span> {{ $pao->endv_init_dev }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm text-gray-700">
                                                            <span class="font-medium">Temps Prévu:</span> {{ $pao->tps_devis }}
                                                        </p>
                                                        <p class="text-sm text-gray-700">
                                                            <span class="font-medium">Temps Réel:</span> {{ $pao->tps_reel }}
                                                        </p>
                                                        <p class="text-sm text-gray-700">
                                                            <span class="font-medium">Écart:</span>
                                                            @if ($pao->ecart_tps < 0)
                                                                <span class="text-red-500">{{ $pao->ecart_tps }}</span>
                                                            @else
                                                                <span class="text-green-500">{{ $pao->ecart_tps }}</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
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
                    row.classList.add('bg-yellow-100');
                } else {
                    row.classList.remove('bg-yellow-100');
                }
            });
        }

        function toggleDetails(id) {
            const details = document.getElementById(id);
            details.classList.toggle('hidden');
        }
    </script>
</x-app-layout>
