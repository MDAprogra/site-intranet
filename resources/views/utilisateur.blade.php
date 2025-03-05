<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Utilisateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-4">
            <div class="bg-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="font-bold text-xl underline">{{ __("Liste des utilisateurs") }}</h1>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <a href="{{ route('create-user') }}">
                            <button class="bg-[#83818f] text-white font-semibold py-2 px-4 rounded">
                                Créer un utilisateur
                            </button>
                        </a>
                    </div>
                    <div class="overflow-x-auto" x-data="{ open: false, selectedUserId: null, selectedUserRole: null, clickedRow: null }">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr @click="open = !open; selectedUserId = {{ $user->id }}; selectedUserRole = '{{ $user->role }}'; clickedRow = $event.currentTarget;"
                                    class="cursor-pointer hover:bg-gray-100 relative">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $user->role }}</div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <div x-show="open" class="absolute z-10 whitespace-nowrap" :style="'top: ' + (clickedRow ? clickedRow.offsetTop + clickedRow.offsetHeight : 0) + 'px; left: 15em;'">
                                <div class="mt-2 p-4 bg-white rounded-lg shadow-lg border border-gray-200">
                                    <div class="flex space-x-3">
                                        <a :href="'{{ route('access-indicateurs', ['user_id' => '']) }}' + selectedUserId">
                                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition duration-300 ease-in-out">
                                                Indicateurs
                                            </button>
                                        </a>
                                        @if(Auth::user()->role === 'admin')
                                            <a :href="editUserUrl(selectedUserId)" x-data="{
                    editUserUrl(userId) {
                        return `/utilisateur/${userId}/edit`;
                    },
                    deleteUserUrl(userId) {
                        return `/utilisateur/${userId}`;
                    },
                    confirmDelete(userId) {
                        if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
                            window.location.href = this.deleteUserUrl(userId);
                        }
                    }
                }">
                                                <button class="bg-yellow-500 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded transition duration-300 ease-in-out">
                                                    Modifier
                                                </button>
                                                <button
                                                    x-bind:disabled="selectedUserRole === 'admin'"
                                                    :class="selectedUserRole === 'admin' ? 'bg-red-300 text-gray-500 cursor-not-allowed' : 'bg-red-500 hover:bg-red-700 text-white'"
                                                    class="font-semibold py-2 px-4 rounded flex items-center transition duration-300 ease-in-out"
                                                    :onclick="selectedUserRole !== 'admin' ? 'confirmDelete(selectedUserId)' : ''"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Supprimer
                                                </button>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
