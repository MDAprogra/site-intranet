<?php

namespace App\Console\Commands;

use App\Tools\AccessoiresFTP;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateContactRelance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-contact-relance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = microtime(true);
        $channel_success = Log::channel('update_success');
        $channel_errors = Log::channel('update_errors');

        try {
            $contacts_relance = $this->getContactRelance();
        } catch (\Exception $e) {
            $channel_errors->error('[Contact Relance] -> Erreur lors de la récupération des contacts de relance : ' . $e->getMessage());
            return;
        }
        try {
            $this->writeFile($contacts_relance);
        } catch (\Exception $e) {
            $channel_errors->error('[Contact Relance] -> Erreur lors de l\'écriture du fichier : ' . $e->getMessage());
            return;
        }
        try {
            $access_ftp = new AccessoiresFTP();
            $access_ftp->sendToFTP('Exp_ContactsRelances.txt');
        } catch (\Exception $e) {
            $channel_errors->error('[Articles] -> Erreur lors de l\'envoi FTP : ' . $e->getMessage());
            return;
        }
        $end = microtime(true);
        $executionTime = ($end - $start);
        $channel_success->info('Contacts relances Mise à jour avec succès (' . count($contacts_relance) . ' articles) en ' . round($executionTime, 2) . ' secondes');
    }

    private function writeFile($data)
    {

        // Vérification du dossier
        $directory = dirname("/mnt/partage_windows/Exp_ContactsRelances.txt");
        if (!is_dir($directory) || !is_writable($directory)) {
            throw new \RuntimeException("Le répertoire n'existe pas ou n'est pas accessible en écriture : $directory");
        }

        // Ouverture du fichier en mode écriture
        $file = fopen("/mnt/partage_windows/Exp_ContactsRelances.txt", 'w');
        if (!$file) {
            throw new \RuntimeException("Impossible d'ouvrir le fichier en écriture");
        }
        // Écriture des données dans le fichier
        foreach ($data as $row) {
            $line = implode(';', (array)$row) . "\n";
            fwrite($file, $line);
        }
        fclose($file);
    }

    private function getContactRelance()
    {
        return DB::connection('pgsql')->select("
        SELECT DISTINCT
	frc.relcont_code AS Societe,
	rf.fo_cpte_gene AS fo_cpte_gene,
	( ( SELECT STRING_AGG( f1.relcont_adpro_email ,  ';')  AS Expr1
FROM fc_relation_contact f1
WHERE ( UPPER( f1.relcont_prenom_nom  ) LIKE '%RELANCE%' AND ( f1.relcont_code = frc.relcont_code ) )  )  )  AS mail
FROM
	fc_relation_contact frc,
	fc_references rf
WHERE
	frc.relcont_code = rf.fo_reference
	AND
	(
		UPPER( frc.relcont_prenom_nom  )  LIKE '%RELANCE%'
		AND	frc.relcont_clifoupro = 'C'
		AND	frc.relcont_adpro_email <> ''
	)
ORDER BY
	Societe ASC;
        ");

    }
}
