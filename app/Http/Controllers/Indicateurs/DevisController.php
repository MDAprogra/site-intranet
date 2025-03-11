<?php

namespace App\Http\Controllers\Indicateurs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use DateTime;

class DevisController extends Controller
{
    public function index()
    {
        $Devis = DB::connection('pgsql')->select("
        SELECT
            endv_date,
            COUNT(*) AS nombre
        FROM
            fd_entete_devi
        WHERE
            TO_CHAR(endv_date, 'YYYY') >= '2024'
            AND endv_init_dev IN ('CCE', 'DBD', 'DGY', 'IAE', 'KPN', 'QRN', 'TSE')
        GROUP BY
            endv_date
        ORDER BY
            endv_date DESC;
    ");

        $collection = collect($Devis);

        $DevisSemaine = $collection->mapWithKeys(function ($item) {
            $date = new DateTime($item->endv_date);
            $semaine = $date->format("Y-W");
            return [$semaine => ($this->DevisSemaine[$semaine] ?? 0) + $item->nombre];
        })->toArray();

        $DevisMois = $collection->mapWithKeys(function ($item) {
            $date = new DateTime($item->endv_date);
            $mois = $date->format("Y-m");
            return [$mois => ($this->DevisMois[$mois] ?? 0) + $item->nombre];
        })->toArray();

        return view('components.devis', compact('DevisSemaine', 'DevisMois'));
    }
}
