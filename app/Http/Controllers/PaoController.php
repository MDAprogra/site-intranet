<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaoController extends Controller
{
    public function index()
    {
        $EcartPAO = DB::connection('pgsql')->select("
            SELECT
                for2.opre_dossier,
                fed2.endv_cclient,
                for2.opre_date,
                for2.opre_sal,
                fed2.endv_init_dev,
                fos.suivi_tps_prevu AS tps_devis,
                SUM(for2.opre_duree) AS tps_reel,
                fos.suivi_tps_prevu - SUM(for2.opre_duree) AS ecart_tps
            FROM
                fp_opera_reel for2
                JOIN fp_opera_suivi fos ON for2.opre_dossier = fos.suivi_dossier
                FULL JOIN fd_entete_devi fed2 ON for2.opre_dossier = fed2.endv_no_commande
            WHERE
                for2.opre_poste LIKE '010'
                AND for2.opre_libelle_ope LIKE 'Compo'
                AND fos.suivi_centre LIKE '010'
                AND TO_CHAR(for2.opre_date, 'YYYY') >= '2024'
            GROUP BY
                for2.opre_dossier,
                for2.opre_date,
                fed2.endv_cclient,
                for2.opre_sal,
                fed2.endv_init_dev,
                fos.suivi_tps_prevu
            ORDER BY
                for2.opre_date DESC;
        ");

        // Ajouter la semaine à chaque élément du tableau
        foreach ($EcartPAO as $pao) {
            $date = Carbon::parse($pao->opre_date);
            $pao->semaine = $date->format('Y-W');
        }

        // Compter les dossiers par semaine (sans vérifier l'unicité)
        $CompteDossierSemaine = [];
        foreach ($EcartPAO as $pao) {
            $semaine = $pao->semaine;
            if (!isset($CompteDossierSemaine[$semaine])) {
                $CompteDossierSemaine[$semaine] = [];
            }
            $CompteDossierSemaine[$semaine][] = $pao->opre_dossier; // Ajouter le dossier sans vérification
        }

        // Compter le nombre de dossiers par semaine
        foreach ($CompteDossierSemaine as $semaine => $dossiers) {
            $CompteDossierSemaine[$semaine] = count($dossiers);
        }

        return view('components.pao', compact('EcartPAO', 'CompteDossierSemaine'));
    }
}
