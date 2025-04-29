<?php

namespace App\Http\Controllers;

class LogController extends Controller
{
    public function index()
    {
        $path_BL = storage_path('logs/bons_livraison.log');
        $path_Contact = storage_path('logs/contacts.log');

        if (!file_exists($path_BL) || !file_exists($path_Contact)) {
            abort(404, 'Fichier de log introuvable.');
        }

        $logs_BL = array_reverse(file($path_BL));
        $logs_Contact = array_reverse(file($path_Contact));

        return view('Log', [
            'logs_BL' => $logs_BL,
            'logs_Contact' => $logs_Contact,
        ]);
    }
}
