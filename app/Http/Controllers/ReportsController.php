<?php

namespace App\Http\Controllers;

use App\Models\DataLink;
use App\Models\StatoDisp;
use App\Models\TipoDispositivo;
use App\Models\Struttura;
use App\Models\Dispositivo;
use App\Models\Report;
use App\Models\DownloadReport;
use App\Manager\LogicManager;
use App\Manager\LogManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use League\Csv\Writer;
use League\Csv\Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ModificaReportsRequest;
use App\Http\Requests\CreaReportsRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware(["utente"]);
    }
    
    public function view() 
    {
        return view("reports");
    }

    public function show() 
    {
        try {
            $dati = Struttura::with('dispositivi')->get();
            return response()->json($dati);
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazini", NULL, false, null, Auth::id(), "Dati strutture", null, $th);
            return response()->json(null);
        }
    }

    public function showDownloadReport()
    {
        try {
            $datiDownload = DownloadReport::get();
            return response()->json($datiDownload);
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazini", NULL, false, null, Auth::id(), "Dati strutture", null, $th);
            return response()->json(null);
        }
    }
    public function getReportsPeriodici() {
        try {
            $reports = Report::get();
            return response()->json($reports);
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazini", NULL, false, null, Auth::id(), "Dati reports periodici", null, $th);
            return response()->json(null);
        }

    }

    public function creaAutomazioneReports(CreaReportsRequest $request) {
        try {
            $strutture = Struttura::with('dispositivi')->get();
            $struttura = null;
            if (!empty($request->Nome)) {
                $nomeDati = $request->Nome;
            }
            $nome = null;
            $frequenzaRes = $request->Frequenza;
            $periodo = $request->Periodo;
            $generaDal = $request->inizioAutomazioneReports;
            $isJSONdati = $request->isJSON;
            $isCSVdati = $request->isCSV;
            $isXMLdati = $request->isXML;
            $codStruttura = (int)$request->Struttura;
            $isJSON = null;
            $isCSV = null;
            $isXML = null;
            $nomeStruttura = null;
            for ($s=0; $s < count($strutture) ; $s++) { 
                if($strutture[$s]->id == $codStruttura) {
                    $struttura = $strutture[$s];
                }
            }
            if (!is_null($request->Struttura)) {
                $nomeStruttura = $struttura->Nome;
            }
    
            if(!is_null($isJSONdati)) {
                $isJSON = 1;
            } else {
                $isJSON = 0;
            }
    
            if(!is_null($isCSVdati)) {
                $isCSV = 1;
            } else {
                $isCSV = 0;
            }
    
            if(!is_null($isXMLdati)) {
                $isXML = 1;
            } else {
                $isXML = 0;
            }
    
            if(empty($nomeDati)) {
                $nome = $nomeStruttura;
            } else {
                $nome = $nomeDati;
            }
    
            if (!is_null($generaDal)) {
                $dataInizio = $generaDal;
            } else {
                $dataInizio = now();
            }
    
            if (!is_null($frequenzaRes)) {
                $frequenza = $frequenzaRes;
            } else {
                $frequenza = now();
            }
    
            $data = [
                'Nome' => $nome,
                'Frequenza' => $frequenza,
                'Periodo' => $periodo,
                'isJSON' => $isJSON,
                'isCSV' => $isCSV,
                'isXML' => $isXML,
                'DataOra' => $dataInizio,
                'codStruttura' => $codStruttura,
            ];
    
            $query = LogicManager::generazioneQuery($data);
            $res = Report::insert($query);
            if ($res) {
                LogManager::scritturaLogs("operazioni", 0, true, $request->ip(), Auth::id(), "Automazione reports", $request, NULL);
                Session::flash("success", LogManager::messaggiOperazioni(0, true, "Automazione reports"));
            } else {
                LogManager::scritturaLogs("operazioni", 0, false, $request->ip(), Auth::id(), "Automazione reports", $request, NULL);
                Session::flash("error", LogManager::messaggiOperazioni(0, false, "Automazione reports"));
            }
    
            return back();
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazioni", NULL, false, $request->ip(), Auth::id(), "Automazione reports", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(NULL, false, "Automazione reports"));
            return back();
        }
    }

    public function showDatiJSON(Request $request) {
        try {
            $deveui = $request->Dispositivo;
            $dataDA = ($request->dataDA == "Invalid date" || empty($request->dataDA)) ? '' : Carbon::parse($request->dataDA);
            $dataA = ($request->dataA == "Invalid date" || empty($request->dataA)) ? '' : Carbon::parse($request->dataA);        
    
            $messaggio = '';
    
            if (empty($dataDA) && empty($dataA)) {
                $messaggio = 'Date non inserite';
            } else if (!empty($dataDA) && !empty($dataA)) {
                if ($dataDA > $dataA) {
                    $messaggio = 'La data finale deve essere minore o uguale alla data iniziale';
                }
            } else {
                if (empty($dataDA)) {
                    $messaggio = 'Data iniziale non valorizzata';
                }
                if (empty($dataA)) {
                    $messaggio = 'Data finale non valorizzata';
                }
            }
    
            if (!empty($messaggio)) {
                Session::flash("error", $messaggio);
                return back();
            }
    
            $statoDispList = StatoDisp::where("DevEui", $deveui)->where("DataOra", ">=", $request->dataDA)->where("DataOra", "<=", $request->dataA)->orderBy("DataOra", "desc")->get();
            
            $isJSON = $request->isJSON;
            $isCSV = $request->isCSV;
            $isXML = $request->isXML;
            
            $dispositivo = Dispositivo::where('DevEui', $deveui)->first();
            $struttura = Struttura::where('id', $dispositivo->codStruttura)->first();
            
            // Lista chiavi
            $chiaviList = ['Testo1', 'Testo2', 'Testo3', 'Testo4', 'Testo5', 'Testo6', 'Testo7', 'Testo8', 'Testo9', 'Testo10', 'Numero1', 'Numero2', 'Numero3', 'Numero4', 'Numero5', 'Numero6', 'Numero7', 'Numero8', 'Numero9', 'Numero10', 'FloatP1', 'FloatP2', 'FloatP3', 'FloatP4', 'FloatP5', 'FloatP6', 'FloatP7', 'FloatP8', 'FloatP9', 'FloatP10', 'DataOra'];
            
            // Tipo dispositivo
            $tipoDispRow = TipoDispositivo::where("IdTipo", $dispositivo->codTipoDisp)->first();
                    
            // Chiavi filtrate
            $filtroChiavi = [];
            foreach ($chiaviList as $key => $chiave) {
                if ($tipoDispRow->$chiave) {
                    array_push($filtroChiavi, $chiave);
                }
            }
            array_push($filtroChiavi, 'DataOra');
            
            // Chiavi con nomi
            $chiavi = [];
            foreach ($chiaviList as $key => $chiave) {
                if ($tipoDispRow->$chiave) {
                    array_push($chiavi, $tipoDispRow->$chiave);
                }
            }
            array_push($chiavi, 'DataOra');
            // return $chiavi;        
            
            // Crea e pusha oggetti nell'array del risultato finale 'datiList'
            $datiList = [];
            // Per ogni risultato di 'statoDispRow' creo un oggetto con le chiavi e lo popolo con i dati di 'statoDispRow'
            for ($s=0; $s < count($statoDispList); $s++) { 
                $valoriDisp = new \stdClass();
                foreach ($filtroChiavi as $key => $chiave) {
                    $valoriDisp->{$chiavi[$key]} = $statoDispList[$s]->$chiave;
                }
                array_push($datiList, $valoriDisp);
            }
            
            if (!is_null($isJSON)) {
                if (!empty($datiList)) {
                    $data = json_encode($datiList);
                    $jsonFile = 'Report ' . $struttura->Nome . ' ' . $dispositivo->Nome . ' dal ' . $request->dataDA . ' al ' . $request->dataA . '.json';    
                    $filePath = public_path('upload/json/' . $jsonFile);
                    
                    // Salva il file JSON
                    File::put($filePath, $data);
                    
                    // Restituisci il percorso del file per il download
                    return response()->download($filePath);
                } else {
                    Session::flash("error", "Non ci sono dati da scaricare");
                    // return response()->json(["avviso" => "Non ci sono dati da scaricare"]);
                }
                return back();
            }
    
            if (!is_null($isCSV)) {
                if (!empty($datiList)) {
                    try {
                        $csvFile = 'Report ' . $struttura->Nome . ' ' . $dispositivo->Nome . ' dal ' . $request->dataDA . ' al ' . $request->dataA . '.csv';    
                        $csv = Writer::createFromPath(public_path('upload/csv/' . $csvFile), 'w+');
                        $csv->setDelimiter(';');
                        $csv->setEnclosure('"');
                        $csv->insertOne($chiavi);
                        for ($f=0; $f < count($datiList) ; $f++) { 
                            $csv->insertOne((array)$datiList[$f]);
                        }
                        unlink('upload/csv/' . $csvFile);
                        return $csv->output($csvFile);
                    } catch (\Throwable $th) {
                        Log::channel("operazioni")->error($request->ip()." -> ".Auth::id().": Errore durante il download dei dati -> $th");
                        Session::flash('error', "Errore durante il download dei dati");
                        return redirect()->route("reports");
                    }
                } else {
                    Session::flash("error", "Non ci sono dati da scaricare");
                    // return response()->json(["avviso" => "Non ci sono dati da scaricare"]);
                }
                return back();
            }
    
            if (!is_null($isXML)) {
                if (!empty($datiList)) {
                    $dati = $datiList;
                    $disp = $dispositivo;
                    $xml = new \SimpleXMLElement('<dati/>');
                    $dispositivo = $xml->addChild('dispositivo');
                    $dispositivo->addAttribute('id', $disp->id);
                    $dispositivo->addAttribute('nome', $disp->Nome);
                    $dispositivo->addAttribute('deveui', $disp->Deveui);
                    foreach ($dati as $key => $dato) {
                        $pacchetto = $dispositivo->addChild('pacchetto');
                        $pacchetto->addAttribute('id', $key);
                        foreach ($chiavi as $key => $chiave) {
                            $chiave = $pacchetto->addChild($chiave, $dato->$chiave);
                        }
                    }
        
                    $content = $xml->asXML();
                    $headers = [
                        'Content-type' => 'text/xml',
                        'Content-Disposition' => 'attachment; filename=' . 'Report ' . $struttura->Nome . ' ' . $dispositivo->Nome . ' dal ' . $request->dataDA . ' al ' . $request->dataA . '.xml'
                    ];
                    return Response::make($content, 200, $headers);
                } else {
                    Session::flash("error", "Non ci sono dati da scaricare");
                    // return response()->json(["avviso" => "Non ci sono dati da scaricare"]);
                }
    
                return back();
            }
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazini", NULL, false, null, Auth::id(), "Dati", null, $th);
            return back();
        }
        
    }

    // Download dei file delle automazioni
    public function scaricaReportsFormato(Request $request) {    
        try {
            $idReport = $request->idModifica;
            if (isset($request->json)) {
                $formato = 'json';
            } elseif ($request->csv) {
                $formato = 'csv';
            } elseif ($request->xml) {
                $formato = 'xml';
            }

            if (isset($formato)) {
                if ($formato == 'json') {
                    $downloadFile = DownloadReport::where('codReport', $idReport)->first();
                    $nomeFile = $downloadFile->Nome . '.json';
                    $filePath = (public_path('download/reports/json/' . $nomeFile));
                    $headers = ['Content-Type: application/json'];
                    $changeIsDownload = DownloadReport::where('Nome', $downloadFile->Nome)->get();
                    // Scarico il report
                    foreach ($changeIsDownload as $key => $file) {
                        $file->isDownload = 1;
                        $file->save();
                    }
                    // dd($dataReport);
                    return response()->download($filePath, $nomeFile, $headers);
                } elseif ($formato == 'csv') {
                    $downloadFile = DownloadReport::where('codReport', $idReport)->first();
                    $nomeFile = $downloadFile->Nome . '.csv';
                    $filePath = (public_path('download/reports/csv/' . $nomeFile));
                    $headers = ['Content-Type: application/csv'];
                    // Cambio nel DB isDownload dei file scaricati con almeno un formato
                    $nomeIsDownload = $downloadFile->Nome;
                    $changeIsDownload = DownloadReport::where('Nome', $nomeIsDownload)->get();
                    foreach ($changeIsDownload as $key => $file) {
                        $file->isDownload = 1;
                        $file->save();
                    }
                    // Scarico il report
                    return response()->download($filePath, $nomeFile, $headers);
                } elseif ($formato == 'xml') {
                    $downloadFile = DownloadReport::where('codReport', $idReport)->first();
                    $nomeData = str_replace('-', '', $downloadFile);
                    $nomeFile = $downloadFile->Nome . '.xml';
                    $filePath = (public_path('download/reports/xml/' . $nomeFile));
                    $headers = ['Content-Type: application/xml'];
                    // Cambio nel DB isDownload dei file scaricati con almeno un formato
                    $nomeIsDownload = $downloadFile->Nome . '_' . $nomeData;
                    $changeIsDownload = DownloadReport::where('Nome', $nomeIsDownload)->get();
                    foreach ($changeIsDownload as $key => $file) {
                        $file->isDownload = 1;
                        $file->save();
                    }
                    // Scarico il report
                    return response()->download($filePath, $nomeFile, $headers);
                }
            }

        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function update(ModificaReportsRequest $request) {
        try {
            // $res = $request;
            $query = LogicManager::generazioneQuery($request->all());
            $res = (object)$query;
            
            $reportsID = (int)$res->reportsID;
            $nome = $res->Nome;
            $frequenza = (int)$res->Frequenza;
            $periodo = (int)$res->Periodo;
            $reportDaModificare = Report::find($reportsID);
            
            $codiceVecchiaStruttura = $reportDaModificare->codStruttura;
            $vecchiaStruttura = Struttura::find($codiceVecchiaStruttura);
            $nomeVecchiaStruttura = $vecchiaStruttura->Nome;
            if (isset($res->Struttura)) {
                $strutturaID = $res->Struttura;
                $struttura = Struttura::find($strutturaID);
                $nomeNuovaStruttura = $struttura->Nome;
            }
            
            
            // Definisco il nome della struttura
            if(empty($nome)) {
                if (isset($res->Struttura)) {
                    $strutturaID = $res->Struttura;
                    $struttura = Struttura::find($strutturaID);
                    $nomeNuovaStruttura = $struttura->Nome;    
                    $reportDaModificare->Nome = $nomeNuovaStruttura;
                } else {
                    $reportDaModificare->Nome = $nomeVecchiaStruttura;
                }
            } else {
                if (isset($res->Struttura)) {
                    if ($nome == $nomeVecchiaStruttura) {
                        $reportDaModificare->Nome = $nomeNuovaStruttura;
                    }
                } else {
                    $reportDaModificare->Nome = $nome;
                }
            }
            
            // Inserisco tutte le variabili da cambiare
            if(isset($res->Frequenza)) {
                $reportDaModificare->Frequenza = $frequenza;
            }
            if(isset($res->Periodo)) {
                $reportDaModificare->Periodo = $periodo;
            }
            if(isset($res->Struttura)) {
                $reportDaModificare->codStruttura = $strutturaID;
            }
            if (isset($res->isJSON)) {
                $reportDaModificare->isJSON = 1;
            } else {
                $reportDaModificare->isJSON = 0;
            }
            if (isset($res->isCSV)) {
                $reportDaModificare->isCSV = 1;
            } else {
                $reportDaModificare->isCSV = 0;
            }
            if (isset($res->isXML)) {
                $reportDaModificare->isXML = 1;
            } else {
                $reportDaModificare->isXML = 0;
            }
            
            $res = $reportDaModificare->save();
            if ($res) {
                LogManager::scritturaLogs("operazioni", 1, true, $request->ip(), Auth::id(), "Automazione reports", $request, NULL);
                Session::flash("success", LogManager::messaggiOperazioni(1, true, "Automazione reports"));
            } else {
                LogManager::scritturaLogs("operazioni", 1, false, $request->ip(), Auth::id(), "Automazione reports", $request, NULL);
                Session::flash("error", LogManager::messaggiOperazioni(1, false, "Automazione reports"));
            }
            
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazioni", NULL, false, $request->ip(), Auth::id(), "Automazione reports", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(NULL, false, "Automazione reports"));
        }
        return back();
    }

    // DELETE
    public function delete(Request $request)
    {
        try {
            $daEliminare = $request->id;
    
            $reportDaEliminare = Report::find($daEliminare);
    
            $nomeReport = $reportDaEliminare->Nome;
    
            $res = Report::where("id", $daEliminare)->delete();
            if ($res) {
                LogManager::scritturaLogs("operazioni", 2, true, $request->ip(), Auth::id(), "Automazione reports", $request, NULL);
                Session::flash("success", LogManager::messaggiOperazioni(2, true, "Automazione reports"));
            } else {
                LogManager::scritturaLogs("operazioni", 2, false, $request->ip(), Auth::id(), "Automazione reports", $request, NULL);
                Session::flash("error", LogManager::messaggiOperazioni(2, false, "Automazione reports"));
            }
    
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazioni", NULL, false, $request->ip(), Auth::id(), "Automazione reports", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(2, false, "Automazione reports"));
        }

        return back();
    }

//__________________________________________________________________________________________________________________

    // public function scaricaReports(Request $request) {
    //     if (!is_null($isJSON)) {
    //         if (!empty($datiList)) {
    //             $data = json_encode($datiList);
    //             $jsonFile = 'Nome report';    
    //             File::put(public_path('upload/json/' . $jsonFile), $data);
    //             return response()->download(public_path('upload/json/' . $jsonFile));
    //         } else {
    //             return response()->json(["avviso" => "Non ci sono dati da scaricare"]);
    //         }
    //     }

    //     if (!is_null($isCSV)) {
    //         try {
    //             $csvFile = 'Report ' . $nomeStruttura . ' ' . $nomeDispositivo . ' dal ' . $dataDA . ' al ' . $dataA . '.csv';    
    //             $csv = Writer::createFromPath(public_path('upload/json/' . $csvFile), 'w+');
    //             $csv->setDelimiter(';');
    //             $csv->setEnclosure('"');
    //             $csv->insertOne($chiavi);
    //             for ($f=0; $f < count($datiList) ; $f++) { 
    //                 $csv->insertOne((array)$datiList[$f]);
    //             }

    //             return $csv->output($csvFile);
    //         } catch (Exception | RuntimeException $e) {
    //             echo $e->getMessage(), PHP_EOL;
    //         }
    //         return back();
    //     }

    //     if (!is_null($isXML)) {
    //         $dati = $datiList;
    //         $disp = $dispositivo;
    //         $xml = new \SimpleXMLElement('<dati/>');
    //         $dispositivo = $xml->addChild('dispositivo');
    //         $dispositivo->addAttribute('id', $disp->id);
    //         $dispositivo->addAttribute('nome', $disp->Nome);
    //         $dispositivo->addAttribute('deveui', $disp->Deveui);
    //         foreach ($dati as $key => $dato) {
    //             $pacchetto = $dispositivo->addChild('pacchetto');
    //             $pacchetto->addAttribute('id', $key);
    //             foreach ($chiavi as $key => $chiave) {
    //                 $chiave = $pacchetto->addChild($chiave, $dato->$chiave);
    //             }
    //         }

    //         $content = $xml->asXML();
    //         $headers = [
    //             'Content-type' => 'text/xml',
    //             'Content-Disposition' => 'attachment; filename=' . 'Report ' . $nomeStruttura . ' ' . $nomeDispositivo . ' dal ' . $dataDA . ' al ' . $dataA . '.xml'
    //         ];
    //         return Response::make($content, 200, $headers);
    //     }
    //     return back();
    // }
}
