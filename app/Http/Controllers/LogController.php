<?php

namespace App\Http\Controllers;

class LogController extends Controller
{
    public function bonsLivraison()
    {
        $path = storage_path('logs/bons_livraison.log');

        if (!file_exists($path)) {
            abort(404, 'Fichier de log introuvable.');
        }

        $logs = array_reverse(file($path)); // Lignes inversées (les plus récentes en haut)

        return view('Log', compact('logs'));
    }
}
