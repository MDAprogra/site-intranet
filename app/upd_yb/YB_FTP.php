<?php

namespace App\upd_yb;

use Illuminate\Support\Facades\Log;
use phpseclib3\Net\SFTP;

class YB_FTP
{
    public function uploadFile($localFile, $remoteFile, $server, $username, $password)
    {
        $sftp = new SFTP($server);


        if (!$sftp->login($username, $password)) {
            Log::error('Échec de la connexion SFTP');

            return false;
        }

        if ($sftp->put($remoteFile, $localFile, SFTP::SOURCE_LOCAL_FILE)) {
            Log::info('Fichier SFTP téléchargé avec succès');

            return true;
        } else {
            Log::error('Échec du téléchargement du fichier SFTP');

            return false;
        }
    }
}
