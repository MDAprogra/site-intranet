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
                    <div class="overflow-x-auto" x-data="{ open: false, selectedUserId: null, selectedUserRole: null }">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nom
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rôle
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr @click="open = !open; selectedUserId = {{ $user->id }}; selectedUserRole = '{{ $user->role }}';"
                                    class="cursor-pointer hover:bg-gray-100">
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
                        </table>

                        <div x-show="open" class="mt-4 p-4 bg-gray-100 rounded">
                            <a :href="'{{ route('access-indicateurs', ['user_id' => '']) }}' + selectedUserId">
                                <button class="bg-[#8fce00] hover:bg-[#6aa84f] text-gray-900 font-bold py-2 px-4 rounded">
                                    Accès aux indicateurs
                                </button>
                            </a>
                            @if(Auth::user()->role === 'admin')
                                <template x-if="selectedUserRole !== 'admin'">
                                    <button class="bg-[#f44336] hover:bg-[#cc0000] text-gray-900 font-bold py-2 px-4 rounded" disabled>
                                        Supprimer
                                    </button>
                                </template>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
