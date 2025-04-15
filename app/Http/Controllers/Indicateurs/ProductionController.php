<?php

namespace App\Http\Controllers\Indicateurs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    /**
     * Récupère et transforme les données de consommation de papier.
     *
     * Cette méthode exécute une requête SQL brute pour extraire les données
     * de consommation de papier à partir de la base de données PostgreSQL.
     * Les données sont ensuite transformées en une collection formatée.
     *
     * @return Collection
     */
    public function GET_ConsoPapier(): Collection
    {
        // Exécute une requête SQL brute pour récupérer les données de consommation de papier
        $ConsoPapier = collect(DB::connection('pgsql')->select("
                        Select MVT_DOSSIER,
                               st_seq,
                               st_ref,
                               MVT_SEQ_ARTICLE                                  as papier,
                               max(MVT_FAM)                                     as famille,
                               max(MVT_TYPE)                                    as type,
                               max(MVT_GRAM)                                    as grammage,
                               max(MVT_FTX) * 10                                as laize,
                               sum((MVT_Q) - (2 * (MVT_TYP_MVT - 4) * (MVT_Q))) as quantite_m,
                               mvt_date_mvt
                        from FS_LIGNE_MVT
                                 left join fs_stock
                                           on MVT_SEQ_ARTICLE = ST_SEQ_COMPT
                        where MVT_GENRE = 'F'
                          and MVT_TYP_MVT between 4 and 5
                          and MVT_DOSSIER <> ''
                          and mvt_date_mvt >= '20230101'
                        group by MVT_DOSSIER, MVT_SEQ_ARTICLE, st_ref, st_seq, mvt_date_mvt;
                        "));

        // Transforme les résultats de la requête en une collection formatée
        return $ConsoPapier->map(function ($item) {
            return [
                'mvt_dossier' => $item->MVT_DOSSIER,
                'st_seq' => $item->st_seq,
                'st_ref' => $item->st_ref,
                'papier' => $item->papier,
                'famille' => $item->famille,
                'type' => $item->type,
                'grammage' => $item->grammage,
                'laize' => $item->laize,
                'quantite_m' => $item->quantite_m,
                'mvt_date_mvt' => $item->mvt_date_mvt
            ];
        });
    }
}
