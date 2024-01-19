<?php

namespace App\Manager;

use Illuminate\Http\Request;
use League\Csv\EscapeFormula;

class LogicManager
{
    // gestione valori per query (generale)
    public static function generazioneQuery($array)
    {
        $result = array();
        foreach ($array as $key => $value) {
            if ($key != "id" && $key != "_token" && $key != null) {
                $result[$key] = str_replace(array('"', "'", "`", "\\", "/", "*"), "", strip_tags($value));
            }
        }
        return $result;
    }

    // gestione stati checkbox per query
    public static function getCheckboxState(Request $request, $valore)
    {
        // verifico se presente il valore
        if ($request->has($valore)) {
            $result = 1;
        } else {
            $result = 0;
        }

        return $result;
    }


    // gestione tabelle csv per ottenere in formato array
    public static function getCsvData($filePath)
    {
        $handler = fopen($filePath, 'r');  // Apriamo il file csv per analizzarlo

        $formatter = new EscapeFormula("`", ["*", "/", "="]);  // Caratteri da sottoporre a escape

        $riga = 0;

        $datiGlobali = [];

        //Apriamo il file utlizzando il separtore di colonne ; e andando a leggere ogni riga
        while ($data = fgetcsv($handler, null, ";")) {

            $array[] = implode(";", $data);

            if ($riga >= 1) {
                //dividiamo le colonne con i dati nel file csv 
                $arrayProvvisorio = explode(';', $array[$riga]);

                $arrayCorretto = [];

                //filtriamo le caselle della riga per sottoporre a escape i vari caratteri pericolosi
                foreach ($arrayProvvisorio as $index => $value) {
                    $key = strval($datiGlobali[0][$index]);

                    $spazi = substr_count($value, ' ');
                    $arraySpazi = [];

                    $start = 0;

                    while (($pos = strpos(($value), " ", $start)) !== false) {
                        $start = $pos + 1;
                        array_push($arraySpazi, $pos);
                    }

                    if ($spazi > 0) {
                        $stringa = filter_var(str_replace(",", ".", $value), FILTER_SANITIZE_EMAIL);

                        $stringaCorretta = $stringa;

                        for ($i = 0; $i < count($arraySpazi); $i++) {
                            if (count($arraySpazi) != $i) {
                                $stringaCorretta = substr_replace($stringaCorretta, " ", $arraySpazi[$i], 0);
                            }
                        }
                        $arrayCorretto[$key] = strip_tags(trim($stringaCorretta));
                    } else {
                        $arrayCorretto[$key] = strip_tags(filter_var(str_replace(",", ".", $value), FILTER_SANITIZE_EMAIL));
                    }
                }

                array_push($datiGlobali, $arrayCorretto); //aggiungiamo la riga dati all'array
            } else {
                array_push($datiGlobali, explode(';', $array[$riga]));  //questa riga rappresenta i nomi delle colonne
            }

            $riga++;
        }

        //eseguiamo l'ultimo filtraggio con '
        foreach ($datiGlobali as $index => $righe) {
            $valoriFiltrati =  $formatter->escapeRecord($righe);

            $datiGlobali[$index] = $valoriFiltrati;
        }

        fclose($handler);  // chiudiamo il file 

        return $datiGlobali;
    }

    //Lowercase e no spazi
    public static function lowerNoSpazi($value)
    {
        return str_replace(' ', '', strtolower($value));
    }


    public static function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}
