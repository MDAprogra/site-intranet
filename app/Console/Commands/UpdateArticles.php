<?php

namespace App\Console\Commands;

use App\Tools\AccessoiresFTP;
use App\Tools\FormatTexte;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mise à jour des articles de Yellowbox';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //exporter les articles
        $start = microtime(true);
        $channel_success = Log::channel('update_success');
        $channel_errors = Log::channel('update_errors');
        try {
            $articles = $this->getArticles();
        } catch (\Exception $e) {
            $channel_errors->error('[Articles] -> Erreur lors de l\'exportation des articles : ' . $e->getMessage());
            return;
        }

        //ecriture du fichier
        if (count($articles) > 0) {
            try {
                $this->writeFile($articles);
            } catch (\Exception $e) {
                $channel_errors->error('[Articles] -> Erreur lors de l\'écriture du fichier : ' . $e->getMessage());
                return;
            }
        }

        //envoi ftp du fichier
        try {
            $access_ftp = new AccessoiresFTP();
            $access_ftp->sendToFTP('Exp_Articles.txt');
        } catch (\Exception $e) {
            $channel_errors->error('[Articles] -> Erreur lors de l\'envoi FTP : ' . $e->getMessage());
            return;
        }
        $end = microtime(true);
        $executionTime = ($end - $start);
        $channel_success->info('Articles Mise à jour avec succès (' . count($articles) . ' articles) en ' . round($executionTime, 2) . ' secondes');
    }


    private function writeFile($data)
    {

        // Vérification du dossier
        $directory = dirname("/mnt/partage_windows/Exp_Articles.txt");
        if (!is_dir($directory) || !is_writable($directory)) {
            throw new \RuntimeException("Le répertoire n'existe pas ou n'est pas accessible en écriture : $directory");
        }

        // Ouverture du fichier en mode écriture
        $file = fopen("/mnt/partage_windows/Exp_Articles.txt", 'w');
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

    private function getArticles()
    {
        $articles = DB::connection('pgsql')->select("
        SELECT
 fc_references.fo_rep_code AS gest,
	CASE fc_references.fo_site  WHEN  'I' THEN CONCAT( 'Interfas', '|', 'Interfas Nord')  WHEN  'L' THEN  'ILD' WHEN  'N' THEN  'Interfas Nord' ELSE CONCAT( 'Interfas', '|', 'Interfas Nord')  END  AS site,
	fs_stock.st_seq_compt AS st_seq_compt,
	CASE fs_stock.st_client  WHEN  '*' THEN  'III STK' ELSE fs_stock.st_client  END  AS st_client,
	CONCAT(fs_stock.st_modele , '/',fs_stock.st_version_modele )  AS article,
	fs_stock.st_art_ref_client AS ref_client,
	fs_stock.st_lib_1_conso AS libelle,
	fs_stock.st_q_physique AS qte,
	fs_stock.st_px_vente_le_1000 AS prix_vente,
	fs_stock.st_pmp AS PMP,
	fd_types_prod.typpro_lib AS typpro_lib,
	( ( SELECT SUM( lot_q_en_m_f )  AS Q
FROM fs_lot
WHERE ( ( ( ( lot_statut =  1) AND ( lot_genre =  'P') ) AND ( lot_mag =  'S') ) AND ( fs_lot.lot_article = fs_stock.st_seq_compt ) )
GROUP BY lot_article   )  )  AS Qte_lot,
	( fs_stock.st_q_physique - ( ( SELECT SUM( lot_q_en_m_f )  AS Q
FROM fs_lot
WHERE ( ( ( ( lot_statut =  1) AND ( lot_genre =  'P') ) AND ( lot_mag =  'S') ) AND ( fs_lot.lot_article = fs_stock.st_seq_compt ) )
GROUP BY lot_article   )  ) )  AS en_quarantaine,
	fs_stock.st_dernier_mvt AS dernier_mvt,
	fs_stock.st_q_cmdee AS en_fab,
	fs_stock.st_q_reservee AS en_cde,
	fs_stock.st_q_min_de_reappro AS q_min_reapro,
	fs_stock.st_niveau_de_reappro AS q_niveau_reapro,
	fs_stock.st_niveau_de_secu AS stk_secu,
	( ( ( SELECT SUM( lot_q_en_m_f )  AS Q
FROM fs_lot
WHERE ( ( ( ( lot_statut =  1) AND ( lot_genre =  'P') ) AND ( lot_mag =  'S') ) AND ( fs_lot.lot_article = fs_stock.st_seq_compt ) )
GROUP BY lot_article   )  ) - fs_stock.st_niveau_de_secu )  AS stk_moins_secu,
	( ( ( ( SELECT SUM( lot_q_en_m_f )  AS Q
FROM fs_lot
WHERE ( ( ( ( lot_statut =  1) AND ( lot_genre =  'P') ) AND ( lot_mag =  'S') ) AND ( fs_lot.lot_article = fs_stock.st_seq_compt ) )
GROUP BY lot_article   )  ) + fs_stock.st_q_cmdee ) - fs_stock.st_q_reservee )  AS potentielle
FROM
	(
		fs_stock
		LEFT OUTER JOIN
		fc_references
		ON fs_stock.st_client = fc_references.fo_reference
	)
	LEFT OUTER JOIN
	fd_types_prod
	ON fd_types_prod.typpro_code = fs_stock.st_type
WHERE
	(
	fs_stock.st_genre = 'P'
	AND	fs_stock.st_inactif = 0
	AND	fs_stock.st_client NOT IN ('ZZZ')
	AND	fs_stock.st_art_date_crea >= current_date
)
ORDER BY
	st_seq_compt DESC;
        ");

        $formatter = new FormatTexte();
        foreach ($articles as $article) {
            foreach ($article as $key => $value) {
                if (is_string($value)) {
                    $article->$key = $formatter->clean_txt($value);
                }
            }
            $formatter->getIdYB($article->gest);
            //$article->gest = $this->YBidd($article->gest);
        }
        return $articles;
    }

    private function YBidd(string $repCode): string
    {
        $result = DB::connection('mysql2')->selectOne(
            "SELECT IddYB FROM users WHERE REP_CODE = ? LIMIT 1", [$repCode]
        );
        return $result->IddYB ?? 'Administrateur';
    }
}

