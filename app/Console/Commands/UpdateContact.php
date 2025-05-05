<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Tools\FormatTexte;
use App\Tools\AccessoiresFTP;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class UpdateContact extends Command
{
    protected $signature = 'app:update-contact';
    protected $description = 'Mise à jour des contacts de Yellowbox';

    public function handle()
    {
        $start = microtime(true);
        $channel_success = Log::channel('update_success');
        $channel_errors = Log::channel('update_errors');
        try {
            $contacts = $this->fetchContacts();
            $filePath = '/mnt/partage_windows/Exp_Contacts.txt';
            $this->writeContactsToFile($contacts, $filePath);
            try {
                $access_ftp = new AccessoiresFTP();
                $name = 'Exp_Contacts.txt';
                $access_ftp->sendToFTP($name);
            } catch (\Exception $e) {
                $channel_errors->error('[Contacts] -> Erreur lors de l\'envoi du fichier sur le serveur FTP : ' . $e->getMessage());
                return Command::FAILURE;
            }

            $duration = round(microtime(true) - $start, 2);
        } catch (Throwable $e) {
            $channel_errors->error('[Contacts] -> Erreur : ' . $e->getMessage());
            return Command::FAILURE;
        }
        $channel_success->info('BL Mise à jour avec succès (' . count($contacts) . ' articles) en ' . round($duration, 2) . ' secondes');
        return Command::SUCCESS;

    }

    private function fetchContacts(): array
    {
        $formatter = new FormatTexte();

        $contacts = DB::connection('pgsql')->select("SELECT DISTINCT
	frc.relcont_code AS Reference,
	fc_references.fo_rep_code AS Gestionnaire,
	REPLACE(REPLACE(REPLACE(frc.relcont_adpro ,CHR( 13) , '##') ,CHR( 10) , '##') ,CHR( 9) , '     ')  AS Adresse,
	REPLACE(REPLACE(REPLACE(frc.relcont_adpro_codpost ,CHR( 13) , '') ,CHR( 10) , '') ,CHR( 9) , '     ')  AS CodePostal,
	REPLACE(REPLACE(REPLACE(frc.relcont_adpro_ville ,CHR( 13) , '') ,CHR( 10) , '') ,CHR( 9) , '     ')  AS Ville,
	REPLACE(REPLACE(REPLACE(frc.relcont_adpro_tel ,CHR( 13) , '') ,CHR( 10) , '') ,CHR( 9) , '     ')  AS LigneDirecte,
	REPLACE(REPLACE(REPLACE(frc.relcont_adpro_pays ,CHR( 13) , '') ,CHR( 10) , '') ,CHR( 9) , '     ')  AS Pays,
	REPLACE(REPLACE(REPLACE(frc.relcont_adpro_email ,CHR( 13) , '') ,CHR( 10) , '') ,CHR( 9) , '     ')  AS EmailPro,
	CASE WHEN ( frc.relcont_code_fonction =  'BAT')  THEN  1 ELSE  0 END  AS ValidateurBAT,
	CASE frc.relcont_code_fonction  WHEN  'ACH' THEN  'Acheteur' WHEN  'APP' THEN  'Approvisionneur' WHEN  'CPT' THEN  'Comptable' WHEN  'DAC' THEN  'Directeur des achats' WHEN  'DFI' THEN  'Directeur financier' WHEN  'DG' THEN  'Directeur général' WHEN  'DIN' THEN  'Directeur informatique' WHEN  'DLG' THEN  'Directeur logistique' WHEN  'DMA' THEN  'Directeur marketing' WHEN  'DPR' THEN  'Directeur de production' WHEN  'DRG' THEN  'Directeur régional' WHEN  'PDG' THEN  'PDG' WHEN  'RAQ' THEN  'Responsable qualité' ELSE  'A préciser' END  AS Fonction,
	frc.relcont_date_crea AS CreeeLe,
	CASE frc.relcont_civilite  WHEN  0 THEN  'Monsieur' WHEN  1 THEN  'Madame' WHEN  2 THEN  'Mademoiselle' ELSE  '' END  AS Civilite,
	frc.relcont_prenom AS Prenom,
	frc.relcont_nom AS Nom,
	frc.relcont_adpro_gsm AS PortablePro,
	frc.relcont_seq AS IdG6,
	frc.relcont_no_contact AS NoG6Contact,
	to_char(current_timestamp, 'DD/MM/YYYY HH24:MI') as MiseAJour,
    frc.relcont_seq AS Seq
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
		frc.relcont_date_maj >= CURRENT_DATE
		OR	fc_references.fo_date_crea >= CURRENT_DATE
		OR	fc_references.fo_date_modif >= CURRENT_DATE
	)
	AND	fc_references.fo_reference <> '*'
	AND	fc_references.fo_nom_1 <> ''
	AND	fc_references.fo_nom_1 IS NOT NULL
	AND	fc_references.fo_reference NOT IN ('ZZZZZZZZ', 'ZZZ')
	AND	fc_references.fo_type_c_f_p IN ('C', 'P', 'F')
);");
        $funcIdYB = new FormatTexte();
        foreach ($contacts as $contact) {
            foreach ($contact as $key => $value) {
                if (is_string($value)) {
                    $contact->$key = $formatter->clean_txt($value);
                }
            }

            $contact->gestionnaire = $funcIdYB->getIdYB($contact->gestionnaire);
        }

        return $contacts;
    }

    private function writeContactsToFile(array $contacts, string $filePath): void
    {

        // Vérification du dossier
        $directory = dirname($filePath);
        if (!is_dir($directory) || !is_writable($directory)) {
            throw new RuntimeException("Le répertoire n'existe pas ou n'est pas accessible en écriture : $directory");
        }

        // Ouverture du fichier
        $file = fopen($filePath, 'w');
        if (!$file) {
            throw new RuntimeException("Impossible d'ouvrir le fichier en écriture : $filePath");
        }

        foreach ($contacts as $contact) {
            $line = implode(';', (array)$contact) . "\n";
            fwrite($file, $line);
        }

        fclose($file);
    }
}
