<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Tools\FormatTexte;
use App\Tools\AccessoiresFTP;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class UpdateContact extends Command
{
    protected $signature = 'app:update-contact';
    protected $description = 'Mise à jour des contacts de Yellowbox';

    public function handle()
    {
        $start = microtime(true);
        $channel_success = Log::channel('update_success');
        $channel_errors = Log::channel('update_errors');
        try {
            $formatTxt = new FormatTexte();

            $contacts = $this->fetchContacts();
            $filePath = '/mnt/interfas/DEV/YB_linux/Yellowbox/';
            $fileName = 'Exp_Contacts.txt';
            $formatTxt->YBcreateFileTXT($filePath , $fileName,$contacts);
            //$this->writeContactsToFile($contacts, $filePath);
            try {
                $access_ftp = new AccessoiresFTP();
                $name = 'Exp_Contacts.txt';
                $access_ftp->sendToFTP($name);
            } catch (\Exception $e) {
                $channel_errors->error('[Contacts] -> Erreur lors de l\'envoi du fichier sur le serveur FTP : ' . $e->getMessage());
                return Command::FAILURE;
            }

            $duration = round(microtime(true) - $start, 2);
        } catch (Throwable $e) {
            $channel_errors->error('[Contacts] -> Erreur : ' . $e->getMessage());
            return Command::FAILURE;
        }
        $channel_success->info('BL Mise à jour avec succès (' . count($contacts) . ' articles) en ' . round($duration, 2) . ' secondes');
        return Command::SUCCESS;

    }

    private function fetchContacts(): array
    {
        $formatter = new FormatTexte();
        $funcIdYB = new FormatTexte();

        $sql = file_get_contents(database_path('sql/Contacts.sql'));
        $contacts = DB::connection('pgsql')->select($sql);

        foreach ($contacts as $contact) {
            $contact->gestionnaire = $funcIdYB->getIdYB($contact->gestionnaire);
        }

        return $contacts;
    }

    private function writeContactsToFile(array $contacts, string $filePath): void
    {

        // Vérification du dossier
        $directory = dirname($filePath);
        if (!is_dir($directory) || !is_writable($directory)) {
            throw new RuntimeException("Le répertoire n'existe pas ou n'est pas accessible en écriture : $directory");
        }

        // Ouverture du fichier
        $file = fopen($filePath, 'w');
        if (!$file) {
            throw new RuntimeException("Impossible d'ouvrir le fichier en écriture : $filePath");
        }

        foreach ($contacts as $contact) {
            $line = implode(';', (array)$contact) . "\n";
            fwrite($file, $line);
        }

        fclose($file);
    }
}
