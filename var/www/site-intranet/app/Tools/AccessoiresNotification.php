<?php

namespace App\Tools;

use Illuminate\Support\Facades\Http;

class AccessoiresNotification
{

    public function sendTeamsNotification_Error(string $message)
    {
        $webhookUrl = 'https://interfas.webhook.office.com/webhookb2/a9d50eda-2912-4146-81ff-ddd8c7e98609@0de0ef00-3714-4d44-985b-5663f8f938fc/IncomingWebhook/65e79baba767432ebc4a204378a0c1cd/e00807d5-cfcd-47c7-8422-f65bd6682389/V2WzY2hvl25vE-2qkJ3WIFh8jPbHKcS_Cl0Pj1TaPRmk41';

        $payload = [
            '@type' => 'MessageCard',
            '@context' => 'http://schema.org/extensions',
            'themeColor' => 'd11141',
            'summary' => 'Notification de l\'entreprise Interfas',
            'sections' => [
                [
                    'activityTitle' => 'Notification de la société Interfas',  // Titre de la notification
                    'activitySubtitle' => 'Une tâche a été exécutée avec une erreur.',
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
    public function sendTeamsNotification_Success(string $message)
    {
        $webhookUrl = 'https://interfas.webhook.office.com/webhookb2/a9d50eda-2912-4146-81ff-ddd8c7e98609@0de0ef00-3714-4d44-985b-5663f8f938fc/IncomingWebhook/65e79baba767432ebc4a204378a0c1cd/e00807d5-cfcd-47c7-8422-f65bd6682389/V2WzY2hvl25vE-2qkJ3WIFh8jPbHKcS_Cl0Pj1TaPRmk41';

        $payload = [
            '@type' => 'MessageCard',
            '@context' => 'http://schema.org/extensions',
            'themeColor' => '00b159',
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
