<?php

namespace App\Console\Commands;

use App\Tools\AccessoiresFTP;
use App\Tools\FormatTexte;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateDevis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-devis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mise à jour des devis de Yellowbox';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = microtime(true);
        try {
            $formatTxt = new FormatTexte();
            $access_ftp = new AccessoiresFTP();

            $filePath = '/mnt/interfas/DEV/YB_linux/Yellowbox/';
            $fileName = 'Exp_Devis.txt';
            $devis = $this->fetchDevis();
            $formatTxt->YBcreateFileTXT($fileName, $filePath, $devis);

            $access_ftp->sendToFTP($fileName);
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $message = 'Erreur lors de l\'exportation des devis : ' . $e->getMessage();
            $this->error($message);
            return Command::FAILURE;
        }
    }

    private function fetchDevis()
    {
        $sql = file_get_contents(database_path('sql/Devis.sql'));
        return DB::connection('pgsql')->select($sql);
    }
}
