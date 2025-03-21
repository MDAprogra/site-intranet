<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Devis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if ($NbDev !== null)
                        <h1 class="font-bold text-xl underline">
                            {{ __("Chiffre du mois précédent (") . $Mois . ") : " }}
                            <span class="text-xl">{{ $NbDev . " Devis" }}</span>
                        </h1>
                    @else
                        <p>{{ __("Aucun devis trouvé pour le mois précédent.") }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
