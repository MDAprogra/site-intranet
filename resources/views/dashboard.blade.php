<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Indicateurs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-4">
            <div class="bg-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="font-bold text-xl underline">{{ __("Vos indicateurs disponibles") }}</h1>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <ul class="hover:border-gray-800">Indic</ul>
                    <ul>Indic</ul>
                    <ul>Indic</ul>
                    <ul>Indic</ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
