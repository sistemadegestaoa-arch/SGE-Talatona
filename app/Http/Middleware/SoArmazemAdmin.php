<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\PermissaoHelper;

class SoArmazemAdmin
{
    public function handle($request, Closure $next)
    {
        if (!PermissaoHelper::podeEliminar()) {
            return redirect()->back()
                ->with('error', 'Apenas o administrador do Armazém Central pode realizar esta operação.');
        }

        return $next($request);
    }
}
