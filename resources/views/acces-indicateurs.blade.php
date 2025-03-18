<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __("Gestion de l'accès aux indicateurs") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('utilisateur') }}" class="inline-flex items-center text-gray-200 hover:text-gray-600 transition duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="font-medium">Retour à la liste des utilisateurs</span>
                </a>
            </div>

            <div class="bg-gray-800 overflow-hidden shadow-md rounded-lg border border-gray-700">
                <div class="p-8 text-gray-200">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="font-bold text-xl text-gray-200">{{ __("Accès aux indicateurs pour") }} <span class="text-blue-400">{{ $user->name }}</span></h1>
                        <span class="px-3 py-1 bg-gray-700 text-gray-200 rounded-full text-sm">{{ $user->role }}</span>
                    </div>

                    <div class="bg-gray-700 p-6 rounded-lg shadow-sm" x-data="{
                        selectedCount: {{ $UserIndic->count() }},
                        totalCount: {{ $AllIndic->count() }},
                        search: '',
                        selectAll: {{ $UserIndic->count() == $AllIndic->count() ? 'true' : 'false' }},
                        toggleAll() {
                            const checkboxes = document.querySelectorAll('input[name=\'indicateurs[]\']');
                            checkboxes.forEach(checkbox => {
                                checkbox.checked = this.selectAll;
                            });
                            this.selectedCount = this.selectAll ? this.totalCount : 0;
                        },
                        updateCount() {
                            const checkboxes = document.querySelectorAll('input[name=\'indicateurs[]\']:checked');
                            this.selectedCount = checkboxes.length;
                            this.selectAll = this.selectedCount === this.totalCount;
                        }
                    }">
                        <div class="mb-4 flex items-center justify-between">
                            <div class="relative">
                                <input
                                    type="text"
                                    x-model="search"
                                    placeholder="Rechercher un indicateur..."
                                    class="px-4 py-2 pl-10 pr-4 rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-800 text-gray-200"
                                >
                                <div class="absolute left-3 top-2.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <button
                                    @click="selectAll = !selectAll; toggleAll()"
                                    type="button"
                                    class="flex items-center bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded transition duration-150"
                                >
                                    <template x-if="!selectAll">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                                        </svg>
                                    </template>
                                    <template x-if="selectAll">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </template>
                                    <span x-text="selectAll ? 'Désélectionner tout' : 'Sélectionner tout'"></span>
                                </button>
                                <span class="ml-4 text-sm text-gray-400">
                                    <span x-text="selectedCount"></span> sur <span x-text="totalCount"></span> sélectionnés
                                </span>
                            </div>
                        </div>

                        <form action="{{ route('indicateurs.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="user_id" value="{{ $user->id }}">

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                                @foreach($AllIndic as $indicateur)
                                    <div
                                        class="relative p-4 border border-gray-600 rounded-lg hover:bg-gray-700 transition duration-150"
                                        x-show="search === '' || '{{ strtolower($indicateur->name) }}'.includes(search.toLowerCase())"
                                    >
                                        <div class="flex items-center">
                                            <input
                                                id="indicateur_{{ $indicateur->id }}"
                                                class="form-checkbox h-5 w-5 text-blue-400 border-gray-600 rounded focus:ring-blue-500 focus:ring-2 transition-colors duration-200 ease-in-out shadow-sm hover:shadow-md focus:outline-none"
                                                type="checkbox"
                                                name="indicateurs[]"
                                                value="{{ $indicateur->id }}"
                                                {{ $UserIndic->contains($indicateur->id) ? 'checked' : '' }}
                                                @change="updateCount()"
                                                aria-label="Sélectionner l'indicateur {{ $indicateur->name }}"
                                                title="Sélectionner l'indicateur {{ $indicateur->name }}"
                                            >

                                            <label
                                                for="indicateur_{{ $indicateur->id }}"
                                                class="ml-3 block text-sm font-medium text-gray-200 cursor-pointer select-none"
                                            >
                                                {{ $indicateur->name }}
                                            </label>
                                        </div>
                                        @if(isset($indicateur->description) && !empty($indicateur->description))
                                            <p class="mt-2 text-xs text-gray-400 pl-8">{{ $indicateur->description }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex justify-end gap-4 border-t pt-6 border-gray-700">
                                <a href="{{ route('utilisateur') }}" class="px-4 py-2 border border-gray-600 rounded-md bg-gray-800 text-gray-200 hover:bg-gray-700 transition duration-150">
                                    Annuler
                                </a>
                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-gray-200 font-medium rounded-md shadow-sm transition duration-150 flex items-center"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
