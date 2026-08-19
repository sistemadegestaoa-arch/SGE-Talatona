<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequisicaoFarmaco extends Model
{
    protected $table    = 'requisicao_farmaco';
    protected $fillable = [
        'departamento_id', 'solicitante_id', 'estado',
        'observacao', 'atendido_por', 'atendido_em',
    ];

    protected $casts = [
        'atendido_em' => 'datetime',
    ];

    public function itens()
    {
        return $this->hasMany(RequisicaoFarmacoItem::class, 'requisicao_farmaco_id');
    }

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    public function atendente()
    {
        return $this->belongsTo(User::class, 'atendido_por');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }
}
