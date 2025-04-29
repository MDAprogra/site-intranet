<x-app-layout>
    <div class="max-w-5xl mx-auto bg-gray-900 text-white p-6 rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold mb-6">📝 Logs – Bons de Livraison</h1>

        <div class="max-h-[75vh] overflow-y-auto divide-y divide-gray-700 rounded-lg border border-gray-700 bg-gray-800 p-4 scroll-smooth shadow-inner" id="logs-content">
            @forelse ($logs as $line)
                @php
                    $line = trim($line);
                    $level = 'info';
                    $icon = 'ℹ️';

                    if (str_contains($line, 'ERROR') || str_contains($line, 'Erreur')) {
                        $level = 'error';
                        $icon = '❌';
                    } elseif (str_contains($line, 'WARN') || str_contains($line, 'Attention')) {
                        $level = 'warning';
                        $icon = '⚠️';
                    } elseif (str_contains($line, 'SUCCESS') || str_contains($line, 'succès')) {
                        $level = 'success';
                        $icon = '✅';
                    }
                @endphp

                <div class="@if($level === 'error') text-red-400
                            @elseif($level === 'warning') text-yellow-300
                            @elseif($level === 'success') text-green-400
                            @else text-white @endif py-2">
                    <span class="mr-2">{{ $icon }}</span>
                    <span>{{ $line }}</span>
                </div>
            @empty
                <p class="text-center text-gray-400">Aucune ligne de log trouvée.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
