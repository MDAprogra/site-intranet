<?php

namespace App\Console\Commands;

use App\Tools\AccessoiresFTP;
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

        try {
            $societes = $this->fetchSociete();

            $formatter = new FormatTexte();
            $filePath = '/mnt/interfas/DEV/YB_linux/Yellowbox/';
            $fileName = 'Exp_Societes.txt';
            $formatter->YBcreateFileTXT($fileName, $filePath, $societes);

            try {
                $access_ftp = new AccessoiresFTP();
                $access_ftp->sendToFTP('Exp_Societes.txt');
            }
            catch (\Throwable $e) {
                $this->error('Erreur lors de l\'envoi du fichier sur le FTP : ' . $e->getMessage());
            }
            $executionTime = round(microtime(true) - $start, 2);
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $message = 'Erreur lors de l\'exportation des sociétés : ' . $e->getMessage();
            $this->error($message);


            return Command::FAILURE;
        }
    }

    private function fetchSociete()
    {
        $sql = file_get_contents(database_path('sql/Societes.sql'));
        $societes = DB::connection('pgsql')->select($sql);

        $formatter = new FormatTexte();

        foreach ($societes as $societe) {
            $societe->gest = $formatter->getIdYB($societe->gest);
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
