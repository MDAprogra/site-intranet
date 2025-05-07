<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-white tracking-tight">
            {{ __('Logs de l\'application') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- En-tête avec onglets de navigation -->
            <div class="flex flex-wrap gap-4 mb-2">
                <button onclick="showTab('errors')"
                        class="px-4 py-2 rounded-lg font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2
                        bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-800/50">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728"/>
                        </svg>
                        {{ __('Erreurs') }}
                        @if($log_errors && count($log_errors) > 0)
                            <span class="ml-2 bg-red-200 text-red-800 dark:bg-red-800 dark:text-red-200 text-xs font-semibold px-2 py-0.5 rounded-full">
                                {{ count($log_errors) }}
                            </span>
                        @endif
                    </div>
                </button>

                <button onclick="showTab('success')"
                        class="px-4 py-2 rounded-lg font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2
                        bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900/30 dark:text-green-300 dark:hover:bg-green-800/50">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ __('Succès') }}
                        @if($log_succes && count($log_succes) > 0)
                            <span class="ml-2 bg-green-200 text-green-800 dark:bg-green-800 dark:text-green-200 text-xs font-semibold px-2 py-0.5 rounded-full">
                                {{ count($log_succes) }}
                            </span>
                        @endif
                    </div>
                </button>
            </div>

            <!-- Conteneur pour les logs avec effet de carte flottante -->
            <div class="bg-white dark:bg-gray-900 shadow-xl rounded-xl overflow-hidden border dark:border-gray-700 transition-all">
                <!-- En-tête avec titre et options -->
                <div class="border-b dark:border-gray-700 p-4 flex justify-between items-center">
                    <h3 id="current-tab-title" class="text-xl font-semibold text-gray-800 dark:text-white flex items-center">
                        <span id="tab-icon" class="mr-2">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728"/>
                            </svg>
                        </span>
                        <span id="tab-text">{{ __("Erreurs") }}</span>
                    </h3>
                    <div class="flex items-center space-x-2">
                        <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-500 dark:text-gray-400"
                                title="{{ __('Rafraîchir') }}" onclick="refreshLogs()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                        <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-500 dark:text-gray-400"
                                title="{{ __('Télécharger') }}" onclick="downloadLogs()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contenu des logs -->
                <div class="p-6">
                    <!-- Section Erreurs -->
                    <div id="errors-tab" class="log-section">
                        @if ($log_errors)
                            @if (count($log_errors) > 0)
                                <div class="bg-gray-100 dark:bg-gray-800 text-sm font-mono rounded-lg p-4 overflow-auto max-h-96 text-red-600 dark:text-red-300 whitespace-pre-wrap">
                                    @foreach ($log_errors as $line)
                                        {{ e($line) }}
                                    @endforeach
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center py-12 text-center">
                                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('Aucune erreur enregistrée.') }}</p>
                                </div>
                            @endif
                        @else
                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                <p class="text-red-500">{{ __('Fichier de log d\'erreurs introuvable ou vide.') }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Section Succès -->
                    <div id="success-tab" class="log-section hidden">
                        @if ($log_succes)
                            @if (count($log_succes) > 0)
                                <div class="bg-gray-100 dark:bg-gray-800 text-sm font-mono rounded-lg p-4 overflow-auto max-h-96 text-green-600 dark:text-green-300 whitespace-pre-wrap">
                                    @foreach ($log_succes as $line)
                                        {{ e($line) }}
                                    @endforeach
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center py-12 text-center">
                                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('Aucun succès enregistré.') }}</p>
                                </div>
                            @endif
                        @else
                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                <p class="text-red-500">{{ __('Fichier de log de succès introuvable.') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fonction pour afficher l'onglet sélectionné
        function showTab(tabName) {
            // Masquer toutes les sections
            document.querySelectorAll('.log-section').forEach(tab => {
                tab.classList.add('hidden');
            });

            // Afficher la section sélectionnée
            document.getElementById(tabName + '-tab').classList.remove('hidden');

            // Mettre à jour l'en-tête
            const tabTitle = document.getElementById('current-tab-title');
            const tabIcon = document.getElementById('tab-icon');
            const tabText = document.getElementById('tab-text');

            if (tabName === 'errors') {
                tabText.textContent = "{{ __("Erreurs") }}";
                tabIcon.innerHTML = `<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728"/>
                </svg>`;
            } else {
                tabText.textContent = "{{ __('Succès') }}";
                tabIcon.innerHTML = `<svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>`;
            }
        }

        // Fonction pour rafraîchir les logs
        function refreshLogs() {
            // Ici, vous pouvez ajouter la logique pour rafraîchir les logs via AJAX
            window.location.reload();
        }

        // Fonction pour télécharger les logs
        function downloadLogs() {
            // Ici, vous pouvez ajouter la logique pour télécharger les logs
            // Exemple simple de téléchargement du contenu visible
            const activeTab = document.querySelector('.log-section:not(.hidden)');
            const logContent = activeTab.querySelector('div').textContent;
            const type = activeTab.id.includes('error') ? 'erreurs' : 'succes';

            const blob = new Blob([logContent], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);

            const a = document.createElement('a');
            a.href = url;
            a.download = `logs_${type}_${new Date().toISOString().slice(0,10)}.txt`;
            document.body.appendChild(a);
            a.click();

            // Nettoyer
            setTimeout(() => {
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }, 0);
        }
    </script>
</x-app-layout>