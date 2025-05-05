<?php

namespace App\Console\Commands;

use App\Tools\FormatTexte;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateSociete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-societes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mise à jour des sociétés de Yellowbox';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = microtime(true);
        $channel = Log::channel('societes');
        $channel->info('DEBUT -- exportation des societes -- DEBUT');

        try {
            // Étape 1 : Récupération des sociétés
            $this->info('Récupération des sociétés...');
            $societes = $this->fetchSociete();
            $this->info('Sociétés récupérées avec succès. Nombre : ' . count($societes));
            $channel->info('Récupération des sociétés terminée. Nombre : ' . count($societes));

            // Étape 2 : Écriture dans le fichier
            $this->info('Écriture des sociétés dans le fichier...');
            $this->writeFile($societes);
            $this->info('Fichier créé avec succès.');
            $channel->info('Fichier des sociétés écrit avec succès.');

            // Étape 3 : Affichage du temps d'exécution
            $executionTime = round(microtime(true) - $start, 2);
            $this->info("Temps d'exécution : {$executionTime} secondes");
            $channel->info("FIN -- exportation des societes -- Durée : {$executionTime} secondes");

            return 0; // succès
        } catch (\Throwable $e) {
            $message = 'Erreur lors de l\'exportation des sociétés : ' . $e->getMessage();
            $this->error($message);
            $channel->error($message, [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
            return 1; // échec
        }
    }


    private function writeFile($data)
    {
        $channel = Log::channel('societes');

        // Vérification du dossier
        $directory = dirname("/mnt/partage_windows/Exp_Societes.txt");
        if (!is_dir($directory) || !is_writable($directory)) {
            throw new \RuntimeException("Le répertoire n'existe pas ou n'est pas accessible en écriture : $directory");
        }

        // Ouverture du fichier en mode écriture
        $file = fopen("/mnt/partage_windows/Exp_Societes.txt", 'w');
        if (!$file) {
            throw new \RuntimeException("Impossible d'ouvrir le fichier en écriture : Exp_Societes.txt");
        }
        // Écriture des données dans le fichier
        foreach ($data as $row) {
            $line = implode(';', (array)$row) . "\n";
            fwrite($file, $line);
        }
        fclose($file);
        $channel->info('Fichier créé : Exp_Societes.txt');
    }

    private function fetchSociete()
    {
        $societes = DB::connection('pgsql')->select("SELECT
	CASE fo_type_c_f_p WHEN 'C' THEN 'Client' WHEN 'P' THEN 'Prospect' WHEN 'F' THEN 'Fournisseur' END AS Typ ,
	fo_reference,
	fo_nom_1,
	fo_cpte_gene,
	fo_date_crea AS date_creation ,
	(SELECT
		fosite_rep_code
	FROM
		fc_gestion_client_site
	WHERE
		fosite_reference=fo_reference
		AND
		fosite_site =''
	) AS gest ,
	fo_code_maison_mere,
	REPLACE(REPLACE(REPLACE(fo_adresse_1,CHR(13),'##'),CHR(10),'##'),CHR(9),'     ') AS fo_adresse_1,
	REPLACE(REPLACE(REPLACE(fo_code_postal,CHR(13),''),CHR(10),''),CHR(9),'     ') AS fo_code_postal ,
	REPLACE(REPLACE(REPLACE( fo_ville ,CHR(13),''),CHR(10),''),CHR(9),'     ') AS fo_ville,
	REPLACE(REPLACE(REPLACE( fo_telephone ,CHR(13),''),CHR(10),''),CHR(9),'     ') AS fo_telephone,
	fo_devise,
	CONCAT(fo_lettre_tva,REPLACE(REPLACE(REPLACE( fo_no_tva ,CHR(13),''),CHR(10),''),CHR(9),'     ')) AS code_tva,
	fo_pays ,
	REPLACE(REPLACE(REPLACE( fo_adress_email ,CHR(13),''),CHR(10),''),CHR(9),'     ') AS fo_adress_email,
	CASE fo_site WHEN 'I' THEN CONCAT('Interfas','|','Interfas Nord') WHEN 'L' THEN 'ILD' WHEN 'N' THEN 'Interfas Nord' ELSE CONCAT('Interfas','|','Interfas Nord') END AS site,
	fo_siret AS siret,
	 (SELECT  fosite_niveau_1_message FROM fc_gestion_client_site WHERE fo_reference=fosite_reference LIMIT 1) AS msg,
	 CASE (SELECT  fosite_niveau_1_action FROM fc_gestion_client_site WHERE fo_reference=fosite_reference LIMIT 1) WHEN 0 THEN '' ELSE 'Bloqué' END AS statut,
	 fr.fo_seq AS sequentiel
	 
FROM
	fc_references fr
WHERE
	fo_inactif ='0'
	AND
	(
		fo_date_modif >=current_date OR fo_date_crea >=current_date OR fo_date_maj >=current_date
	)
	AND
	fo_reference <>'*'
	AND
	fo_nom_1 <>''
	AND
	fo_nom_1 IS NOT NULL
	AND
	fo_reference NOT IN ('ZZZZZZZZ','ZZZ')
	AND
	fo_type_c_f_p IN('C','P','F')
UNION
SELECT
	'Fournisseur' AS Typ ,
	fo_reference,
	fo_nom_1,
	fo_cpte_gene,
	fo_date_crea AS date_creation ,
	't.mullinghausen' AS gest ,
	fo_code_maison_mere,
	REPLACE(REPLACE(REPLACE(fo_adresse_1,CHR(13),'##'),CHR(10),'##'),CHR(9),'     ') AS fo_adresse_1,
	REPLACE(REPLACE(REPLACE(fo_code_postal,CHR(13),''),CHR(10),''),CHR(9),'     ') AS fo_code_postal ,
	REPLACE(REPLACE(REPLACE( fo_ville ,CHR(13),''),CHR(10),''),CHR(9),'     ') AS fo_ville,
	REPLACE(REPLACE(REPLACE( fo_telephone ,CHR(13),''),CHR(10),''),CHR(9),'     ') AS fo_telephone,
	fo_devise,
	CONCAT(fo_lettre_tva,REPLACE(REPLACE(REPLACE( fo_no_tva ,CHR(13),''),CHR(10),''),CHR(9),'     ')) AS code_tva,
	fo_pays ,
	REPLACE(REPLACE(REPLACE( fo_adress_email ,CHR(13),''),CHR(10),''),CHR(9),'     ') AS fo_adress_email,
	CASE fo_site WHEN 'I' THEN CONCAT('Interfas','|','Interfas Nord') WHEN 'L' THEN 'ILD' WHEN 'N' THEN 'Interfas Nord' ELSE CONCAT('Interfas','|','Interfas Nord') END AS site,
	'' AS siret
		,
	 (SELECT  fosite_niveau_1_message FROM fc_gestion_client_site WHERE fo_reference=fosite_reference LIMIT 1) AS msg,
	 CASE (SELECT  fosite_niveau_1_action FROM fc_gestion_client_site WHERE fo_reference=fosite_reference LIMIT 1) WHEN 0 THEN '' ELSE 'Bloqué' END AS statut,
	  fr.fo_seq AS sequentiel
FROM
	fc_references fr
WHERE
	fo_inactif ='0'
	AND
	fo_reference <>'*'
	AND
	(
		fo_date_modif >=current_date OR fo_date_crea >=current_date OR fo_date_maj >=current_date
	)
	AND
	fo_nom_1 <>''
	AND
	fo_nom_1 IS NOT NULL
	AND
	fo_reference NOT IN ('ZZZZZZZZ','ZZZ')
	AND
	fo_type_c_f_p ='F';");

        $formatter = new FormatTexte();

        foreach ($societes as $societe) {
            foreach ($societe as $key => $value) {
                if (is_string($value)) {
                    $societe->$key = $formatter->clean_txt($value);
                }
            }
            $societe->gest = $this->YBidd($societe->gest);
        }

        return $societes;
    }

    private function YBidd(string $repCode): string
    {
        $result = DB::connection('mysql2')->selectOne(
            "SELECT IddYB FROM users WHERE REP_CODE = ? LIMIT 1", [$repCode]
        );

        return $result->IddYB ?? 'Administrateur';
    }
}
