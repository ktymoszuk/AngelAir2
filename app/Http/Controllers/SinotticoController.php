<?php

namespace App\Http\Controllers;

use App\Models\Automazione;
use App\Models\Dispositivo;
use App\Models\Struttura;
use Illuminate\Http\Request;

class SinotticoController extends Controller
{
    public function __construct()
    {
        $this->middleware("utente");
    }

    public function index()
    {
        return view("sinottico");
    }

    // SELECT
    // visualizzazione sinottico e dispositivi
    public function show(Request $request) {
        $result = array();

        $struttura = $request->struttura;
        $automazione = $request->automazione;

        if (empty($struttura)) {
            $automazioneRow = Automazione::with("strutture")->where("id", $automazione)->orderBy("id", "desc")->first();
            $obj["Sinottico"] = $automazioneRow->Cartografia;
            
            foreach ($automazioneRow->strutture as $key => $row) {
                $dispositiviList = Dispositivo::with("categoriadispositivo")->where("codStruttura", $row->id)->get();
                $obj["Dispositivi"] = $dispositiviList;
            }

            array_push($result, $obj);
        } else {
            $strutturaRow = Struttura::where("id", $struttura)->orderBy("id", "desc")->first();
            $obj["Sinottico"] = $strutturaRow->Cartografia;

            $dispositiviList = Dispositivo::with("categoriadispositivo")->where("codStruttura", $strutturaRow->id)->get();
            $obj["Dispositivi"] = $dispositiviList;

            array_push($result, $obj);
        }

        return response()->json($result);
    }
    
}
