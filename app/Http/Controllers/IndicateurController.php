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

    public function show(string $indicateur)
    {
        if ($indicateur === 'devis') // ou $indicateur->component, etc.
        {
            $DevisMois = DB::connection('pgsql')->select("SELECT count(*) as nombre, TO_CHAR(endv_date, 'YYYY - MM') AS mois
    FROM fd_entete_devi
    WHERE endv_init_dev IN ('CCE', 'DBD', 'DGY', 'IAE', 'KPN', 'QRN', 'TSE')
      AND TO_CHAR(endv_date, 'YYYY - MM') = TO_CHAR(CURRENT_DATE - INTERVAL '1 month', 'YYYY - MM')
    GROUP BY TO_CHAR(endv_date, 'YYYY - MM')");
            return view('indicateurs.devis', compact('DevisMois'));
        }
        else
        {
            return view('indicateurs');
        }
    }
}
