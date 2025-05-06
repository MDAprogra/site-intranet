<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
        $this->info('Début de la commande');

        try {
            // Récupérer les données
            $rawData = $this->getAttenteMatierePremiere();

            if (empty($rawData)) {
                $this->error('Aucune donnée trouvée.');
                $this->sendTeamsNotification('Erreur : Aucune donnée trouvée.');
                return;
            }

            // Transformer en tableau de tableaux avec en-têtes
            $headers = array_keys((array) $rawData[0]);
            $rows = array_map(fn ($item) => array_values((array) $item), $rawData);
            $data = array_merge([$headers], $rows);

            // Créer le fichier Excel
            $filePath = $this->createExcel($data);

            $this->info("Fichier Excel créé : {$filePath}");
            $this->info('Fin de la commande');

            // Envoi Notification à Webhook Teams
            $this->sendTeamsNotification("Le fichier Excel a été créé avec succès : {$filePath}");

        } catch (\Exception $e) {
            $this->error("Erreur : " . $e->getMessage());
            // Envoi notification en cas d'erreur
            $this->sendTeamsNotification("Erreur dans la commande : " . $e->getMessage());
        }
    }

    private function getAttenteMatierePremiere()
    {
        return DB::connection('pgsql')->select("SELECT OPRE_SAL, OPRE_POSTE, OPRE_DATE, 
            to_char(OPRE_H_DEBUT, 'HH24:MI') as \"Heure départ\",
            to_char(OPRE_H_FIN, 'HH24:MI')   as \"Heure fin\",
            OPRE_DUREE, OPRE_TAUX_1, OPRE_TAUX_2, OPRE_QUANTITE, OPRE_CODE_OP, OPRE_LIBELLE_OPE
            FROM FP_OPERA_REEL
            WHERE OPRE_DOSSIER = ''
            AND OPRE_DATE BETWEEN '01/02/2025' AND '18/02/2025'
            AND OPRE_LIBELLE_OPE = 'Attente Matière Première'");
    }

    private function createExcel(array $data)
    {
        $filename = 'Exp_AMP_' . date('Y-m-d') . '.xlsx';
        $fullPath = '/mnt/partage_windows/' . $filename;

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

        // Sauvegarde sur le disque 'partage_windows' (défini dans filesystems.php)
        Excel::store($export, $filename, 'partage_windows');

        return $fullPath;
    }

    private function sendTeamsNotification(string $message)
    {
        $webhookUrl = 'https://interfas.webhook.office.com/webhookb2/a9d50eda-2912-4146-81ff-ddd8c7e98609@0de0ef00-3714-4d44-985b-5663f8f938fc/IncomingWebhook/ac680532dc66449eae25381bb04fc907/e00807d5-cfcd-47c7-8422-f65bd6682389/V2s-xHSUl-kClwb1A3c22YrFWZ7UahDHiCpvGt7P_nSIM1';

        $payload = [
            '@type' => 'MessageCard',
            '@context' => 'http://schema.org/extensions',
            'themeColor' => '00b159', // Couleur de la barre de titre (bleu Microsoft)
            'summary' => 'Notification de l\'entreprise Interfas',
            'sections' => [
                [
                    'activityTitle' => 'Notification de la société Interfas',  // Titre de la notification
                    'activitySubtitle' => 'Une tâche a été exécutée avec succès.',
                    'activityImage' => 'https://www.interfas.com/favicon.ico', // Icône ou image (ajustez avec le logo de votre société)
                    'facts' => [
                        [
                            'name' => 'Message',
                            'value' => $message, // Le message que vous souhaitez envoyer
                        ],
                    ],
                ],
            ],
        ];

        // Envoi de la notification à Teams via le Webhook
        Http::post($webhookUrl, $payload);
    }


}
