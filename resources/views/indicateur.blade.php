<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Indicateurs') }}
        </h2>
    </x-slot>

    <div id="app" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-4">
            <div class="bg-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="font-bold text-xl underline">{{ __("Vos indicateurs disponibles") }}</h1>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <button v-for="indicateur in indicateurs"
                                @click="selectedComponent = indicateur.component"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md">
                            Voir @{{ indicateur.name }}
                        </button>
                    </div>

                    <!-- Affichage du composant sélectionné -->
                    <div class="mt-6">
                        <DynamicComponent v-if="selectedComponent" :component-name="selectedComponent" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
