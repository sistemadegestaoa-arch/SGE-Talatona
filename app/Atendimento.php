<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Atendimento extends Model
{
    protected $table    = 'atendimento';
    protected $fillable = ['receita_id','requisicao_id','utente','processo','departamento_id','users_id','observacao','data'];

    public function itens()
    {
        return $this->hasMany(AtendimentoItem::class, 'atendimento_id');
    }
}
