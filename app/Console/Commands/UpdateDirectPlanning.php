<?php

namespace App\Console\Commands;

use App\Tools\FormatTexte;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UpdateDirectPlanning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-direct-planning';

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

        try {
            //Ouverture d'un fichier Excel pour lecture de ses données
            $excelFilePath = '/mnt/interfas/DIRECT PLANNING/Import CRM/Classeur1.xlsx';
            $outputPath = '/mnt/interfas/DEV/YB_linux/Yellowbox/Exp_DP.txt';

            $spreadsheet = IOFactory::load($excelFilePath);
            $sheet = $spreadsheet->getActiveSheet();

            $outputFile = fopen($outputPath, 'w');
            if ($outputFile === false) {
                throw new \Exception("Le fichier n'a pas pu être ouvert.");
            }

            $formatTxt = new FormatTexte();

            $rowIndex = 4;
            while ($sheet->getCell('A' . $rowIndex)->getValue() != '') {
                $machine = $sheet->getCell('A' . $rowIndex)->getValue();
                $line = $machine . "\t" . $sheet->getCell('W' . $rowIndex)->getValue() . "\t";

                // Gestion PAO
                $paoValue = $sheet->getCell('B' . $rowIndex)->getValue();
                $line .= ($paoValue == 0) ? '' : 'PAO';
                $line .= "\t";

                // Conversion durée
                $duration = $sheet->getCell('C' . $rowIndex)->getValue();
                $hours = round($this->convertDurationToHours($duration), 2);
                $line .= $hours;

                // Ajout des autres colonnes
                $line .= "\t" . $sheet->getCell('D' . $rowIndex)->getValue();
                $line .= "\t" . number_format((float)str_replace('pouces', '', $sheet->getCell('G' . $rowIndex)->getValue()), 2);
                $line .= "\t" . $sheet->getCell('H' . $rowIndex)->getValue();
                $line .= "\t" . $sheet->getCell('I' . $rowIndex)->getValue();
                $line .= "\t" . $sheet->getCell('L' . $rowIndex)->getValue();
                $line .= "\t" . $sheet->getCell('M' . $rowIndex)->getValue();
                $line .= "\t" . $sheet->getCell('U' . $rowIndex)->getValue();
                $line .= "\t" . $formatTxt->getIdYB($sheet->getCell('Y' . $rowIndex)->getValue());

                //fwrite($outputFile, $line . PHP_EOL);

                $formatTxt->YBcreateFileTXT('Exp_DP.txt', '/mnt/interfas/DEV/YB_linux/Yellowbox/', $line);
                $rowIndex++;
            }

            fclose($outputFile);
            $this->info('Traitement Excel OK -> Direct Planning: ' . now()->format('d/m/Y H:i:s'));

            return Command::SUCCESS;

        } catch (\Throwable $e) {
            $this->error('Erreur: ' . $e->getMessage());
            Log::error('Update Direct Planning Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function convertDurationToHours($duration): float
    {
        // Vérifie si c'est bien une chaîne avec des ":" dedans
        if (!is_string($duration) || substr_count($duration, ':') < 2) {
            return 0.0;
        }

        $parts = explode(':', $duration);

        // Sécurité supplémentaire au cas où explode donne moins de 3 éléments
        $hours = isset($parts[0]) ? (float) $parts[0] : 0;
        $minutes = isset($parts[1]) ? (float) $parts[1] : 0;
        $seconds = isset($parts[2]) ? (float) $parts[2] : 0;

        return $hours + ($minutes / 60) + ($seconds / 3600);
    }
}
