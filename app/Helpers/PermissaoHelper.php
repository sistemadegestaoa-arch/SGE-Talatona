<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class PermissaoHelper
{
    /**
     * Verifica se o utilizador autenticado pode eliminar registos.
     * Condição: tipo "admin" E departamento contém "armazem" (normalizado).
     */
    public static function podeEliminar(): bool
    {
        $user = auth()->user();

        if (!$user) return false;
        if ($user->tipo !== 'admin') return false;

        $dep = DB::table('departamento')
            ->where('id', $user->departamento_id)
            ->value('departamento');

        if (!$dep) return false;

        return self::normalizarContemArmazem($dep);
    }

    /**
     * Normaliza string removendo acentos e verifica se contém "armazem".
     */
    public static function normalizarContemArmazem(string $dep): bool
    {
        $norm = mb_strtolower($dep);
        $norm = strtr($norm, [
            'á'=>'a','à'=>'a','â'=>'a','ã'=>'a','ä'=>'a',
            'é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
            'í'=>'i','ì'=>'i','î'=>'i','ï'=>'i',
            'ó'=>'o','ò'=>'o','ô'=>'o','õ'=>'o','ö'=>'o',
            'ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u',
            'ç'=>'c','ñ'=>'n',
        ]);
        return str_contains($norm, 'armazem');
    }
}
