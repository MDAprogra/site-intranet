<x-app-layout>
    @vite('resources/js/app.js')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Indicateurs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-4">
            <div class="bg-gray-700 overflow-hidden shadow-lg rounded-lg">
                <div class="p-8 text-gray-200">
                    <h1 class="font-bold text-3xl mb-6 text-gray-100">
                        {{ __("Vos indicateurs disponibles") }}
                    </h1>

                    <ul class="space-y-4">
                        @foreach ($indicateurs as $indicateur)
                            <li>
                                <a href="{{ route($indicateur->component) }}"
                                   class="block px-6 py-4 bg-gray-800 hover:bg-gray-900 rounded-lg transition duration-300 ease-in-out shadow-md">
                                    <span class="text-gray-100 font-medium">{{ $indicateur->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
