<?php

namespace App\Console\Commands;

use App\Tools\AccessoiresFTP;
use Illuminate\Console\Command;

class UpdateEncours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-encours';

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
        $access_ftp = new AccessoiresFTP();
        try {


            $access_ftp->getFTP('Imports_Automatiques/GAMSYS/', 'encours Export.txt', '/mnt/interfas/DEV/YB_linux/Yellowbox/','encours.txt');
        } catch (\Throwable $e) {
            $this->error('Erreur lors de l\'exportation des encours : ' . $e->getMessage());
            return Command::FAILURE;
        }
        $this->info('Fichier encours Export.txt téléchargé avec succès.');

        //TODO : Ajouter l'écriture dans la base de données mysql "interfas" qui sert pour Toucan
        $duration = microtime(true) - $start;
        $this->info('Durée d\'exécution : ' . round($duration, 2) . ' secondes');
        return Command::SUCCESS;

    }
}
