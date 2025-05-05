<?php

namespace App\Tools;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AccessoiresFTP
{
    public function sendToFTP(string $File_name): void
    {
        $localPath = '/mnt/partage_windows/' . $File_name;
        $remotePath = 'Imports_Automatiques/PHP/' . $File_name;

        if (!file_exists($localPath)) {
            Log::error("Fichier local introuvable : $localPath");
            throw new \RuntimeException("Le fichier local '$localPath' est introuvable.");
        }

        try {
            $stream = fopen($localPath, 'r');
            if (!$stream) {
                throw new \RuntimeException("Impossible d'ouvrir le fichier : $localPath");
            }
            // Assurez-vous que le disque SFTP est configuré dans config/filesystems.php
            Storage::disk('sftp')->put($remotePath, $stream);
            fclose($stream);

            Log::info("Fichier transféré avec succès vers SFTP : $remotePath");
        } catch (\Throwable $e) {
            Log::error("Erreur lors du transfert FTP de '$localPath' vers '$remotePath' : " . $e->getMessage());
            throw $e;
        }
    }


}
