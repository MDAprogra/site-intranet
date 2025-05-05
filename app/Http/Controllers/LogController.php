<?php

namespace App\Http\Controllers;

class LogController extends Controller
{
    public function index()
    {
//        $path_BL = storage_path('logs/bons_livraison.log');
//        $path_Contact = storage_path('logs/contacts.log');
//        $path_Devis = storage_path('logs/devis.log');
//        $path_Societes = storage_path('logs/societes.log');


//        if (!file_exists($path_BL) || !file_exists($path_Contact) || !file_exists($path_Devis) || !file_exists($path_Societes)) {
//            abort(404, 'Fichier de log introuvable.');
//        }

        //        $logs_BL = array_reverse(file($path_BL));
//        $logs_Contact = array_reverse(file($path_Contact));
//        $logs_Devis = array_reverse(file($path_Devis));
//        $logs_Societes = array_reverse(file($path_Societes));

        $log_errors_path = storage_path('logs/upd_errors.log');
        $log_succes_path = storage_path('logs/upd_succes.log');

        $log_errors = file_exists($log_errors_path) ? array_reverse(file($log_errors_path)) : [];
        $log_succes = file_exists($log_succes_path) ? array_reverse(file($log_succes_path)) : [];

        return view('Log', [
            'log_errors' => $log_errors,
            'log_succes' => $log_succes,
        ]);
    }
}
