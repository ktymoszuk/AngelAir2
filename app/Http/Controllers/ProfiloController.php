<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Manager\LogManager;

use Illuminate\Http\Request;
use App\Manager\LogicManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Hash;


class ProfiloController extends Controller
{
    //Vista pagina
    public function view()
    {
        return view('profilo');
    }

    public function dati() {
        $risultato = Auth::user();
        return response()->json($risultato);
    }

    public function update(Request $request)
    {
        try{
            $name = LogicManager::generazioneQuery($request->all());

            $res = User::where('id', $request->id)->update($name);

            if($res){
                LogManager::scritturaLogs("operazioni", 0, true, $request->ip(), Auth::id(), "Profilo", $request, NULL);
                Session::flash("success", LogManager::messaggiOperazioni(0, true, "Profilo"));
            }else{
                LogManager::scritturaLogs("operazioni", 0, false, $request->ip(), Auth::id(), "Profilo", $request, NULL);
                Session::flash("error", LogManager::messaggiOperazioni(0, false, "Profilo"));
            }
            return back();
        }catch(\Throwable $th){
            return $th;
            Log::channel('operazioni')->info('Modifica utente fallita');
            return back();
        }
    }

    public function password(PasswordRequest $request) {
        try {
            $query = LogicManager::generazioneQuery($request->all());
            $user = User::where('id', $request->id)->first();
            $old = Hash::check($request->old, $user->password);
            $new = $request->new == $request->confirm;
            if ($old) {
                if ($new) {
                    // $res = User::where('id', $request->id)->update(
                    //     ['password' => $password]
                    // );
                    $user = User::where('id', $request->id)->first();
                    
                    $res = $user->forceFill([
                        'password' => Hash::make($query['new']),
                    ])->save();
            
    
                    if($res){
                        LogManager::scritturaLogs("utenze", 1, true, $request->ip(), Auth::id(), "Password", $request, NULL);
                        Session::flash("success", LogManager::messaggiOperazioni(1, true, "Password"));
                    }else{
                        LogManager::scritturaLogs("utenze", 1, false, $request->ip(), Auth::id(), "Password", $request, NULL);
                        Session::flash("error", LogManager::messaggiOperazioni(1, false, "Password"));
                    }
                    return back();
                    
                } else {
                    LogManager::scritturaLogs("utenze", 1, false, $request->ip(), Auth::id(), "Password", $request, NULL);
                    Session::flash("error", 'La conferma della password non è corretta');
                    return back();
                }
            }  else {
                LogManager::scritturaLogs("utenze", 1, false, $request->ip(), Auth::id(), "Password", $request, NULL);
                Session::flash("error", 'La password attuale non è corretta');
                return back();
            }       
        } catch (\Throwable $th) {
            LogManager::scritturaLogs("operazini", 1, false, $request->ip(), Auth::id(), "Password", $request, $th);
            Session::flash("error", LogManager::messaggiOperazioni(1, false, "Password"));
            return back();
        }
        
    }

}
