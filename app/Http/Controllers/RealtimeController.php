<?php

namespace App\Http\Controllers;

use App\Models\StatoDisp;
use App\Models\Dispositivo;
use Illuminate\Http\Request;
use App\Manager\LogicManager;
use App\Models\TipoDispositivo;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Bus\Dispatcher;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Response;

class RealtimeController extends Controller
{
    //Vista pagina
    public function view()
    {
        return view('real-time');
    }

    public function allarmi()
    {
        try {
            // 0059AC0000154E8E
            
            $result = null;
            $query = Dispositivo::orderBy('Nome');
            $auth1 = (int)Auth::user()->id_1liv;
            $auth2 = (int)Auth::user()->id_2liv;
            $auth3 = (int)Auth::user()->id_3liv;
            $auth4 = (int)Auth::user()->id_4liv;
            
            if (Auth::user()->isAssistenza != 1) {
                
                if ($auth4 != null) {
                    $query->where("id_1liv", $auth1)
                    ->where("id_2liv", $auth2)
                    ->where("id_3liv", $auth3)
                    ->where("id_4liv", $auth4);
                } else if ($auth3 != null) {
                    $query->where("id_1liv", $auth1)
                    ->where("id_2liv", $auth2)
                    ->where("id_3liv", $auth3);
                } else if ($auth2 != null) {
                    $query->where("id_2liv", $auth2)
                    ->where("id_1liv", $auth1);
                } else if ($auth1 != null) {
                    $query->where("id_1liv", $auth1);
                }
            }
            $quantitaDispositiviTotali = count($query->pluck('DevEui')->toArray());
    

            $query2 = Dispositivo::where('isAllarme', '>', 0);

            if (Auth::user()->isAssistenza != 1) {
                
                if ($auth4 != null) {
                    $query2->where("id_1liv", $auth1)
                    ->where("id_2liv", $auth2)
                    ->where("id_3liv", $auth3)
                    ->where("id_4liv", $auth4);
                } else if ($auth3 != null) {
                    $query2->where("id_1liv", $auth1)
                    ->where("id_2liv", $auth2)
                    ->where("id_3liv", $auth3);
                } else if ($auth2 != null) {
                    $query2->where("id_2liv", $auth2)
                    ->where("id_1liv", $auth1);
                } else if ($auth1 != null) {
                    $query2->where("id_1liv", $auth1);
                }
            }
            $dispositivi = $query2->with('tipodispositivo')->get(['Nome', 'DevEui', 'isAllarme', 'codTipoDisp']);

            $result['quantitaDispositiviNonFunzionanti'] = count($dispositivi);

            $query3 = Dispositivo::where('isAllarme', '>', 0);

            if (Auth::user()->isAssistenza != 1) {
                
                if ($auth4 != null) {
                    $query3->where("id_1liv", $auth1)
                    ->where("id_2liv", $auth2)
                    ->where("id_3liv", $auth3)
                    ->where("id_4liv", $auth4);
                } else if ($auth3 != null) {
                    $query3->where("id_1liv", $auth1)
                    ->where("id_2liv", $auth2)
                    ->where("id_3liv", $auth3);
                } else if ($auth2 != null) {
                    $query3->where("id_2liv", $auth2)
                    ->where("id_1liv", $auth1);
                } else if ($auth1 != null) {
                    $query3->where("id_1liv", $auth1);
                }
            }
            $listaDeveui = $query3->pluck('DevEui')->toArray();


            
            $statoDisp = StatoDisp::whereIn('DevEui', $listaDeveui)->orderBy('DataOra');

            $result['quantitaTuttiDispositivi'] = $quantitaDispositiviTotali;
            $result['allerta'] = [];
            $result['allarme'] = [];

            foreach ($dispositivi as $d => $dispositivo) {
                $dispositivo['ultimoDato'] = $statoDisp->where('DevEui', $dispositivo->DevEui)->first();
                $dispositivo['differenzaInOre'] = null;
                if (!empty($dispositivo['ultimoDato'])) {
                    # code...
                    $dataOra = $dispositivo['ultimoDato']['DataOra'];
                    $carbon = Carbon::parse($dataOra);
                    $now = Carbon::now();
                    $differenza = $now->diffInHours($carbon);
                    $dispositivo['differenzaInOre'] = $differenza;
                }
                
                if ($dispositivo['isAllarme'] == 1) {
                    array_push($result['allerta'], $dispositivo);
                } else if ($dispositivo['isAllarme'] == 2) {
                    array_push($result['allarme'], $dispositivo);
                }
            }
            
            return response()->json($result);

        } catch (\Throwable $th) {
            Log::channel('operazioni')->info("Errore nell'ottenere i dati in realtime: $th");
            return response()->json(null);
        }
    }
}
