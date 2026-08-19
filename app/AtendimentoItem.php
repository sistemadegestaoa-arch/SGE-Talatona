<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoItem extends Model
{
    protected $table    = 'atendimento_item';
    protected $fillable = ['atendimento_id','produto_id','lote_id','quantidade'];
}
