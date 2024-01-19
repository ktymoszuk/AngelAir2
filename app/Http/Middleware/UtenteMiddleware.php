<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UtenteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        // if (Auth::id() != null) {
        //     $res = User::where('id', Auth::id())->first();
        //     if (!Auth::check() || !$res->isAbilitato) {
        //         Log::channel('utenze')->alert('Utente '.Auth::id().' '.$request->ip().': Non autorizzato');
        //         return redirect()->route('errore');
        //     }
        // } else {
        //     Log::channel('utenze')->alert('Accesso negato alla risorsa '.$request->ip());
        //     return redirect()->route('errore');
        // }
        
        return $next($request);
    }
}
