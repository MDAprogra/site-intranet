<?php

namespace App\Http\Controllers\Indicateurs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DevisController extends Controller
{
    public function index()
    {
//        $DevisMois = DB::connection('pgsql')->select("SELECT count(*) as nombre, TO_CHAR(endv_date, 'YYYY - MM') AS mois
//    FROM fd_entete_devi
//    WHERE endv_init_dev IN ('CCE', 'DBD', 'DGY', 'IAE', 'KPN', 'QRN', 'TSE')
//      AND TO_CHAR(endv_date, 'YYYY - MM') = TO_CHAR(CURRENT_DATE - INTERVAL '1 month', 'YYYY - MM')
//    GROUP BY TO_CHAR(endv_date, 'YYYY - MM')");
        return view('indicateurs.devis');
    }
}
