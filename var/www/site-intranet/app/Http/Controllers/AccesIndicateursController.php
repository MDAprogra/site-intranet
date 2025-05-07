<?php

namespace App\Http\Controllers;

use App\Models\Indicateur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccesIndicateursController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->query('user_id'); // Récupérer l'ID de l'utilisateur depuis la requête

        $user = User::findOrFail($userId); // Récupérer l'utilisateur correspondant
        $AllIndic = Indicateur::all();
        $UserIndic = $user->indicateurs; // Récupérer les indicateurs de l'utilisateur choisi

        return view('acces-indicateurs', compact('AllIndic', 'UserIndic', 'user')); // Passer l'utilisateur à la vue
    }

    public function update(Request $request)
    {
        // Valider les données de la requête
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'indicateurs' => 'array',
            'indicateurs.*' => 'exists:indicateurs,id',
        ]);

        // Récupérer l'utilisateur choisi
        $user = User::findOrFail($request->input('user_id'));

        // Synchroniser les indicateurs de l'utilisateur
        $user->indicateurs()->sync($request->input('indicateurs', []));

        // Rediriger l'utilisateur vers la page de gestion des indicateurs
        return redirect()->route('access-indicateurs', ['user_id' => $user->id])->with('success', 'Les indicateurs ont été mis à jour avec succès.');
    }
}
