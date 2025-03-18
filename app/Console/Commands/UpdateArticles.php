<?php

namespace App\Console\Commands;

use App\upd_yb\updates;
use Illuminate\Console\Command;
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
    protected $description = 'Création d\'un fichier CSV contenant les articles à jour';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $update = new updates();
        if ($update->upd_articles()) {
            $this->info('Mise à jour des articles terminée avec succès.');
            Log::info('Mise à jour des articles terminée avec succès.');
            return 0; // Succès
        } else {
            $this->error('Erreur lors de la mise à jour des articles.');
            Log::error('Erreur lors de la mise à jour des articles.');
            return 1; // Échec
        }
    }
}
