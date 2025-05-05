<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Logs de l\'application') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="font-semibold text-lg mb-4">{{ __('Logs d\'erreurs') }}</h3>
                    @if ($log_errors)
                        <pre class="bg-gray-100 dark:bg-gray-700 rounded-md p-4 overflow-auto max-h-96">
                            @foreach ($log_errors as $line)
                                <div>{{ $line }}</div>
                            @endforeach
                        </pre>
                        @if (count($log_errors) === 0)
                            <p class="text-gray-500 dark:text-gray-400">{{ __('Aucune erreur enregistrée.') }}</p>
                        @endif
                    @else
                        <p class="text-red-500">{{ __('Fichier de log d\'erreurs introuvable ou vide.') }}</p>
                    @endif
                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100 border-t dark:border-gray-700">
                    <h3 class="font-semibold text-lg mb-4">{{ __('Logs de succès') }}</h3>
                    @if ($log_succes)
                        <pre class="bg-gray-100 dark:bg-gray-700 rounded-md p-4 overflow-auto max-h-96">
                            @foreach ($log_succes as $line)
                                <div>{{ $line }}</div>
                            @endforeach
                        </pre>
                        @if (count($log_succes) === 0)
                            <p class="text-gray-500 dark:text-gray-400">{{ __('Aucun succès enregistré.') }}</p>
                        @endif
                    @else
                        <p class="text-red-500">{{ __('Fichier de log de succès introuvable.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>