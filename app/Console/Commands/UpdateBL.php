<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateBL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-b-l';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Récupération des Bons de Livraison';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //Forcer la database
        config(['database.default' => 'pgsql']);
        config(['database.connections.pgsql.database' => 'interfas']);
        config(['database.connections.pgsql.username' => 'u_odbc']);
        config(['database.connections.pgsql.password' => 'efi']);
//        DB::connection('pgsql')->select("select bo_no,
//       bo_devis,
//       bo_ref,
//         bo_date_reelle,
//         bo_quant_livree_total,
//         BO_TYPE_LIVR,
//         BO_FACT,
//         BO_STATUT_LIVRAISON,
//         BO_DATE_SOUHAITEE
//from ff_livraison
//where bo_date_reelle >= '2024-01-01'
//  and bo_date_reelle <= now()
//order by bo_date_reelle desc;");

        $NbDossier = DB::connection('pgsql')->select("
            SELECT dos_date,
       COUNT(*) AS nombre_dossiers
FROM fd_dossier
where to_char(dos_date, 'YYYY') >= '2024'
group by dos_date
            order by dos_date desc;
        ");

        if (empty($NbDossier)) {
            $this->error('Aucun dossier trouvé.');
            return;
        }

        $this->info('Bons de Livraison récupérés avec succès.');
    }
}
