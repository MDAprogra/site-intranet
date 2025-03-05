<?php

namespace App\Http\Controllers;

use App\Models\Indicateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndicateurController extends Controller
{
    public function index()
    {
        // Récupérer tous les utilisateurs de la base de données
        $indicateurs = Auth::user()->indicateurs;

        // Passer les utilisateurs à la vue
        return view('indicateur', compact('indicateurs')); // Remplacez 'votre_vue' par le nom de votre fichier blade.
    }
}
