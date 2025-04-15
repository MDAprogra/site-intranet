<?php

namespace App\Http\Controllers\Indicateurs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public function IndicateurConsoPapier(Request $request)
    {
        $getConsoPapier = $this->getConsoPapier();

        return view('indicateurs.production', [
            'getConsoPapier' => $getConsoPapier,
        ]);
    }

    /**
     * Récupère les données de consommation de papier.
     **/
    public function getConsoPapier(): Collection
    {
        $results = DB::connection('pgsql')->select("
            SELECT MVT_DOSSIER,
                   st_seq,
                   st_ref,
                   MVT_SEQ_ARTICLE AS papier,
                   MAX(MVT_FAM) AS famille,
                   MAX(MVT_TYPE) AS type,
                   MAX(MVT_GRAM) AS grammage,
                   MAX(MVT_FTX) * 10 AS laize,
                   SUM((MVT_Q) - (2 * (MVT_TYP_MVT - 4) * (MVT_Q))) AS quantite_m,
                   mvt_date_mvt
            FROM FS_LIGNE_MVT
            LEFT JOIN fs_stock ON MVT_SEQ_ARTICLE = ST_SEQ_COMPT
            WHERE MVT_GENRE = 'F'
              AND MVT_TYP_MVT BETWEEN 4 AND 5
              AND MVT_DOSSIER <> ''
              AND mvt_date_mvt >= '20250101'
            GROUP BY MVT_DOSSIER, MVT_SEQ_ARTICLE, st_ref, st_seq, mvt_date_mvt
        ");

        return collect($results);
    }

    /**
     * Récupère les données papier des devis.
     */
    public function getPapierDevise(): Collection
    {
        $results = DB::connection('pgsql')->select("
            SELECT
                ENDV_DATE AS date_devis,
                endv_no_commande,
                endv_coduniq,
                FI_SOL_IMP.soximp_nbre_feuil_pap / 100 AS papier_dev,
                FI_SOL_IMP.soximp_ftx_pap * 10 AS laize_pap,
                FI_SOL_IMP.soximp1_fty_imp * 10 / 25.4 AS developpement,
                fi_sol_imp.soximp1_poses AS poses,
                SOXIMP1_DISP_POSE AS layout,
                FI_SOL_IMP.soximp1_seq_papier AS seq_papier,
                cat_ref,
                cat_fournisseur,
                cat_info_fournisseur
            FROM FI_SOL_IMP
            JOIN fd_entete_devi ON FI_SOL_IMP.soximp_code_devis = fd_entete_devi.endv_coduniq
            JOIN fs_catalogue fc ON FI_SOL_IMP.soximp1_seq_papier = cat_compt
            WHERE endv_no_commande <> ''
              AND ENDV_DATE > '20250101'
        ");

        return collect($results)->map(function ($item) {
            return (array)$item;
        });
    }
}
