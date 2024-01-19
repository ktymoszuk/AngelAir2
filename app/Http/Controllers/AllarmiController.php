<?php

namespace App\Http\Controllers;

use App\Models\Automazione;
use App\Models\Dispositivo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Manager\LogManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AllarmiController extends Controller
{
    public function __construct()
    {
        $this->middleware("utente");
    }

    public function index() {

        $result = array();
        
        $dispositiviCount = Dispositivo::where("isAllarme", 1)->count();
        $dispositiviList = Dispositivo::with("struttura")->where("isAllarme", 1)->get();
        $automazioniList = Automazione::get();

        $automazioneCount = 0;
        foreach ($automazioniList as $row) {
            $res = Carbon::parse(Carbon::now()->setTimezone("Europe/Rome"))->diffInMinutes(Carbon::parse($row->DataUpdate));
            if ($res > 1) {
                $automazioneCount++;
            }
        }

        $result["DispositiviCount"] = $dispositiviCount;
        $result["AutomazioniCount"] = $automazioneCount;
        $result["dispositivi"] = $dispositiviList;

        return response()->json($result);

    }
}
