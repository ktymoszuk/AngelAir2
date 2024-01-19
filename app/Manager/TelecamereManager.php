<?php 

namespace App\Manager;

class TelecamereManager {
    // configurazione messaggio azionamento telecamera
    public static function messaggioTelecamera($stato) {
        $result = "";
        switch ($stato) {
            case 0:
                $result = "Inviato comando spegnimento telecamera";
                break;
            
            case 1:
                $result = "Inviato comando accensione telecamera";
                break;
        }

        return $result;
    }
}


?>