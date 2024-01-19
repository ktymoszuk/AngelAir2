<?php

namespace App\Manager;

use Illuminate\Support\Facades\DB;

class AxManager
{

    //Metodo post per la sincronizzazione singola del campo con curl
    public static function SincCampoSingolo($AxId, $AxAppTag, $Operazione, $tabella, $AxSystemTag = null)
    {
        DB::table('Sincronizzazioni')->where('AxId', $AxId)->update([
            'AxAppTag' => 'VS_GA',
            'AxSystemTag' => $AxSystemTag,
            'isSincCampo' => 0,
            'Operazione' => $Operazione,
        ]);

        //Prelevazione dati da inviare
        $datiRiga = DB::table($tabella)->where('AxId', $AxId)->get()->first();

        //Settaggio dati
        $datiDaInviare = [
            "AxAppTag" => $AxAppTag,
            "AxSystemTag" => $AxSystemTag,
            "Tabella" => $tabella,
            "Operazione" => $Operazione,
            "Parametri" => [],
        ];

        array_push($datiDaInviare["Parametri"], $datiRiga);

        //Url di ricezione
        $apiUrl = config('app.guzzle_test_url') . "/api/ricezione-dal-cdc";

        $client = new \GuzzleHttp\Client();

        //Invio della richiesta post per inserire i dati
        $response = $client->request('POST', $apiUrl,  ['form_params' => $datiDaInviare]);

        $statusCode = $response->getStatusCode();

        $responseBody = json_decode($response->getBody(), true);

        //Se la risposta è buona cambio i dati db
        if ($responseBody == 1 && $statusCode == 200) {
            if ($Operazione == 'delete')  //Se la sincronizzazione è andata a buon fine elimino definitivamente dal db 
            {
                $res1 = DB::table('Sincronizzazioni')->where('AxId', $AxId)->delete();
                $res2 = DB::table($tabella)->where('AxId', $AxId)->delete();
                if ($res1 && $res2) {
                    return true;
                }
            } else {
                $res1 = DB::table('Sincronizzazioni')->where('AxId', $AxId)->update([
                    'isSincCampo' => 1,
                    'Operazione' => '0',
                ]);
                if ($res1) {
                    return true;
                }
            }
        } else {

            return false;
        }
    }

    //Metodo post per la sincronizzazione ultipla del campo con curl
    public static function SincCampoMultiplo($dati, $Operazione, $tabella, $AxIds)
    {
        //Update delle operazioni da svolgere
        DB::table('Sincronizzazioni')->whereIn('AxId', $AxIds)->update(['Operazione' => $Operazione]);

        //Url di ricezione
        $apiUrl = config('app.guzzle_test_url') . "/api/ricezione-dal-cdc";

        $client = new \GuzzleHttp\Client();

        $datiDaInviare = [];

        foreach ($dati as $riga) {
            if ($riga->id == 1) {
                $datiDaInviare = [
                    "AxAppTag" => $riga->AxAppTag,
                    "AxSystemTag" => $riga->AxSystemTag,
                    "Tabella" => $tabella,
                    "Operazione" => $Operazione,
                    "Parametri" => [],
                ];
            }

            array_push($datiDaInviare["Parametri"], $riga);
        }

        //Invio della richiesta post per inserire i dati
        $response = $client->request('POST', $apiUrl,  ['form_params' => $datiDaInviare]);

        $statusCode = $response->getStatusCode();

        $responseBody = json_decode($response->getBody(), true);

        // Se la risposta è buona cambio i dati db
        if ($responseBody == 1 && $statusCode == 200) {
            if ($Operazione == 'delete')  //Se la sincronizzazione è andata a buon fine elimino definitivamente dal db 
            {
                $res1 = DB::table('Sincronizzazioni')->whereIn('AxId', $AxIds)->delete();
                $res2 = DB::table($tabella)->wherein('AxId', $AxIds)->delete();
                if ($res1 && $res2) {
                    return true;
                }
            } else {
                $res1 = DB::table('Sincronizzazioni')->whereIn('AxId', $AxIds)->update([
                    'isSincCampo' => 1,
                    'Operazione' => '0',
                ]);
                if ($res1) {
                    return true;
                }
            }
        } else {
            $AxIdDaAggiornare = array_diff($AxIds, $responseBody);

            if ($Operazione == 'delete')  //Se la sincronizzazione è andata a buon fine elimino definitivamente dal db 
            {
                $res1 = DB::table('Sincronizzazioni')->whereIn('AxId', $AxIdDaAggiornare)->delete();
                $res2 = DB::table($tabella)->wherein('AxId', $AxIdDaAggiornare)->delete();
            } else {
                $res1 = DB::table('Sincronizzazioni')->whereIn('AxId', $AxIdDaAggiornare)->update([
                    'isSincCampo' => 1,
                    'Operazione' => '0',
                ]);
            }
            return false;
        }
    }
}
