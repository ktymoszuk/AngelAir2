<?php 
namespace App\Manager;

use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Log;

class LogManager {
    // canale = utenze o operazioni, operazione = 0, 1, 2 ecc..., stato = true o  false, ip = indirizzo ip utente
    // id = id utente, obj = Struttura, Utente, Automazione, Dipositivo ecc..., objID = id Struttura, Utente, Dispositivo ecc..
    // th = errore processo
    public static function scritturaLogs($canale, $operazione, $stato, $ip, $id, $obj, $request, $th) {
        $message = "";
        switch ($operazione) {
            // insert
            case 0:
                $message = ($stato) ? $ip . " -> " . $id . ": $obj inserito con successo" : $ip . " -> " . ": Errore inserimento $obj";
                ($stato) ? Log::channel($canale)->info($message) : Log::channel($canale)->error($message);
                LogController::scritturaLog(($stato) ? "SUCCESS" : "ERROR", $message . "(insert)", $id);
                break;

            // update
            case 1:
                $message = ($stato) ? $ip . " -> " . $id . ": $obj $request->id : aggiornato con successo" : $ip . " -> " . $id . ": Errore aggiornamento $obj $request->id";
                ($stato) ? Log::channel($canale)->info($message) : Log::channel($canale)->error($message);
                LogController::scritturaLog(($stato) ? "SUCCESS" : "ERROR", $message . "(update)", $id);
                break;

            // delete
            case 2:
                $message = ($stato) ? $ip . " -> " . $id . ": $obj $request->id : cancellato con successo" : $ip . " -> " . $id . ": Errore cancellazione $obj $request->id";
                ($stato) ? Log::channel($canale)->info($message) : Log::channel($canale)->error($message);
                LogController::scritturaLog(($stato) ? "SUCCESS" : "ERROR", $message . "(delete)", $id);
                break;

            // upload
            case 3:
                $message = ($stato) ? $ip . " -> " . $id . ": $obj : caricato con successo" : $ip . " -> " . ": Errore caricamento $obj";
                ($stato) ? Log::channel($canale)->info($message) : Log::channel($canale)->error($message);
                LogController::scritturaLog(($stato) ? "SUCCESS" : "ERROR", $message . "(upload)", $id);
                break;

            // associazione
            case 4:
                $message = ($stato) ? $ip . " -> " . $id . ": Associazione $obj eseguita con dispositivo $request->codDispositivo con successo" : $ip . " -> " . $id . ": Errore nell'associazione $obj con dispositivo $request->codDispositivo";
                ($stato) ? Log::channel($canale)->info($message) : Log::channel($canale)->error($message);
                LogController::scritturaLog(($stato) ? "SUCCESS" : "ERROR", $message . "(associazione)", $id);
                break;

            // associazione
            case 5:
                $message = ($stato) ? $ip . " -> " . $id . ": Associazione $obj con dispositivo $request->codDispositivo eliminata con successo" : $ip . " -> " . $id . ": Errore nell'eliminazione associazione di $obj con dispositivo $request->codDispositivo";
                ($stato) ? Log::channel($canale)->info($message) : Log::channel($canale)->error($message);
                LogController::scritturaLog(($stato) ? "SUCCESS" : "ERROR", $message . "(associazione)", $id);
                break;

            // associazione
            case 6:
                $message = ($stato) ? $ip . " -> " . $id . ": Processo di $obj eseguito con successo" : $ip . " -> " . $id . ": Errore nel processo di $obj";
                ($stato) ? Log::channel($canale)->info($message) : Log::channel($canale)->error($message);
                LogController::scritturaLog(($stato) ? "SUCCESS" : "ERROR", $message . "(associazione)", $id);
                break;
            
            // errore processo
            default:
                $message = $ip . " -> " . $id . ": Errore processo $obj $request->id: $th";
                Log::channel($canale)->info($message);
                LogController::scritturaLog(($stato) ? "SUCCESS" : "ERROR", $message . "(ERRORE PROCESSO)", $id);
                break;
        }         
    }

    // operazione = 0, 1, 2 ecc..., stato = true o  false, obj = Struttura, Utente, Automazione, Dipositivo ecc...
    public static function messaggiOperazioni($operazione, $stato, $obj) {
        switch ($operazione) {
            case 0:
                return ($stato) ? "Inserimento di $obj completato" : "Errore inserimento di $obj";
                break;

            case 1:
                return ($stato) ? "Aggiornamento di $obj completato" : "Errore aggiornamento di $obj";
                break;

            case 2:
                return ($stato) ? "Cancellazione di $obj completata" : "Errore cancellazione di $obj";
                break;

            case 3:
                return ($stato) ? "$obj caricato" : "Errore caricamento di $obj";
                break;

            case 4:
                return ($stato) ? "L'associazione di $obj è andata a buon fine" : "Errore associazione di $obj";
                break;

            case 5:
                return ($stato) ? "L'eliminazione dell'associazione di $obj è andata a buon fine" : "Errore di eliminazione associazione di $obj";
                break;

            case 6:
                return ($stato) ? "Il processo di $obj è stato completato" : "Errore nel processo di $obj";
                break;

            default:
                return "Errore processo";
                break;
        }         
    }
}
