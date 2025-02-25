<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
