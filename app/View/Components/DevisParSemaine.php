<?php

namespace App\View\Components;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class DevisParSemaine extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $devisParSemaine = DB::connection('pgsql')->select("
    SELECT count(*) as nombre, TO_CHAR(endv_date, 'YYYY-WW') AS semaine
    FROM fd_entete_devi
    WHERE endv_init_dev IN ('CCE', 'DBD', 'DGY', 'IAE', 'KPN', 'QRN', 'TSE')
      AND TO_CHAR(endv_date, 'YYYY-WW') = TO_CHAR(CURRENT_DATE - INTERVAL '1 week', 'YYYY-WW')
    GROUP BY TO_CHAR(endv_date, 'YYYY-WW')
");

        $nombreDeDevis = !empty($devisParSemaine) ? $devisParSemaine[0]->nombre : 0;
        $semaine = !empty($devisParSemaine) ? $devisParSemaine[0]->semaine : '';

        $devisParSemaineParDeviseur = DB::connection('pgsql')->select("
    SELECT endv_init_dev, TO_CHAR(endv_date, 'YYYY-WW') AS semaine, COUNT(*) AS Nombre
    FROM fd_entete_devi
    WHERE TO_CHAR(endv_date, 'YYYY-WW') = TO_CHAR(CURRENT_DATE - INTERVAL '1 week', 'YYYY-WW')
      AND endv_init_dev IN ('CCE', 'DBD', 'DGY', 'IAE', 'KPN', 'QRN', 'TSE')
    GROUP BY endv_init_dev, TO_CHAR(endv_date, 'YYYY-WW')
    ORDER BY semaine DESC;
");


        $devisParMois = DB::connection('pgsql')->select("
    SELECT count(*) as nombre, TO_CHAR(endv_date, 'YYYY-MM') AS mois
    FROM fd_entete_devi
    WHERE endv_init_dev IN ('CCE', 'DBD', 'DGY', 'IAE', 'KPN', 'QRN', 'TSE')
      AND TO_CHAR(endv_date, 'YYYY-MM') = TO_CHAR(CURRENT_DATE - INTERVAL '1 month', 'YYYY-MM')
    GROUP BY TO_CHAR(endv_date, 'YYYY-MM')
");

        $nombreDeDevisMensuel = !empty($devisParMois) ? $devisParMois[0]->nombre : 0;
        $mois = !empty($devisParMois) ? $devisParMois[0]->mois : '';


        return view('components.devis', ['nombreDeDevis' => $nombreDeDevis, 'semaine' => $semaine, 'devisParSemaineParDeviseur' => $devisParSemaineParDeviseur,'nombreDeDevisMensuel' => $nombreDeDevisMensuel, 'mois' => $mois ]);
    }
}
