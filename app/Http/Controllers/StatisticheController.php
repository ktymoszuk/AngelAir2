<?php

namespace App\Http\Controllers;

use App\Models\Missione;
use Illuminate\Http\Request;
use App\Manager\LogicManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class StatisticheController extends Controller
{
    //Vista pagina
    public function view()
    {
        return view('statistiche');
    }

}
