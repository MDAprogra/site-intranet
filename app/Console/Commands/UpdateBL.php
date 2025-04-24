<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;
use League\Csv\CannotInsertRecord;
use League\Csv\InvalidArgument;

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
        $start = microtime(true); // Début du chronométrage

        try {
            // Récupération des bons de livraison
            $bonsLivraison = DB::connection('pgsql')->select("
            SELECT 
                d.BO_SEQ                                               AS numero_livraison,
                d.BO_SITE                                              AS site_livraison,
                d.BO_NO                                                AS numero_bon_livraison,
                d.BO_DATE_SOUHAITEE                                    AS date_souhaitee,
                d.BO_DATE_IMPERATIVE                                   AS date_imperative,
                d.BO_DATE_REELLE                                       AS date_reelle,
                dCli.FO_REFERENCE                                      AS reference_client,
                BO_GENRE_DE_LIVRAISON                                  AS genre_livraison,
                BO_TYPE_LIVRAISON                                      AS type_livraison,
                d.BO_DESCRIPTIF_1                                      AS descriptif_livraison,
                BO_STATUT_LIVRAISON                                    AS statut_livraison,
                BO_CODE_REP                                            AS code_representant
            FROM 
                (((FF_LIVRAISON d 
                LEFT JOIN FC_REFERENCES dCli ON dCli.FO_REFERENCE = d.BO_REF)
                LEFT JOIN FD_ENTETE_DEVI dDos ON dDos.ENDV_NO_COMMANDE <> '' AND dDos.ENDV_NO_COMMANDE = d.BO_NO_DOSSIER)
                LEFT JOIN FS_LIGNE_MVT dMvt ON dMvt.MVT_TYP_MVT = 3 
                    AND dMvt.MVT_IDENTIFIANT_LIGNE_CMD = d.BO_NO_DOSSIER 
                    AND d.BO_TYPE_LIVRAISON = 1)
                LEFT JOIN FP_AGENDA_PROD dDosDatGest14 ON dDosDatGest14.AG_DOSSIER = dDos.ENDV_NO_COMMANDE 
                    AND dDosDatGest14.AG_TYPE_DATE = 14
            WHERE 
                (d.BO_DATE_SOUHAITEE >= '01/01/2024')
            ORDER BY 
                numero_bon_livraison DESC, 
                numero_livraison ASC
        ");

            $filePath = '/mnt/partage_windows/Exp_BL.csv';

            $csv = Writer::createFromPath($filePath, 'w');
            $csv->setDelimiter(';');

            // Récupérer dynamiquement les noms des colonnes à partir du premier résultat
            if (!empty($bonsLivraison)) {
                $firstRow = (array) $bonsLivraison[0];
                $csv->insertOne(array_keys($firstRow)); // En-tête dynamique
            }

            // Insérer les lignes
            foreach ($bonsLivraison as $row) {
                $csv->insertOne((array) $row);
            }

            $duration = microtime(true) - $start; // Fin du chronométrage
            $this->info('Bons de Livraison exportés avec succès vers : ' . $filePath);
            $this->info('Temps d\'exécution : ' . round($duration, 2) . ' secondes');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Une erreur est survenue lors de l\'exportation : ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

}