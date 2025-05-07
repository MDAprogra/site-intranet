<?php

namespace App\Tools;

use Illuminate\Support\Facades\DB;

class FormatTexte
{
    public function clean_txt(string $v): string
    {
        // Supprimer les retours chariot, sauts de ligne et tabulations
        $vv = str_replace(
            ["\r\n", "\r", "\n", "\t", chr(13), chr(10)],
            '',
            $v
        );

        // Conversion Unicode (UTF-8) vers ANSI (ISO-8859-1 ou Windows-1252 selon le besoin)
        $ss = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $vv);

        return $ss;
    }

    public function getIdYB(string $v): string
    {
        $result = DB::connection('mysql2')->selectOne(
            "SELECT IddYB FROM users WHERE REP_CODE = ? LIMIT 1", [$v]
        );

        return $result->IddYB ?? 'Administrateur';
    }

    public function YBcreateFileTXT($FileName, $FilePath, $FileData)
    {
        // ouverture du fichier par le disque YellowBox
        $fullPath = $FilePath . $FileName;


        $file = fopen($fullPath, 'w+');

        if ($file === false) {
            throw new \Exception("Le fichier n'a pas pu être ouvert.");
        }

        fwrite($file, "\xEF\xBB\xBF"); // Ajouter le BOM UTF-8 (Byte Order Mark)
        $enTete = (array)$FileData[0];
        $header = implode(';', array_keys($enTete));

        foreach ($FileData as $row) {
            $row = (array)$row;
            $line = implode(';', array_map(function ($value) {
                return '"' . str_replace('"', '""', $value) . '"';
            }, $row));
            fwrite($file, $line . PHP_EOL);
        }
        fclose($file);
    }
}
