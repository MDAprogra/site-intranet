<x-app-layout>
    @vite('resources/js/app.js')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Indicateurs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-4">
            <div class="bg-gray-800 overflow-hidden shadow-md rounded-lg">
                <div class="p-8 text-gray-200">
                    <h1 class="font-bold text-2xl mb-6 text-gray-300">
                        {{ __("Vos indicateurs disponibles") }}
                    </h1>

                    <ul class="space-y-4">
                        @foreach ($indicateurs as $indicateur)
                            <li>
                                <a href="{{ route($indicateur->component) }}"
                                   class="block px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-md transition duration-300 ease-in-out">
                                    <span class="text-gray-200 font-medium">{{ $indicateur->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
