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

}