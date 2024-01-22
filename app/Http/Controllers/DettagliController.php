<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use App\Models\StatoDisp;
use App\Models\RawData;
use App\Models\TipoDispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DettagliController extends Controller
{
    public function dettagli(Request $request) {
        try {
            $url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

            $arrayUrl = explode('deveui=', $url);
            $deveui = $arrayUrl[count($arrayUrl) - 1];
            $dispositivo = Dispositivo::where('DevEui', $request->deveui)->first();
            $nome = $dispositivo->Nome;
            
            // Creo gli array dei nomi delle colonne
            $tipoDisp = TipoDispositivo::where('idTipo', $dispositivo->codTipoDisp)->first();
            if ($tipoDisp) {
                if ($tipoDisp->Nome == 'StazioneGas' || $tipoDisp->Nome == 'Stazione Gas') {
                    // Ottieni coppie di chiave-valore per le chiavi non nulle
                    $coppieChiaveValore = collect($tipoDisp->toArray())
                    ->filter(function ($valore) {
                        return $valore !== null;
                    })
                    ->mapWithKeys(function ($valore, $chiave) {
                        return [$chiave => $valore];
                    })
                    ->except(['IdTipo', 'ParserName', 'Nome', 'Numero11'])
                    ->prepend('Frame', 'Frame');
                } else {
                    // Ottieni coppie di chiave-valore per le chiavi non nulle
                    $coppieChiaveValore = collect($tipoDisp->toArray())
                    ->filter(function ($valore) {
                        return $valore !== null;
                    })
                    ->mapWithKeys(function ($valore, $chiave) {
                        return [$chiave => $valore];
                    })
                    ->except(['IdTipo', 'ParserName', 'Nome', 'Numero3', 'Numero4', 'Numero5', 'FloatP4', 'FloatP5'])
                    ->prepend('Frame', 'Frame');
                }
                
                if ($tipoDisp->Nome == 'StazioneGas' || $tipoDisp->Nome == 'Stazione Gas') {
                    $coppieChiaveValore['Vento'] = 'Vento';
                }
                $coppieChiaveValore['DataOra'] = 'DataOra';
                
                $array = $coppieChiaveValore->toArray();
                $chiavi = array_keys($array);
                $valori = array_values($array);
                $colonneGrezze = json_encode($chiavi);
                $colonne = json_encode($valori);
                // Se modifico queste soglie, le devo modificare anche nella funzione 'dati' nelle variabili: 
                    // $limiteMinimo = 0.25 -> $soglieVento[0]
                    // $limiteMedio = 0.75 $soglieVento[1]
                    // $limiteMassimo = 1 $soglieVento[2]
                    $soglieVento = json_encode([2, 5, 10]);
                }
                // return response()->json($soglieVento);
    
            return view('dettaglio', compact('dispositivo', 'colonneGrezze', 'colonne', 'soglieVento'));
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function rawData(Request $request) {
        $rawData = RawData::where('Deveui', $request->deveui)->orderBy('DataOra', 'DESC')->limit(250)->get();
        return response()->json($rawData);
    }
                
    public function statoDisp(Request $request) {
        $statoDisp = StatoDisp::where('DevEui', $request->deveui)->orderBy("DataOra", "DESC")->limit(1000)->get();
        return response()->json($statoDisp);

        foreach ($statoDisp as $key => $dato) {
            $dataOra = Carbon::parse($dato->DataOra);
            $formatDataOra = $dataOra->format('d/m/Y - H:m:i');
            $dato->DataOra = $formatDataOra;
        }
    }

    public function dati(Request $request) {
        try {
            $perPagina = 100;
            $offset = ($request->page * $perPagina) - $perPagina;
            
            $dispositivo = Dispositivo::where('DevEui', $request->deveui)->first();
            
            // Definisco il numero del set stazione meteo/gas
            $numeroArray = explode('-', $dispositivo->Nome);
            $tipo = $numeroArray[0];
            if ($tipo == "Gas" || $tipo == "Meteo") {
                $lunghezzaNumeroArray = count($numeroArray);
                $numero = $numeroArray[$lunghezzaNumeroArray - 1];
                $nomeStazioneMeteo = 'Meteo-' . $numero;
                
            } else {
                $nomeStazioneMeteo = $tipo . '-Meteo';
            }
    
            $stazioneMeteo = Dispositivo::where('Nome', 'LIKE', '%' . $nomeStazioneMeteo . '%')->first();
            $stazioneMeteoDeveui = $stazioneMeteo->DevEui;
    
            $stazioneMeteo = Dispositivo::where('Nome', 'LIKE', '%' . $nomeStazioneMeteo . '%')->first();
            $stazioneMeteoDeveui = $stazioneMeteo->DevEui;
    
            // Definisco il tpo dispositivo
            $nomeTipoDisp = TipoDispositivo::where('idTipo', $dispositivo->codTipoDisp)->first()->Nome;
    
            // Prendo tutti i dati di StatoDisp
            $statodisp = StatoDisp::orderBy('DataOra', 'DESC')->get();
            $evita = [];
            $datiSensore = [];
    
            // Prendo i dati della stazione meteo della stazione trovata prima
            $datiStazioneMeteo = [];
            foreach ($statodisp as $key => $dato) {            
                if ($dato->DevEui == $stazioneMeteoDeveui && !isset($dato->Testo2) && isset($dato->Testo3) && isset($dato->FloatP5)) {
                    array_push($datiStazioneMeteo, $dato);
                }
            }
            $datiMeteo = collect($datiStazioneMeteo);
    
            foreach ($statodisp as $key => $dato) {            
                if ($dato->DevEui == $dispositivo->DevEui && count($evita) < $offset) {
                    array_push($evita, $dato);
                }
    
                if ($dato->DevEui == $dispositivo->DevEui && count($datiSensore) < $perPagina && count($evita) == $offset) {
                    $statoVento = null;
                    if ($nomeTipoDisp == 'StazioneGas' || $nomeTipoDisp == 'Stazione Gas') {
                        if (count($datiStazioneMeteo) > 0) {
                            $vento = $datiMeteo->where('DataOra', '<=', $dato->DataOra)->first();
                            if (isset($vento->FloatP5)) {
                                $velocitaVento = $vento->FloatP5;
    
                                // Se modifico queste soglie, le devo modificare anche nella funzione 'index' nella variabile: 
                                // $soglieVento = json_encode([0.25, 0.75, 1]);
    
                                // $limiteMinimo = 0.25 -> $soglieVento[0]
                                // $limiteMedio = 0.75 $soglieVento[1]
                                // $limiteMassimo = 1 $soglieVento[2]
                                $limiteMinimo = 2;
                                $limiteMedio = 5;
                                $limiteMassimo = 10;
                                if ($velocitaVento < $limiteMinimo) {
                                    $statoVento = 0;
                                } else if($velocitaVento >= $limiteMinimo && $velocitaVento < $limiteMedio) {
                                    $statoVento = 1;
                                } else if($velocitaVento >= $limiteMedio && $velocitaVento < $limiteMassimo) {
                                    $statoVento = 2;
                                } else if($velocitaVento >= $limiteMassimo) {
                                    $statoVento = 3;
                                }
                            } else {
                                $velocitaVento = null;
                            }
                            if (isset($vento->Testo3)) {
                                $direzioneVento = $vento->Testo3;
                            } else {
                                $direzioneVento = null;
                            }
                            $dato->Vento = $velocitaVento . ' ' . $direzioneVento;
                            $dato->statoVento = $statoVento;
                        }
                    }
    
                    array_push($datiSensore, $dato);
                }
            }
            $dati = array();
    
            $dati['DatiSensore'] = $datiSensore;
    
            return response()->json($datiSensore);            
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}

