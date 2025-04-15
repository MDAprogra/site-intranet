<x-app-layout>
    <div class="overflow-x-auto">
        @if ($v_BEPS->isEmpty())
            <div class="bg-yellow-800 border border-yellow-600 text-yellow-100 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">{{ __('Information!') }}</strong>
                <span class="block sm:inline">{{ __('Aucune donnée BEPS à afficher.') }}</span>
            </div>
        @else
            <table class="min-w-full divide-y divide-gray-700 shadow-md rounded-lg bg-gray-800 text-gray-100">
                <thead class="bg-gray-700 text-gray-300">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        {{ __('Nom Fournisseur') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        {{ __('Séquence Comptable') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        {{ __('Client') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        {{ __('Modèle') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        {{ __('Libellé Article') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider">
                        {{ __('Quantité Physique') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider">
                        {{ __('Prix de Vente / 1000') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        {{ __('Dernier Mouvement') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider">
                        {{ __('PMP') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider">
                        {{ __('Dernier Prix d\'Achat') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider">
                        {{ __('Valeur BEPS') }}
                    </th>
                </tr>
                </thead>
                <tbody class="bg-gray-900 divide-y divide-gray-800">
                @foreach ($v_BEPS as $beps)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $beps->fo_nom_1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $beps->st_seq_compt }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $beps->st_client }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $beps->st_modele }}</td>
                        <td class="px-6 py-4 text-sm text-gray-300">{{ $beps->st_lib_1_conso }}</td>
                        <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-gray-300">{{ $beps->st_q_physique }}</td>
                        <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-gray-300">{{ $beps->st_px_vente_le_1000 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $beps->st_dernier_mvt }}</td>
                        <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-gray-300">{{ $beps->st_pmp }}</td>
                        <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-gray-300">{{ $beps->st_dernier_prix_achat }}</td>
                        <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-gray-300">{{ number_format($beps->val_beps, 2, ',', ' ') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>