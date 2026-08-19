<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Permite acesso apenas a utilizadores do departamento de Laboratório.
 * Detectado por: nome contém "lab"
 */
class SoLaboratorio
{
    public function handle($request, Closure $next)
    {
        $dep = DB::table('departamento')
            ->where('id', Auth::user()->departamento_id)
            ->value('departamento');

        if ($dep) {
            $norm = mb_strtolower($dep);
            $norm = strtr($norm, [
                'á'=>'a','à'=>'a','â'=>'a','ã'=>'a',
                'é'=>'e','è'=>'e','ê'=>'e',
                'í'=>'i','ó'=>'o','ô'=>'o','õ'=>'o',
                'ú'=>'u','ç'=>'c',
            ]);
            if (
                str_contains($norm, 'lab') ||
                str_contains($norm, 'raio') ||
                str_contains($norm, 'hemot') ||
                str_contains($norm, 'cada')
            ) {
                return $next($request);
            }
        }

        return redirect()->route('home.index')
            ->with('error', 'Acesso restrito ao departamento de Laboratório / Diagnóstico.');
    }
}
