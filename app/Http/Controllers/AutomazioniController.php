<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutomazioneRequest;
use App\Manager\LogicManager;
use App\Models\Automazione;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Manager\LogManager;


class AutomazioniController extends Controller
{
    public function __construct()
    {
        $this->middleware("utente");
    }

    public function index()
    {
        return view("automazioni");
    }

    // SELECT
    // visualizzazione automazioni
    public function show(Request $request) {
        try {
            $testo = $request->testo;
            $paginazione = $request->paginazione;
    
            if (empty($testo)) {
                $res = Automazione::offset($paginazione)->limit(100)->get();
            } else {
                $res = Automazione::where("Nome", "like", "%$testo%")->offset($paginazione)->limit(100)->get();
            }
    
            return response()->json($res);
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazini", NULL, false, $request->ip(), Auth::id(), "Automazione", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(NULL, false, "Automazione"));
            return back();
        }
    }

    // UPDATE
    public function update(AutomazioneRequest $request) {
        try {
            $query = LogicManager::generazioneQuery($request->all());

            if ($request->Cartografia != null) {
                $cartografia = $request->Cartografia->getClientOriginalName();
                $request->Cartografia->move(public_path('immagini/mappa'), $cartografia);
                $query["Cartografia"] = $cartografia;
            } else {
                $query["Cartografia"] = $request->CartografiaAttuale;
            }
            unset($query["CartografiaAttuale"]);

            $res = Automazione::where("id", $request->id)->update($query);
            if ($res) {
                Log::channel("operazioni")->info($request->ip()." -> ".Auth::id().": Automazione $request->id aggiornata con successo");
                Session::flash("success", "Automazione aggiornata correttamente");
            } else {
                Log::channel("operazioni")->error($request->ip()." -> ".Auth::id().": Errore nell'aggiornamento dell'automazione $request->id");
                Session::flash("error", "Errore nell'aggiornamento dell'automazione");
            }

            return redirect()->route("automazioni");
        } catch (\Throwable $th) {
            Log::channel("operazioni")->error($request->ip()." -> ".Auth::id().": Errore processo aggiornamento dell'automazione $request->id -> $th");
            Session::flash("error", "Errore processo aggiornamento dell'automazione");
            return redirect()->route("automazioni");
        }
    }

    // OTHER
    // riavvio automazione
    public function riavvio(Request $request) {
        try {
            $id = $request->id;
            $nome = $request->Nome;

            $res = Automazione::where("id", $id)->update(["Stato" => 0]);
            if ($res) {
                Log::channel("operazioni")->info($request->ip()." -> ".Auth::id().": Automazione $request->id riavviata");
                Session::flash("success", "Automazione $nome riavviata correttamente");
            } else {
                Log::channel("operazioni")->error($request->ip()." -> ".Auth::id().": Errore nel riavvio dell'automazione $request->id");
                Session::flash("error", "Errore nel riavvio dell'automazione $nome");
            }

            return back();
        } catch (\Throwable $th) {
            Log::channel("operazioni")->error($request->ip()." -> ".Auth::id().": Errore processo riavvio automazione $id -> $th");
            Session::flash("error", "Errore processo riavvio automazione $nome");
            return back();
        }
    }
}
