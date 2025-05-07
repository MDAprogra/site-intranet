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
            $formatTxt = new FormatTexte();
            try {
                $formatTxt->YBcreateFileTXT('Exp_Articles.txt', '/mnt/interfas/DEV/YB_linux/Yellowbox/', $articles);
                //$this->writeFile($articles);
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

    private function getArticles()
    {

        $sql = file_get_contents(database_path('sql/Articles.sql'));
        $articles = DB::connection('pgsql')->select($sql);
        $formatter = new FormatTexte();
        foreach ($articles as &$article) { // Utilisation de la référence pour modifier l'objet directement
            foreach ($article as &$value) { // Utilisation de la référence pour modifier la valeur directement
                if (is_string($value)) {
                    $value = $formatter->clean_txt($value);
                }
            }
            $article->gest = $this->YBidd($article->gest);
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

