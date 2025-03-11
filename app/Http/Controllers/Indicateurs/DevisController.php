<?php

namespace App\Http\Controllers\Indicateurs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use DateTime;

class DevisController extends Controller
{
    public function index()
    {
//        $DevisSemaine = DB::connection('pgsql')->select("SELECT
//            TO_CHAR(endv_date, 'YYYY-WW') AS semaine,
//            COUNT(*) AS nombre
//        FROM
//            fd_entete_devi
//        WHERE
//            TO_CHAR(endv_date, 'YYYY') >= '2024'
//            AND endv_init_dev IN ('CCE', 'DBD', 'DGY', 'IAE', 'KPN', 'QRN', 'TSE')
//        GROUP BY
//            TO_CHAR(endv_date, 'YYYY-WW')
//        ORDER BY
//            semaine DESC;");

        $Devis = DB::connection('pgsql')->select("SELECT endv_date,
       COUNT(*) AS nombre
FROM fd_entete_devi
WHERE TO_CHAR(endv_date, 'YYYY') >= '2024'
  AND endv_init_dev IN ('CCE', 'DBD', 'DGY', 'IAE', 'KPN', 'QRN', 'TSE')
GROUP BY endv_date
ORDER BY endv_date DESC;");

        $DevisSemaine = [];
        $DevisMois = [];

        foreach ($Devis as $row) {
            $date = new DateTime($row->endv_date);
            $semaine = $date->format("Y-W");
            $mois = $date->format("Y-m");

            // Group by week
            if (!isset($DevisSemaine[$semaine])) {
                $DevisSemaine[$semaine] = 0;
            }
            $DevisSemaine[$semaine] += $row->nombre;

            // Group by month
            if (!isset($DevisMois[$mois])) {
                $DevisMois[$mois] = 0;
            }
            $DevisMois[$mois] += $row->nombre;
        }

        //dd($DevisSemaine, $DevisMois);


        return view('components.devis', compact('DevisSemaine', 'DevisMois'));
    }
}
