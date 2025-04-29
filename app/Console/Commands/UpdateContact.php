<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Tools\FormatTexte;

class UpdateContact extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-contact';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mise à jour des contacts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = microtime(true);

        Log::channel('contacts')->info('DEBUT -- exportation des contacts... -- DEBUT');

        try {
            $contacts = $this->fetchContacts();
            Log::channel('contacts')->info('Contacts récupérés avec succès.');
            Log::channel('contacts')->info('Nombre de contacts récupérés : ' . count($contacts));

            $filePath = '/mnt/partage_windows/Exp_Contacts.txt';
            $file = fopen($filePath, 'w');
            if ($file === false) {
                Log::channel('contacts')->error('Impossible d\'ouvrir le fichier : ' . $filePath);
                return;
            }
            foreach ($contacts as $contact) {
                $line = implode(';', (array)$contact) . "\n";
                fwrite($file, $line);
            }
            fclose($file);
            Log::channel('contacts')->info('Fichier créé avec succès : ' . $filePath);
            Log::channel('contacts')->info('Nombre de lignes écrites dans le fichier : ' . count($contacts));
        } catch (\Exception $e) {
            Log::channel('contacts')->error('Erreur lors de la récupération des contacts : ' . $e->getMessage());
            return;
        }
        $duration = microtime(true) - $start;
        Log::channel('contacts')->info('Durée de l\'exécution : ' . $duration . ' secondes');
        Log::channel('contacts')->info('FIN -- exportation des contacts... -- FIN');

    }

    private function fetchContacts()
    {
        $contacts= DB::connection('pgsql')->select("SELECT DISTINCT
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
        //nettoyage de tous les champs avec clean_texte
        foreach ($contacts as $contact) {
            $contact->relcont_adpro = (new FormatTexte)->clean_txt($contact->relcont_adpro);
            $contact->relcont_adpro_codpost = (new FormatTexte)->clean_txt($contact->relcont_adpro_codpost);
            $contact->relcont_adpro_ville = (new FormatTexte)->clean_txt($contact->relcont_adpro_ville);
            $contact->relcont_adpro_tel = (new FormatTexte)->clean_txt($contact->relcont_adpro_tel);
            $contact->relcont_adpro_pays = (new FormatTexte)->clean_txt($contact->relcont_adpro_pays);
            $contact->relcont_adpro_email = (new FormatTexte)->clean_txt($contact->relcont_adpro_email);
            $contact->relcont_prenom = (new FormatTexte)->clean_txt($contact->relcont_prenom);
            $contact->relcont_nom = (new FormatTexte)->clean_txt($contact->relcont_nom);
        }
        return $contacts;
    }

}
