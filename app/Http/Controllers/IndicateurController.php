<?php

namespace App\Http\Controllers;

use App\Models\Indicateur;
use Illuminate\Http\Request;

class IndicateurController extends Controller
{
    public function index()
    {
        // Récupérer tous les utilisateurs de la base de données
        $indicateurs = Indicateur::all();

        // Passer les utilisateurs à la vue
        return view('indicateur', compact('indicateurs')); // Remplacez 'votre_vue' par le nom de votre fichier blade.
    }
}
