<?php

namespace App\Console\Commands;

use App\Tools\AccessoiresFTP;
use App\Tools\FormatTexte;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UpdateBL extends Command
{
    protected $signature = 'app:update-bl';
    protected $description = 'Mise à jour des BL de Yellowbox';

    public function handle()
    {
        $start = microtime(true);
        $channel_success = Log::channel('update_success');
        $channel_errors = Log::channel('update_errors');
        $bonsLivraison = [];

        try {
            $bonsLivraison = $this->fetchBonsLivraison();
            $bonsLivraison = $this->enrichData($bonsLivraison);

            $filePath = '/mnt/interfas/DEV/YB_linux/Yellowbox/';
            $fileName = 'Exp_BL.txt';
            $formatTxt = new FormatTexte();


            try {
                $formatTxt->YBcreateFileTXT($fileName,$filePath,$bonsLivraison);
                $this->info('[BL] -> Fichier TXT créé avec succès : ' . $filePath);
            }
            catch (Exception $e) {
                $this->error('[BL] -> Erreur lors de la création du fichier TXT : ' . $e->getMessage());
                return;
            }


            //$this->exportToTxt($bonsLivraison, $filePath);

            $access_ftp = new AccessoiresFTP();
            $access_ftp->sendToFTP('Yellowbox/Exp_BL.txt');
        } catch (Exception $e) {
            $channel_errors->error('[BL] -> Erreur lors du processus de mise à jour des BL : ' . $e->getMessage());
        }
        $duration = microtime(true) - $start;
        $channel_success->info('BL Mise à jour avec succès (' . count($bonsLivraison) . ' articles) en ' . round($duration, 2) . ' secondes');
    }

    //TODO : Ajouter données transports
    private function fetchBonsLivraison(): array
    {
        try {
            $sql = file_get_contents(database_path('sql/BL.sql'));
            $BL = DB::connection('pgsql')->select($sql);

//            $BL = DB::connection('pgsql')->select("
//                SELECT d.BO_NO                                  AS numero_bon_livraison, --OK
//       Coalesce(d.BO_NO_DOSSIER, '')            AS numero_dossier, --OK
//       d.BO_REF_DE_LIVRAISON                    AS reference_livraison, --OK
//       BO_CODE_REP                              AS code_representant, --OK
//       d.BO_SITE                                AS code_site, --OK
//       d.BO_GENRE_DE_LIVRAISON                  AS genre_livraison, --OK
//       d.BO_TYPE_LIVRAISON                      AS type_livraison, --OK
//       d.BO_STATUT_LIVRAISON                    AS statut_livraison, --OK
//       Round(d.BO_QUANT_LIVREE_TOTAL)           AS QaLivrer, --OK
//       Cast(dMvt.MVT_LIVRE AS DOUBLE PRECISION) AS QLivree, --OK
//       Cast(MVT_Q AS DOUBLE PRECISION)          AS QCommandee, --OK
//       d.BO_ADRESSE_1                           AS adresse_livraison_1, --OK
//       d.BO_ADRESSE_2                           AS adresse_livraison_2, --OK
//       d.BO_ADRESSE_3                           AS adresse_livraison_3, --OK
//       d.BO_CODE_POSTAL                         AS code_postal_livraison, --OK
//       d.BO_VILLE                               AS ville_livraison, --OK
//       d.BO_ADFACT_ADRESSE_1                    AS adresse_facturation_1, --OK
//       d.BO_ADFACT_ADRESSE_2                    AS adresse_facturation_2, --OK
//       d.BO_ADFACT_ADRESSE_3                    AS adresse_facturation_3, --OK
//       d.BO_ADFACT_CODE_POST                    AS code_postal_facturation, --OK
//       d.BO_ADFACT_VILLE                        AS ville_facturation, --OK
//       d.BO_DATE_SOUHAITEE                      AS date_souhaitee_livraison, --OK
//       d.BO_DATE_IMPERATIVE                     AS date_imperative_livraison, --OK
//       d.BO_DATE_REELLE                         AS date_reelle_livraison, --OK
//       CONCAT(
//               d.BO_DESCRIPTIF_1, '<br>',
//               d.BO_DESCRIPTIF_2, '<br>',
//               d.BO_DESCRIPTIF_3, '<br>',
//               d.BO_DESCRIPTIF_4, '<br>',
//               d.BO_DESCRIPTIF_5
//       )                                        AS descriptif_livraison, --OK
//       dCli.FO_REFERENCE                    AS reference_client --OK
//FROM ((((FF_LIVRAISON d LEFT JOIN FC_GESTION_CLIENT_SITE dCliSite
//    ON dCliSite.FOSITE_SITE = '' AND dCliSite.FOSITE_REFERENCE = d.BO_REF)
//    LEFT JOIN FC_REFERENCES dCli ON dCli.FO_REFERENCE = d.BO_REF LEFT JOIN FS_LIGNE_MVT dMvt
//        ON dMvt.MVT_TYP_MVT = 3 AND dMvt.MVT_IDENTIFIANT_LIGNE_CMD = d.BO_NO_DOSSIER AND
//           d.BO_TYPE_LIVRAISON = 1) LEFT JOIN FS_STOCK dMvtStk
//       ON dMvtStk.ST_SEQ_COMPT = dMvt.MVT_SEQ_ARTICLE) LEFT JOIN FS_TARIF_PF dMvtStkTar
//      ON dMvtStk.ST_CODE_TARIF <> '' AND dMvtStkTar.PF_CODE = dMvtStk.ST_CODE_TARIF)
//         LEFT JOIN FF_CODE_PAYS_PAR_CP dpays ON dpays.PTVA_CODE = d.BO_PAYS
//WHERE ((d.bo_date_reelle >= CURRENT_DATE))
//ORDER BY BO_NO desc;
//            ");
        } catch (Exception $e) {
            Log::channel('update_errors')->error('[BL] -> Erreur lors de la récupération des bons de livraison : ' . $e->getMessage());
            throw $e; // Rethrow the exception to be caught in the handle method
        }
        return $BL;
    }

    private function enrichData(array $bons): array
    {
        foreach ($bons as $row) {
            $row->code_representant = $this->getYBidd($row->code_representant);
            $row->genre_livraison = $this->convertGenreLivraison($row->genre_livraison);
            $row->type_livraison = $this->convertTypeLivraison($row->type_livraison);
            $row->statut_livraison = $this->convertStatutLivraison($row->statut_livraison);
        }

        return $bons;
    }

    private function getYBidd(string $repCode): string
    {
        $result = DB::connection('mysql2')->selectOne(
            "SELECT IddYB FROM users WHERE REP_CODE = ? LIMIT 1", [$repCode]
        );

        return $result->IddYB ?? 'Administrateur';
    }

    private function exportToTxt(array $data, string $filePath): void
    {
        $handle = fopen($filePath, 'w+'); // Utilisez 'w+' pour la lecture/écriture et créer le fichier s'il n'existe pas

        if ($handle === false) {
            throw new \RuntimeException("Impossible d’ouvrir le fichier pour écriture : $filePath");
        }

        // Définir l'encodage pour l'écriture
        stream_set_write_buffer($handle, 0); // Désactiver la mise en tampon pour une écriture immédiate
        fwrite($handle, "\xEF\xBB\xBF"); // Ajouter le BOM UTF-8 (Byte Order Mark)

        // Écriture de l’en-tête
        $firstRow = (array)$data[0];
        $header = implode(';', array_keys($firstRow));
        fwrite($handle, $header . PHP_EOL);

        // Écriture des lignes de données
        foreach ($data as $row) {
            $cleanRow = array_map(function ($value) {
                if (!is_scalar($value)) {
                    return '';
                }
                $value = str_replace(["\r", "\n", '"', ';'], ' ', (string)$value);
                return trim($value);
            }, (array)$row);

            $line = implode(';', $cleanRow);
            fwrite($handle, $line . PHP_EOL);
        }

        fclose($handle);
    }

    private function convertGenreLivraison(string $genre): string
    {
        return match ($genre) {
            '0' => 'Produit Fini',
            '1' => 'Produit Semi-fini',
            '2' => 'Justificatifs vers le client',
            '3' => 'Epreuves',
            '4' => 'Divers',
            default => 'Inconnu',
        };
    }

    private function convertTypeLivraison(string $type): string
    {
        return match ($type) {
            '0' => 'Sur Production',
            '1' => 'Sur Stock',
            '2' => 'Vers le Stock',
            default => 'Inconnu',
        };
    }

    private function convertStatutLivraison(string $statut): string
    {
        return match ($statut) {
            '0' => 'En Preparation',
            '1' => 'Pret a Livrer',
            '2' => 'En Cours de Livraison',
            '3' => 'Livre',
            '4' => 'Cloture',
            default => 'Inconnu',
        };
    }
}
