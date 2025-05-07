<x-app-layout>
    @vite('resources/js/app.js')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-xl font-semibold mb-4 dark:text-gray-300">{{ __('Accès Rapide aux Indicateurs') }}</h2>

                    <ul class="space-y-4">
                        <li>
                            <a href="{{ route('beps') }}"
                               class="flex items-center p-3 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150">
                                <svg class="w-6 h-6 text-indigo-500 mr-4 dark:text-indigo-400" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 00-2-2V5a2 2 0 002-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 002 2h1m-6-4l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <span class="font-semibold dark:text-gray-200">{{ __('BEPS') }}</span>
                                <span class="ms-2 text-gray-500 dark:text-gray-400">- {{ __('Consultez les BEPS') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('conso-papier') }}"
                               class="flex items-center p-3 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150">
                                <svg class="w-6 h-6 text-green-500 mr-4 dark:text-green-400" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                <span class="font-semibold dark:text-gray-200">{{ __('Consommation papier') }}</span>
                                <span class="ms-2 text-gray-500 dark:text-gray-400">- {{ __('Accédez aux consommation de papier') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>