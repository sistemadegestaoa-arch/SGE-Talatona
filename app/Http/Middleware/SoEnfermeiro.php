<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Permite acesso apenas a departamentos de enfermagem / S.O. / observação.
 * Detectado por: s.o, sala de observ, enferm, s.a.t, p.a.v, pav
 */
class SoEnfermeiro
{
    public function handle($request, Closure $next)
    {
        $dep = DB::table('departamento')
            ->where('id', Auth::user()->departamento_id)
            ->value('departamento');

        if ($dep) {
            $norm = $this->normalizar($dep);
            $palavras = [
                's.o', 'sala de observ', 'observa', 'enferm',
                's.a.t', 'sat', 'p.a.v', 'pav',
            ];
            foreach ($palavras as $p) {
                if (str_contains($norm, $p)) return $next($request);
            }
        }

        return redirect()->route('home.index')
            ->with('error', 'Acesso restrito ao departamento de Enfermagem / S.O.');
    }

    private function normalizar(string $str): string
    {
        $s = mb_strtolower($str);
        return strtr($s, [
            'á'=>'a','à'=>'a','â'=>'a','ã'=>'a',
            'é'=>'e','è'=>'e','ê'=>'e',
            'í'=>'i','ì'=>'i','î'=>'i',
            'ó'=>'o','ò'=>'o','ô'=>'o','õ'=>'o',
            'ú'=>'u','ù'=>'u','û'=>'u',
            'ç'=>'c','ñ'=>'n',
        ]);
    }
}
