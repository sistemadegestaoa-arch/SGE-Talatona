<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    //
    protected $table = 'estoque';
    protected $fillable = [
        'produto_id','lote_id','tipo_id','departamento_id','users_id',
        'entrada','saida','situacao','data','qinicial','qfinal',
        'departamento','obs','fornecedor_id',
    ];
}
