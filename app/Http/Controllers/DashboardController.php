<?php

namespace App\Http\Controllers;

use App\Models\Automazione;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware("utente");
    }

    public function index()
    {
        return view("dashboard");
    }

    // SELECT
    // stati scenari
    public function showScenari()
    {
        $res = Automazione::with("scenari")->get();
        return response()->json($res);
    }

    // dati mappa dashboard
    public function showMappaStrutture()
    {
        $res = Automazione::with("strutture")->get();
        return response()->json($res);
    }
}
