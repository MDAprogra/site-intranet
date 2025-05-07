<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestConnectFTP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-connect-ftp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permet de tester la connexion SFTP de YellowBox';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Tentative de connexion SFTP...');

        try {
            $files = Storage::disk('sftp')->files();

            $this->info('Connexion SFTP réussie!');
            $this->info('Nombre de fichiers dans la racine : ' . count($files));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Erreur de connexion SFTP : ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

}
