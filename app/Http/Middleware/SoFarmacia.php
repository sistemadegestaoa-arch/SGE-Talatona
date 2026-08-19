<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SoFarmacia
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // Admin tem sempre acesso
        if ($user->tipo === 'admin') {
            return $next($request);
        }

        $dep = DB::table('departamento')
            ->where('id', $user->departamento_id)
            ->value('departamento');

        // Permite acesso se o departamento contiver "farm"
        if ($dep && stripos($dep, 'farm') !== false) {
            return $next($request);
        }

        return redirect()->route('home.index')
            ->with('error', 'Acesso restrito ao departamento de Farmácia.');
    }
}
