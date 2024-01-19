<?php 

namespace App\Manager;

use App\Models\CategorieDispositivi;
use App\Models\NomiFirebase;

class SoglieManager {
    // verifica la tipologia di dato dei valori della soglia inseriti
    public static function checkTipoDatoSoglia($valore, $tipo) {
        if ($tipo == "Testuale") {
            // se è stringa, invertire risultato booleano
            if (!is_numeric($valore)) {
                return true;
            } else {
                return false;
            }
        } elseif ($tipo == "Numerica") {
            if (!is_numeric($valore)) {
                return false;
            } else {
                return true;
            }
        }
    }

    // query per prendere il valore associato alla soglia
    public static function getValoreAssociato($array) {
        // verifico se categoria soglia è un automazione principale
        if ($array["codCategoriaSoglia"] == 4 || $array["codCategoriaSoglia"] == 1) {
            return null;
        } else if ($array["codCategoriaDisp"] != null) {
            $categoriaDisp = CategorieDispositivi::where("id", $array["codCategoriaDisp"])->first();
            $tipoDisp = NomiFirebase::with("relTipoDisp")->where("Nominativo", $categoriaDisp["Nome"])->first();
            return $tipoDisp["relTipoDisp"][$array["ColonnaAssociata"]];
        } else {
            return null;
        }
    }
}

?>