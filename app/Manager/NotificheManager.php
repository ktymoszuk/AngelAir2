<?php 

namespace App\Manager;

use App\Models\Notifica;
use App\Models\Soglia;
use App\Models\User;

class NotificheManager {
    // inizializzazione tabella notifiche per utente nuovo registrato
    public static function initNotifiche($email) {
        $soglieList = Soglia::orderBy("id")->get(); // prendo tutte le soglie
        $utente = User::where("email", $email)->first(); // prendo utente

        // creo notifiche
        foreach ($soglieList as $row) {
            Notifica::insert(["codSoglia" => $row["id"], "codUtente" => $utente["id"]]);
        }
    }

    public static function insertNotifica() {
        $soglia = Soglia::orderBy("id", "desc")->first();
        $utentiList = User::orderBy("id")->get(); // prendo utenti
        foreach ($utentiList as $key => $row) {
            Notifica::insert(["codSoglia" => $soglia["id"], "codUtente" => $row["id"]]);
        }
    }
}

?>