<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AssistantCommercialController extends Controller
{
    public function index()
    {
        //Dossier
        $NbDossier = DB::connection('pgsql')->select("
            SELECT dos_date,
       COUNT(*) AS nombre_dossiers
FROM fd_dossier
where to_char(dos_date, 'YYYY') >= '2024'
group by dos_date
            order by dos_date desc;
        ");

        $NbDossierWeek = [];
        $NbDossierMonth = [];

        foreach ($NbDossier as $dossier) {
            $date = Carbon::parse($dossier->dos_date);
            $dossier->semaine = $date->format('Y-W');
            $dossier->mois = $date->format('Y-m');
        }

        foreach ($NbDossier as $dossier) {
            $semaine = $dossier->semaine;
            if (!isset($NbDossierWeek[$semaine])) {
                $NbDossierWeek[$semaine] = [];
            }
            $NbDossierWeek[$semaine][] = $dossier->nombre_dossiers;
        }

        foreach ($NbDossier as $dossier) {
            $mois = $dossier->mois;
            if (!isset($NbDossierMonth[$mois])) {
                $NbDossierMonth[$mois] = [];
            }
            $NbDossierMonth[$mois][] = $dossier->nombre_dossiers;
        }


        //BL
        $NbBL = DB::connection('pgsql')->select("
            select count(*) nombre,
       mvt_date_mvt
from fs_ligne_mvt
where mvt_typ_mvt = 4
and mvt_lib_typ = 'Livr.'
and MVT_DATE_MVT >= '2024-01-01'
group by mvt_date_mvt
order by MVT_DATE_MVT desc;
        ");

        $NbBLWeek = [];
        $NbBLMonth = [];

        foreach ($NbBL as $BL) {
            $date = Carbon::parse($BL->mvt_date_mvt);
            $BL->semaine = $date->format('Y-W');
            $BL->mois = $date->format('Y-m');
        }

        foreach ($NbBL as $BL) {
            $semaine = $BL->semaine;
            if (!isset($NbBLWeek[$semaine])) {
                $NbBLWeek[$semaine] = [];
            }
            $NbBLWeek[$semaine][] = $BL->nombre;
        }

        foreach ($NbBL as $BL) {
            $mois = $BL->mois;
            if (!isset($NbBLMonth[$mois])) {
                $NbBLMonth[$mois] = [];
            }
            $NbBLMonth[$mois][] = $BL->nombre;
        }

        return view('components.assistant-commercial', [
            'NbDossier' => $NbDossier,
            'NbDossierWeek' => $NbDossierWeek,
            'NbDossierMonth' => $NbDossierMonth,
            'NbBL' => $NbBL,
            'NbBLWeek' => $NbBLWeek,
            'NbBLMonth' => $NbBLMonth,
        ]);
    }
}
