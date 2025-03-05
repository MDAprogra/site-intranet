<?php

namespace App\Http\Controllers\Indicateurs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use DateTime;

class DevisController extends Controller
{
    public function index()
    {
        $DevisSemaine = DB::connection('pgsql')->select("SELECT
            TO_CHAR(endv_date, 'YYYY-WW') AS semaine,
            COUNT(*) AS nombre
        FROM
            fd_entete_devi
        WHERE
            TO_CHAR(endv_date, 'YYYY') >= '2024'
            AND endv_init_dev IN ('CCE', 'DBD', 'DGY', 'IAE', 'KPN', 'QRN', 'TSE')
        GROUP BY
            TO_CHAR(endv_date, 'YYYY-WW')
        ORDER BY
            semaine DESC;");

        return view('components.devis', compact('DevisSemaine'));
    }
}