<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Permite acesso apenas a utilizadores de departamentos médicos.
 * Detectado por: nome contém "banco", "medic", "pediatr", "clinic", "intern", "cirurg"
 */
class SoMedico
{
    public function handle($request, Closure $next)
    {
        $dep = DB::table('departamento')
            ->where('id', Auth::user()->departamento_id)
            ->value('departamento');

        if ($dep) {
            $norm = $this->normalizar($dep);
            $palavras = [
                'banco','medic','pediatr','clinic','intern','cirurg',
                'puerp','tisiolog','odont','fisio','nutric','oftalmolog',
                'neonat','gesso','p.a.v','pav','estreliz','pos-parto',
            ];
            foreach ($palavras as $p) {
                if (str_contains($norm, $p)) return $next($request);
            }
        }

        return redirect()->route('home.index')
            ->with('error', 'Acesso restrito a departamentos médicos.');
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
