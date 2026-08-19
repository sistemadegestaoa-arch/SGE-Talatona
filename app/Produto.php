<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    //
    protected $table='produto';

    protected $fillable=['produto','apresentacao','categoria_id',
'data_aquisicao','quantidade','stokminimo','categoria_geral_id','codigo',
'bloqueado','motivo_bloqueio','bloqueado_por','bloqueado_em'];

    protected $casts = [
        'bloqueado'   => 'boolean',
        'bloqueado_em'=> 'datetime',
    ];

    public function isBloqueado(): bool
    {
        return (bool) $this->bloqueado;
    }
}
