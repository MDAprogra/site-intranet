<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Tools\FormatTexte;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UpdateContact extends Command
{
    protected $signature = 'app:update-contact';
    protected $description = 'Mise à jour des contacts de Yellowbox';

    public function handle()
    {
        $start = microtime(true);
        $channel = Log::channel('contacts');
        $channel->info('DEBUT -- exportation des contacts -- DEBUT');

        try {
            $contacts = $this->fetchContacts();
            $channel->info('Contacts récupérés : ' . count($contacts));

            $filePath = '/mnt/partage_windows/Exp_Contacts.txt';
            $this->writeContactsToFile($contacts, $filePath);

            $channel->info('Fichier créé : ' . $filePath);
            try {
                $this->SendToFTP();
                $channel->info('Fichier envoyé sur le serveur FTP');
            } catch (\Exception $e) {
                $channel->error('Erreur lors de l\'envoi du fichier sur le serveur FTP : ' . $e->getMessage());
                return Command::FAILURE;
            }

            $duration = round(microtime(true) - $start, 2);
            $channel->info("Durée d'exécution : {$duration} sec");
        } catch (Throwable $e) {
            $channel->error('Erreur : ' . $e->getMessage());
            $channel->debug($e->getTraceAsString());
            return Command::FAILURE;
        }
        //prochaine execution


        $channel->info('FIN -- Temps d\'exécution ' . round($duration, 2) . ' secondes' . ' / Prochaine exécution : ' . now()->addMinutes(5)->format('d/m/Y à H:i') . ' -- FIN');
        return Command::SUCCESS;
    }

    private function fetchContacts(): array
    {
        $formatter = new FormatTexte();

        $contacts = DB::connection('pgsql')->select("SELECT DISTINCT
	frc.relcont_code AS relcont_code,
	fc_references.fo_rep_code AS gest,
	REPLACE(REPLACE(REPLACE(frc.relcont_adpro ,CHR( 13) , '##') ,CHR( 10) , '##') ,CHR( 9) , '     ')  AS relcont_adpro,
	REPLACE(REPLACE(REPLACE(frc.relcont_adpro_codpost ,CHR( 13) , '') ,CHR( 10) , '') ,CHR( 9) , '     ')  AS relcont_adpro_codpost,
	REPLACE(REPLACE(REPLACE(frc.relcont_adpro_ville ,CHR( 13) , '') ,CHR( 10) , '') ,CHR( 9) , '     ')  AS relcont_adpro_ville,
	REPLACE(REPLACE(REPLACE(frc.relcont_adpro_tel ,CHR( 13) , '') ,CHR( 10) , '') ,CHR( 9) , '     ')  AS relcont_adpro_tel,
	REPLACE(REPLACE(REPLACE(frc.relcont_adpro_pays ,CHR( 13) , '') ,CHR( 10) , '') ,CHR( 9) , '     ')  AS relcont_adpro_pays,
	REPLACE(REPLACE(REPLACE(frc.relcont_adpro_email ,CHR( 13) , '') ,CHR( 10) , '') ,CHR( 9) , '     ')  AS relcont_adpro_email,
	CASE WHEN ( frc.relcont_code_fonction =  'BAT')  THEN  1 ELSE  0 END  AS valid_bat,
	CASE frc.relcont_code_fonction  WHEN  'ACH' THEN  'Acheteur' WHEN  'APP' THEN  'Approvisionneur' WHEN  'CPT' THEN  'Comptable' WHEN  'DAC' THEN  'Directeur des achats' WHEN  'DFI' THEN  'Directeur financier' WHEN  'DG' THEN  'Directeur général' WHEN  'DIN' THEN  'Directeur informatique' WHEN  'DLG' THEN  'Directeur logistique' WHEN  'DMA' THEN  'Directeur marketing' WHEN  'DPR' THEN  'Directeur de production' WHEN  'DRG' THEN  'Directeur régional' WHEN  'PDG' THEN  'PDG' WHEN  'RAQ' THEN  'Responsable qualité' ELSE  'A préciser' END  AS Fonction,
	frc.relcont_date_crea AS date_creation,
	CASE frc.relcont_civilite  WHEN  0 THEN  'Monsieur' WHEN  1 THEN  'Madame' WHEN  2 THEN  'Mademoiselle' ELSE  '' END  AS civilité,
	frc.relcont_prenom AS relcont_prenom,
	frc.relcont_nom AS relcont_nom,
	frc.relcont_adpro_gsm AS relcont_adpro_gsm,
	frc.relcont_date_maj AS relcont_date_maj,
	fc_references.fo_date_crea AS fo_date_crea,
	fc_references.fo_date_modif AS fo_date_modif,
	frc.relcont_seq AS sequentiel,
	frc.relcont_no_contact
FROM
	fc_references
	INNER JOIN
	fc_relation_contact frc
	ON fc_references.fo_reference = frc.relcont_code
WHERE
	(
	frc.relcont_inactif = '0'
	AND	frc.relcont_nom <> ''
	AND	fc_references.fo_inactif = '0'
	/*AND	frc.relcont_adpro_email <> ''*/
	AND
	(
		frc.relcont_date_maj >= CURRENT_DATE - interval '1' day
		OR	fc_references.fo_date_crea >= CURRENT_DATE - interval '1' day
		OR	fc_references.fo_date_modif >= CURRENT_DATE - interval '1' day
	)
	AND	fc_references.fo_reference <> '*'
	AND	fc_references.fo_nom_1 <> ''
	AND	fc_references.fo_nom_1 IS NOT NULL
	AND	fc_references.fo_reference NOT IN ('ZZZZZZZZ', 'ZZZ')
	AND	fc_references.fo_type_c_f_p IN ('C', 'P', 'F')
);");

        foreach ($contacts as $contact) {
            foreach ($contact as $key => $value) {
                if (is_string($value)) {
                    $contact->$key = $formatter->clean_txt($value);
                }
            }
        }

        return $contacts;
    }

    private function writeContactsToFile(array $contacts, string $filePath): void
    {
        $channel = Log::channel('contacts');

        // Vérification du dossier
        $directory = dirname($filePath);
        if (!is_dir($directory) || !is_writable($directory)) {
            throw new \RuntimeException("Le répertoire n'existe pas ou n'est pas accessible en écriture : $directory");
        }

        // Ouverture du fichier
        $file = fopen($filePath, 'w');
        if (!$file) {
            throw new \RuntimeException("Impossible d'ouvrir le fichier en écriture : $filePath");
        }

        foreach ($contacts as $contact) {
            $line = implode(';', (array)$contact) . "\n";
            fwrite($file, $line);
        }

        fclose($file);
        $channel->info("Fichier écrit avec succès : $filePath");
    }

    private function SendToFTP(): void
    {
        try {
            Storage::disk('sftp')->put('Imports_Automatiques/PHP/Exp_Contacts.txt', fopen('/mnt/partage_windows/Exp_Contacts.txt', 'r+'));
        } catch (\Exception $e) {
            throw $e; // Rethrow the exception to be caught in the handle method
        }
    }
}
