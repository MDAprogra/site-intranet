<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        // Récupérer tous les utilisateurs de la base de données
        $users = User::all();

        // Passer les utilisateurs à la vue
        return view('utilisateur', compact('users')); // Remplacez 'votre_vue' par le nom de votre fichier blade.
    }
}
