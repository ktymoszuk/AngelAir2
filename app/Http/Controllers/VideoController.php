<?php

namespace App\Http\Controllers;

use App\Models\Telecamere;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware("utente");
    }

    public function index() {
        return view("video");
    }

    // SELECT
    public function show(Request $request) {
        $result = array(); 

        $id = $request->id;

        $tvccRow = Telecamere::where("id", $id)->first();
        
        $obj["telecamera"] = $tvccRow;
        $obj["streaming"] = env("TVCC_SERVER");
        
        array_push($result, $obj);

        return response()->json($result);
    }
}
