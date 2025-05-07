<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Utilisateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-4">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-gray-700 border-b border-gray-600 text-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <a href="{{ route('create-user') }}" aria-label="Créer un nouvel utilisateur">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded flex items-center transition duration-300 ease-in-out">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Créer un utilisateur
                            </button>
                        </a>
{{--                        <div class="relative">--}}
{{--                            <input type="text" placeholder="Rechercher..." class="bg-gray-800 text-gray-200 border border-gray-600 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">--}}
{{--                        </div>--}}
                    </div>

                    <div class="overflow-x-auto" x-data="userTable()">
                        <table class="min-w-full divide-y divide-gray-600 table-fixed">
                            <thead class="bg-gray-600">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider w-1/4">
                                    Nom
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider w-1/3">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider w-1/4">
                                    Rôle
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider w-1/6">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-600">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-700 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-200">{{ $user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-400">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $user->role === 'admin' ? 'bg-purple-800 text-purple-200' :
                                                   ($user->role === 'manager' ? 'bg-green-800 text-green-200' :
                                                   'bg-blue-800 text-blue-200') }}">
                                                {{ $user->role }}
                                            </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" @click.outside="open = false" class="text-gray-400 hover:text-gray-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                </svg>
                                            </button>
                                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                                 x-transition:enter-start="transform opacity-0 scale-95"
                                                 x-transition:enter-end="transform opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-75"
                                                 x-transition:leave-start="transform opacity-100 scale-100"
                                                 x-transition:leave-end="transform opacity-0 scale-95"
                                                 class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-gray-700 ring-1 ring-black ring-opacity-5 z-10">
                                                <div class="py-1">
                                                    <a href="{{ route('access-indicateurs', ['user_id' => $user->id]) }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-600">
                                                        <div class="flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                            </svg>
                                                            Indicateurs
                                                        </div>
                                                    </a>
                                                    @if(Auth::user()->role === 'admin')
                                                        <a href="/utilisateur/{{ $user->id }}/edit" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-600">
                                                            <div class="flex items-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                                Modifier
                                                            </div>
                                                        </a>
                                                        <button
                                                            @click="confirmDelete({{ $user->id }})"
                                                            {{ $user->role === 'admin' ? 'disabled' : '' }}
                                                            class="w-full text-left px-4 py-2 text-sm {{ $user->role === 'admin' ? 'text-gray-500 cursor-not-allowed' : 'text-red-400 hover:bg-gray-600' }}">
                                                            <div class="flex items-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                                Supprimer
                                                            </div>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="py-4 flex items-center justify-between">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-gray-300 bg-gray-800 hover:bg-gray-700">
                                    Précédent
                                </a>
                                <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-gray-300 bg-gray-800 hover:bg-gray-700">
                                    Suivant
                                </a>
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-400">
                                        Affichage de <span class="font-medium">1</span> à <span class="font-medium">10</span> sur <span class="font-medium">{{ count($users) }}</span> utilisateurs
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-600 bg-gray-800 text-sm font-medium text-gray-400 hover:bg-gray-700">
                                            <span class="sr-only">Précédent</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                        <a href="#" aria-current="page" class="relative inline-flex items-center px-4 py-2 border border-gray-600 bg-gray-700 text-sm font-medium text-gray-200">
                                            1
                                        </a>
                                        <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-600 bg-gray-800 text-sm font-medium text-gray-400 hover:bg-gray-700">
                                            2
                                        </a>
                                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-600 bg-gray-800 text-sm font-medium text-gray-400 hover:bg-gray-700">
                                            <span class="sr-only">Suivant</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div x-data="{ showModal: false, userId: null }" x-show="showModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-200" id="modal-title">
                                Confirmation de suppression
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-400">
                                    Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action ne peut pas être annulée.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form :action="'/utilisateur/' + userId" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Supprimer
                        </button>
                    </form>
                    <button @click="showModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-600 shadow-sm px-4 py-2 bg-gray-800 text-base font-medium text-gray-200 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function userTable() {
        return {
            confirmDelete(userId) {
                // Alpine.js peut accéder à d'autres composants dans la page
                document.querySelector('[x-data="{ showModal: false, userId: null }"]').__x.$data.userId = userId;
                document.querySelector('[x-data="{ showModal: false, userId: null }"]').__x.$data.showModal = true;
            }
        }
    }
</script>
