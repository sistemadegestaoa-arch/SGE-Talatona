<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Permite acesso apenas a utilizadores do departamento de Triagem/Recepção.
 * Detectado por: nome contém "catalogac", "consultas" ou "triag" (case-insensitive, sem acentos).
 */
class SoTriagem
{
    public function handle($request, Closure $next)
    {
        $dep = DB::table('departamento')
            ->where('id', Auth::user()->departamento_id)
            ->value('departamento');

        if ($dep) {
            $norm = $this->normalizar($dep);
            if (
                str_contains($norm, 'catalogac') ||
                str_contains($norm, 'consultas') ||
                str_contains($norm, 'triag') ||
                str_contains($norm, 'recepcao') ||
                str_contains($norm, 'c.p.n') ||
                str_contains($norm, 'cpn') ||
                str_contains($norm, 's.a.t') ||
                str_contains($norm, 'sat')
            ) {
                return $next($request);
            }
        }

        return redirect()->route('home.index')
            ->with('error', 'Acesso restrito ao departamento de Triagem.');
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
