<?php

namespace App\Http\Controllers\Indicateurs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class BepsController extends Controller
{
    public function AfficherBeps()
    {
        $v_BEPS = $this->GET_BEPS();

        return view('indicateurs.beps', [
            'v_BEPS' => $v_BEPS,
        ]);
    }

     public function GET_BEPS(): Collection
     {
         $value = collect(DB::connection('pgsql')->select("SELECT st_site,
       extract(day from (now() - ST_DERNIER_MVT)) as days_last_mvt,
       FO_NOM_1,
       FO_REP_CODE,
       FS_STOCK.ST_SEQ_COMPT,
       FS_STOCK.ST_CLIENT,
       FS_STOCK.ST_MODELE,
       FS_STOCK.ST_VERSION_MODELE,
       FS_STOCK.ST_ART_REF_CLIENT,
       FS_STOCK.ST_LIB_1_CONSO,
       FS_STOCK.ST_Q_PHYSIQUE,
       FS_STOCK.ST_PX_VENTE_LE_1000,
       FS_STOCK.ST_DERNIER_MVT,
       FS_STOCK.ST_ART_FAMILLE,
       FS_STOCK.ST_ART_SFAMILLE,
       ST_ART_DEVIS_LIE,
       FS_STOCK.ST_TYPE,
       FS_STOCK.ST_PMP,
       FS_STOCK.ST_DERNIER_PRIX_ACHAT,
       ST_PMP * ST_Q_PHYSIQUE / 1000              AS Val_beps,
       fd_types_prod.typpro_lib                   as typpro_lib
FROM FS_STOCK
         left join fc_references on ST_CLIENT = FO_REFERENCE

         LEFT JOIN fd_types_prod
                   ON fd_types_prod.typpro_code = ST_TYPE

WHERE ST_GENRE = 'P'
  AND FS_STOCK.ST_Q_PHYSIQUE <> 0"));

         return $value;
     }

}
