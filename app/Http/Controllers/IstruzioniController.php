<?php

namespace App\Http\Controllers;

use App\Models\TipoDispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Manager\LogManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class IstruzioniController extends Controller
{

    //Per caricare la vista delle istruzioni
    public function getIstruzioni(string $rotta)
    {
        try {
            $contenuto = File::get(public_path('/assets/istruzioni_excel.json'));
            
            $datiJson = json_decode($contenuto, true);
            
            $rottaEsiste = false;
            
            if(array_key_exists($rotta, $datiJson)){
                $rottaEsiste = true;
            }
            
            if(!is_null($rotta) && $rottaEsiste)
            {
                $istruzioniColonne = collect($datiJson[$rotta]);
                
                $categorieDisp = TipoDispositivo::orderBy('Nome', 'ASC')->get();
                
                return view("components.istruzioni", ['istruzioniColonne' => $istruzioniColonne, 'categorieDisp' => $categorieDisp]);
            }
            else
            {
                return redirect()->back()->with('error', 'Siamo spiacenti, ma le istruzioni per queste colonne non esistono');
            }
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazini", NULL, false, null, Auth::id(), "Istruzioni", null, $th);
            return back();
        }

    }

}
