@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto bg-gray-900 text-white p-6 rounded-xl shadow-lg">
        <h1 class="text-2xl font-bold mb-4">Logs – Bons de Livraison</h1>
        <div class="max-h-[70vh] overflow-y-auto space-y-1 font-mono text-sm">
            @foreach ($logs as $line)
                <div class="border-b border-gray-700 pb-1">{{ $line }}</div>
            @endforeach
        </div>
    </div>
@endsection
