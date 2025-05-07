<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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

    public function edit(User $user)
    {
        return view('user.user-update', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Valider les données
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required'
        ]);

        // Mettre à jour l'utilisateur
        $user->update($data);

        // Rediriger l'utilisateur
        return redirect()->route('utilisateur')->with('success', 'Utilisateur mis à jour avec succès'); // Remplacez 'utilisateur.index' par votre route de liste d'utilisateurs.
    }
}
