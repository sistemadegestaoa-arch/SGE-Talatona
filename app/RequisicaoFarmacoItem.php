<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequisicaoFarmacoItem extends Model
{
    protected $table    = 'requisicao_farmaco_item';
    protected $fillable = [
        'requisicao_farmaco_id', 'produto_id', 'quantidade', 'observacao_item',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
