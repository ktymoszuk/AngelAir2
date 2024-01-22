<?php

namespace App\Http\Controllers;

use App\Models\Automazione;
use App\Models\Dispositivo;
use App\Models\StatoDisp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware("utente");
    }

    public function index()
    {
        return view("dashboard");
    }

    // SELECT
    // stati scenari
    public function showScenari()
    {
        $res = Automazione::with("scenari")->get();
        return response()->json($res);
    }

    // dati mappa dashboard
    public function showMappaStrutture()
    {
        $res = Automazione::with("strutture")->get();
        return response()->json($res);
    }

    public function datiMappa() {
        $adesso = Carbon::now()->setTimezone('Europe/Rome');
        
        $dispositivi = Dispositivo::with("tipodispositivo")->orderBy('Nome')->get()->toArray();

        $statodisp = StatoDisp::orderBy('DataOra', 'DESC')->get();
        
        foreach ($dispositivi as $key => $dispositivo) {
            foreach ($statodisp as $d => $dato) {
                if ($dispositivi[$key]['DevEui'] == $dato->DevEui) {
                    $dispositivi[$key]['DataUltimoPacchetto'] = $dato->DataOra;
                    $data = Carbon::parse($dato->DataOra);
                    $diff = $data->diffInMinutes($adesso);
                    $dispositivi[$key]['Differenza'] = $diff;

                    // CASO GAS - 30 min
                    if ($dispositivi[$key]['codTipoDisp'] == 1) {
                        if ($diff <= 32) {
                            $dispositivi[$key]['isAllarme'] = 0;
                        } else if ($diff > 32 && $diff <= 65) {
                            $dispositivi[$key]['isAllarme'] = 1;
                        } else if ($diff > 65 && $diff) {
                            $dispositivi[$key]['isAllarme'] = 2;
                        }
                    }
                    // CASO METEO - 60 min
                    if ($dispositivi[$key]['codTipoDisp'] == 2) {
                        if ($diff <= 62) {
                            $dispositivi[$key]['isAllarme'] = 0;
                        } else if ($diff > 62 && $diff <= 125) {
                            $dispositivi[$key]['isAllarme'] = 1;
                        } else if ($diff > 125 && $diff) {
                            $dispositivi[$key]['isAllarme'] = 2;
                        }
                    }

                    break;
                }
            }

            $dispositivi[$key]['Citta'] = explode('-', $dispositivi[$key]['Nome'])[0];
        }

        usort($dispositivi, function($a, $b) {
            return strcmp($b['isAllarme'], $a['isAllarme']);
        });

        return response()->json($dispositivi);
    }
}
