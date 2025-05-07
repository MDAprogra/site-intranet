<?php

namespace App\Console\Commands;

use App\Tools\AccessoiresNotification;
use App\Tools\FormatTexte;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Http;

class GetAttenteMatierePremiere extends Command
{
    protected $signature = 'app:get-attente-matiere-premiere';
    protected $description = 'Exporte les opérations Attente Matière Première dans un fichier Excel';

    public function handle()
    {
        $accessNotif = new AccessoiresNotification();
        try {
            // Récupérer les données
            $rawData = $this->getAttenteMatierePremiere();


            if (empty($rawData)) {
                $this->error('Aucune donnée trouvée.');
                $accessNotif->sendTeamsNotification_Success("AMP -> Aucune donnée trouvée.");
                return;
            }

            // Transformer en tableau de tableaux avec en-têtes
            $headers = array_keys((array)$rawData[0]);
            $rows = array_map(fn($item) => array_values((array)$item), $rawData);
            $data = array_merge([$headers], $rows);

            // Créer le fichier Excel
            $date = date('Y-m-d');
            //Si la date du jour est un Lundi on va chercher les données du Vendredi
            if (date('N') == 1) {
                $date = date('Y-m-d', strtotime('-3 days'));
            } else {
                $date = date('Y-m-d', strtotime('-1 day'));
            }


            $formatTxt = new FormatTexte();
            $fileName = 'Exp_AMP_' . $date . '.xlsx';
            $filePath = '/mnt/interfas/DEV/YB_linux/AMP/' . $fileName;
            $formatTxt->createFileXLSX($fileName,$filePath,$data,'AMP');

            // Archiver le fichier Excel J-1
            $this->archiveExcel();

            $this->info('Fin de la commande');

            // Envoi Notification à Webhook Teams
            //$accessNotif->sendTeamsNotification_Success("Le fichier Excel a été créé avec succès : {$filePath}");

        } catch (\Exception $e) {
            $this->error("Erreur : " . $e->getMessage());
            // Envoi notification en cas d'erreur
            $accessNotif->sendTeamsNotification_Error("Erreur dans la commande : " . $e->getMessage());
        }
    }

    private function getAttenteMatierePremiere()
    {
        //Date du jour
        $date = date('Y-m-d');
        //Si la date du jour est un Lundi on va chercher les données du Vendredi
        if (date('N') == 1) {
            $date = date('Y-m-d', strtotime('-3 days'));
        } else {
            $date = date('Y-m-d', strtotime('-1 day'));
        }

        $sql = file_get_contents(database_path('sql/AMP.sql'));
        // Récupérer les données de la base de données
        return DB::connection('pgsql')->select($sql, [$date]);
    }

    private function createExcel(array $data)
    {
        $filename = 'Exp_AMP_' . date('d-m-Y', strtotime('-1 day')) . '.xlsx';
        $fullPath = '/mnt/partage_windows/AMP/' . $filename;

        $export = new class($data) implements FromArray, WithHeadings {
            protected $data;

            public function __construct(array $data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                return array_slice($this->data, 1); // Données (sans en-têtes)
            }

            public function headings(): array
            {
                return $this->data[0]; // En-têtes
            }
        };

        Excel::store($export, $filename, 'AMP');

        return $fullPath;
    }

    private function archiveExcel()
    {
        $yesterday = date('d-m-Y', strtotime('-2 day'));
        $filename = 'Exp_AMP_' . $yesterday . '.xlsx';
        $sourcePath = '/mnt/partage_windows/AMP/' . $filename;

        // Utilisation du disque AMP_Archive défini dans config/filesystems.php
        $archiveDisk = Storage::disk('AMP_Archive');
        $archivePath = $filename;

        if (!file_exists($sourcePath)) {
            return;
        }

        try {
            // Lecture du fichier source
            $fileContents = @file_get_contents($sourcePath);
            if ($fileContents === false) {
                throw new \Exception("Échec de la lecture du fichier : {$sourcePath}");
            }

            // Écriture dans le dossier d’archives via le disque Laravel
            if (!$archiveDisk->put($archivePath, $fileContents)) {
                throw new \Exception("Échec de l’écriture dans le dossier d’archives : {$archiveDisk->path($archivePath)}");
            }

            // Suppression du fichier source
            if (!@unlink($sourcePath)) {
                throw new \Exception("Fichier archivé mais échec de la suppression du fichier source : {$sourcePath}");
            }

            $this->info("Fichier archivé avec succès : {$archiveDisk->path($archivePath)}");
        } catch (\Throwable $e) {
            $this->error("Erreur lors de l’archivage : " . $e->getMessage());
        }
    }


}
