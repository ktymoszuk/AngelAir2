<?php

namespace App\Http\Controllers;
use App\Manager\LogManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use App\Manager\LogicManager;

use App\Models\User;

use Illuminate\Http\Request;

class UtentiController extends Controller
{
    //Vista pagina
    public function view()
    {
        return view('utenti');
    }

    public function update(Request $request)
    {
        try {
            $query = LogicManager::generazioneQuery($request->all());

            // se password nuova non inserita, tolgo campo password
            if ($query["password"] == null) {
                unset($query["password"]);
            } else {
                $query["password"] = Hash::make($query["password"]);
            }

            // imposto stato valore checkbox
            $query["isAbilitato"] = LogicManager::getCheckboxState($request, "isAbilitato");

            $query['email'] = Crypt::encryptString($query['email']);

            $res = User::where("id", $request->id)->update($query);
            if ($res) {
                LogManager::scritturaLogs("utenze", 1, true, $request->ip(), Auth::id(), "Utente", $request, NULL);
                Session::flash("success", LogManager::messaggiOperazioni(1, true, "Utente"));
                // LogManager::scritturaLogs("utenze", 1, true, $request->ip(), Auth::id(), "Utente", $request->id, NULL);
                // Session::flash("success", "Utente aggiornato correttamente");
            } else {
                LogManager::scritturaLogs("utenze", 1, false, $request->ip(), Auth::id(), "Utente", $request, NULL);
                Session::flash("error", LogManager::messaggiOperazioni(1, false, "Utente"));
                // Log::channel("utenze")->error($request->ip() . " -> " . Auth::id() . ": Errore nell'aggiornamento dell'utente $request->id");
                // Session::flash("error", "Errore nell'aggiornamento dell'utente");
            }

            return back();
        } catch (\Throwable $th) {
            return $th;
            LogManager::scritturaLogs("utenze", NULL, false, $request->ip(), Auth::id(), "Utente", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(NULL, false, "Utente"));
            // Log::channel("utenze")->error($request->ip() . " -> " . Auth::id() . ": Errore processo aggiornamento utente $request->id -> $th");
            // Session::flash("error", "Errore processo aggiornamento utente");
            return back();
        }
    }

    public function delete(Request $request)
    {
        try {
            // $notificaRow = Notifica::where("codUtente", $request->id)->first();
            // if ($notificaRow != null) {
            //     $res = Notifica::where("codUtente", $request->id)->delete();
            //     if ($res) {
            //         LogManager::scritturaLogs("utenze", 2, true, $request->ip(), Auth::id(), "Notifiche utente", $request, NULL);
            //         Session::flash("success", LogManager::messaggiOperazioni(2, true, "Notifiche utente"));
            //         // Log::channel("utenze")->info($request->ip() . " -> " . Auth::id() . ": Notifiche utente $request->id cancellate correttamente");
            //     } else {
            //         LogManager::scritturaLogs("utenze", 2, true, $request->ip(), Auth::id(), "Notifiche utente", $request, NULL);
            //         Session::flash("success", LogManager::messaggiOperazioni(2, true, "Notifiche utente"));
            //         // Log::channel("utenze")->error($request->ip() . " -> " . Auth::id() . ": Errore nella cancellazione delle notifiche dell'utente $request->id");
            //     }
            // }

            $res = User::where("id", $request->id)->delete();
            if ($res) {
                LogManager::scritturaLogs("utenze", 2, true, $request->ip(), Auth::id(), "Utente", $request, NULL);
                Session::flash("success", LogManager::messaggiOperazioni(2, true, "Utente"));
            } else {
                LogManager::scritturaLogs("utenze", 2, true, $request->ip(), Auth::id(), "Utente", $request, NULL);
                Session::flash("success", LogManager::messaggiOperazioni(2, true, "Utente"));
            }

            return back();
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("utenze", NULL, false, $request->ip(), Auth::id(), "Utente", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(NULL, false, "Utente"));
            return back();
        }
    }
}