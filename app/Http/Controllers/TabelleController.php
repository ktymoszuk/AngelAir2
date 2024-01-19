<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

// MODELS
use App\Models\Automazione;
use App\Models\Struttura;
use App\Models\Dispositivo;
use App\Models\Comando;
use App\Models\User;
use App\Models\Soglia;
use App\Models\TipoDispositivo;
use App\Models\CategoriaDispositivo;
use App\Models\CategoriaSoglia;
use App\Models\StatoDisp;
class TabelleController extends Controller
{

    public function profilo() {
        $risultato = Auth::user();
        return response()->json($risultato);
    }

    public function automazioni() {
        $prova = Automazione::get();
        $risultato = Automazione::orderBy('Nome', 'ASC')->get();
        return response()->json($risultato);
    }

    public function strutture() {
        $risultato = Struttura::orderBy('Nome', 'ASC')->with("automazione")->with("dispositivi")->get();
        return response()->json($risultato);
    }

    public function dispositivi(Request $request) {

        // return response()->json($request);
        $struttura = null;
        $categoriaDisp = null;
        $testo = null;
        if (isset($request->struttura)) {
            $struttura = $request->struttura;
        }
        if (isset($request->categoriadisp)) {
            $categoriaDisp = $request->categoriadisp;
        }
        if (isset($request->testo)) {
            $testo = $request->testo;
        }
        
        $query = Dispositivo::with("struttura", "tipodispositivo", "sogliadispositivo", "comandodispositivo")
        ->orderBy("isAllarme", "DESC");

        if (!empty($categoriaDisp)) {
            $query->where("codTipoDisp", $categoriaDisp);
        }

        if (!empty($struttura)) {
            $query->where("codStruttura", $struttura);
        }

        if (!empty($testo)) {
            $query->where("Nome", "LIKE", "%$testo%");
        }

        if (Auth::user()->isAssistenza != 1) {

            $auth1 = (int)Auth::user()->id_1liv;
            $auth2 = (int)Auth::user()->id_2liv;
            $auth3 = (int)Auth::user()->id_3liv;
            $auth4 = (int)Auth::user()->id_4liv;

            
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

        $res = $query->orderBy('Nome')->get();

        return response()->json($res);
    }
    
    public function dispositiviPaginati(Request $request) {
        $adesso = Carbon::now()->setTimezone('Europe/Rome');
        
        $categoriaDisp = null;
        $testo = $request->testo;
        if (isset($request->categoriadisp)) {
            $categoriaDisp = $request->categoriadisp;
        }
        if (isset($request->testo)) {
            $testo = $request->testo;
        }
        
        $query = Dispositivo::with("tipodispositivo");

        if (!empty($categoriaDisp)) {
            $query->where("codTipoDisp", $categoriaDisp);
        }

        if (!empty($testo)) {
            $query->where("Nome", 'LIKE', '%' . $testo . '%');
        }


        $dispositivi = $query->orderBy('Nome')->paginate(100);

        $statodisp = StatoDisp::orderBy('DataOra', 'DESC')->get();
        foreach ($dispositivi as $key => $dispositivo) {
            foreach ($statodisp as $d => $dato) {
                if ($dispositivo->DevEui == $dato->DevEui) {
                    $dispositivo->DataUltimoPacchetto = $dato->DataOra;
                    $data = Carbon::parse($dato->DataOra);
                    $diff = $data->diffInMinutes($adesso);
                    $dispositivo->Differenza = $diff;

                    // CASO GAS - 30 min
                    if ($dispositivo->codTipoDisp == 1) {
                        if ($diff <= 32) {
                            $dispositivo->StatoComunicazioni = 0;
                        } else if ($diff > 32 && $diff <= 65) {
                            $dispositivo->StatoComunicazioni = 1;
                        } else if ($diff > 65 && $diff) {
                            $dispositivo->StatoComunicazioni = 2;
                        }
                    }
                    // CASO METEO - 60 min
                    if ($dispositivo->codTipoDisp == 2) {
                        if ($diff <= 62) {
                            $dispositivo->StatoComunicazioni = 0;
                        } else if ($diff > 62 && $diff <= 125) {
                            $dispositivo->StatoComunicazioni = 1;
                        } else if ($diff > 125 && $diff) {
                            $dispositivo->StatoComunicazioni = 2;
                        }
                    }

                    break;
                }
            }
        }

        return response()->json($dispositivi);
    }

    public function utenti() {
        $query = User::orderBy('name', 'ASC');


        if (Auth::user()->isAssistenza != 1) {

            $auth1 = (int)Auth::user()->id_1liv;
            $auth2 = (int)Auth::user()->id_2liv;
            $auth3 = (int)Auth::user()->id_3liv;
            $auth4 = (int)Auth::user()->id_4liv;

            
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

        $res = $query->get();

        return response()->json($res);
    }

    public function tipodisp() {
        $risultato = TipoDispositivo::orderBy('Nome', 'ASC')->get();
        return response()->json($risultato);
    }

    public function comandi() {
        $risultato = Comando::orderBy('Nome', 'ASC')->get();
        return response()->json($risultato);
    }

    public function cartografie() {
        $colonne = Schema::getColumnListing('Strutture');
        $cartografie = [];

        foreach ($colonne as $colonna) {
            if (similar_text('Cartografia', $colonna) >= 11) {
                array_push($cartografie, $colonna);
            }
        }
        return response()->json($cartografie);
    }

    public function categorieDispositivi() {
        $risultato = CategoriaDispositivo::orderBy('Nome', 'ASC')->get();
        return response()->json($risultato);
    }

    public function categorieSoglie() {
        $risultato = CategoriaSoglia::orderBy('Nome', 'ASC')->get();
        return response()->json($risultato);
    }

    public function soglie(Request $request) {
        $automazione = $request->automazione;
        $categoriaSoglia = $request->categoriasoglia;
        $categoriaDisp = $request->categoriadisp;
        $testo = $request->testo;

        $query = Soglia::with("automazione", "categoriasoglia", "categoriadispositivo");
    
        if ($automazione = $request->automazione) {
            $query->where("codAutomazione", $automazione);
        }
    
        if ($categoriaSoglia = $request->categoriasoglia) {
            $query->where("codCategoriaSoglia", $categoriaSoglia);
        }
    
        if ($categoriaDisp = $request->categoriadisp) {
            $query->where("codTipoDisp", $categoriaDisp);
        }
    
        if ($testo = $request->testo) {
            $query->where("Nome", "like", "%" . $testo . "%");
        }
    
        $res = $query->paginate(100);
    
        return response()->json($res);

    }

    public function tratte() {

        $urlAngel = config('app.angel_url');
        
        return response()->json($urlAngel);
        
        
        // auth angel
        $response = Http::get('http://'.$urlAngel.'/api/utente/key');

        $angelToken = $response->getBody();

    }
}
