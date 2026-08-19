<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Departamento;

class AdminExists
{
    /**
     * Bloqueia acesso à rota de setup se já existir um admin na Direcção.
     */
    public function handle($request, Closure $next)
    {
        $direcao = Departamento::where('departamento', 'Direcção')->first();

        if ($direcao) {
            $adminExiste = User::where('tipo', 'admin')
                ->where('departamento_id', $direcao->id)
                ->exists();

            if ($adminExiste) {
                return redirect()->route('login')
                    ->with('admin_exists', 'O administrador já foi configurado. Faça login para aceder ao sistema.');
            }
        }

        return $next($request);
    }
}
