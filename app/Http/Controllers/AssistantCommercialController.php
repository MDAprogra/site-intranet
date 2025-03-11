<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AssistantCommercialController extends Controller
{
    public function index()
    {
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

        return view('components.assistant-commercial', [
            'NbDossier' => $NbDossier,
            'NbDossierWeek' => $NbDossierWeek,
            'NbDossierMonth' => $NbDossierMonth,
        ]);

        //return view('components.assistant-commercial');
    }
}
