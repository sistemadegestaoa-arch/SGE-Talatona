<?php

namespace App\Http\Controllers\Traits;

use App\Helpers\PermissaoHelper;

trait VerificaPermissaoEliminar
{
    protected function podeEliminar(): bool
    {
        return PermissaoHelper::podeEliminar();
    }

    protected function abortSeNaoPodeEliminar()
    {
        if (!$this->podeEliminar()) {
            return redirect()->back()
                ->with('error', 'Sem permissão. Apenas o administrador do Armazém Central pode eliminar registos.');
        }
        return null;
    }
}
