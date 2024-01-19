<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrazioneRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

use App\Manager\LogicManager;
use App\Manager\NotificheManager;
use App\Models\User;

class AuthController extends Controller

{
    // login applicazione
    public function login(LoginRequest $request) {
        // credenziali auth laravel
        $credentials = [
            "email" => $request->Email,
            "password" => $request->Password
        ];

        // verifica credenziali
        if (Auth::attempt($credentials)) {
            // login
            $request->session()->regenerate();
            Log::channel("utenze")->info("Accesso effettuato da ".$request->ip()." con utente $request->Email");
            return redirect()->route("dashboard");
        } else {
            // errore
            Log::channel("utenze")->alert($request->ip().": Tentativo di accesso con credenziali errate su $request->Email");
            Session::flash("error", "Le credenziali di accesso inserite sono errate");
            return redirect()->route("login");
        }
    }

    // registrazione app
    public function registrazione(RegistrazioneRequest $request) {
        $request["Password"] = Hash::make($request->Password); // hash password
        $query = LogicManager::generazioneQuery($request->all()); // composizione query
        $res = User::where("email", $query["email"])->get(); // query di verifica se mail è già in uso

        // verifica email non sia in uso
        if (count($res) > 0) {
            Log::channel("utenze")->alert($request->ip().": Email già esistente");
            Session::flash("error", "L'Email inserita è già in uso");
            return redirect()->route("registrazione_utente");
        } else {
            // inserimento nuovo utente
            $res2 = User::insert($query);

            if ($res2) {
                NotificheManager::initNotifiche($query["email"]); // inizializzazione notifiche
                Log::channel("utenze")->warning("Registrazione effettuata da ".$request->ip());
                Session::flash("success", "Registrazione andata a buon fine. Il tuo account dovrà essere abilitato da un'Amministratore di sistema");
                return redirect()->route("login");
            } else {
                Log::channel("utenze")->error($request->ip().": Errore nella registrazione");
                Session::flash("error", "La registrazione non è andata a buon fine");
                return redirect()->route("registrazione_utente");
            }
        }
    }

    // logout app
    public function logout(Request $request) {
        Log::channel("utenze")->info("Logout utente ".Auth::id()." ".$request->ip());
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        return redirect()->route("index");
    }
}