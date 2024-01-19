<?php

namespace App\Manager;

class ScenarioManager {
    // configuro nuovo stato dello scenario
    public static function statoScenario($stato) {
        $result = 0;
        switch ($stato) {
            case 0:
                $result = 2;
                break;
            
            case 1:
            case 2:
                $result = 0;
                break;
        }

        return $result;
    }

    // configurazione messaggio di log
    public static function messaggioScenario($stato) {
        $result = "";
        switch ($stato) {
            case 0:
                $result = "Scenario impostato in stato Normale";
                break;

            case 1:
                $result = "Scenario impostato in stato di Allerta";
                break;

            case 2:
                $result = "Scenario impostato in stato di Allarme";
                break;
        }

        return $result;
    }
}
?>