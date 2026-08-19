<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoExame extends Model
{
    protected $table    = 'pedido_exame';
    protected $fillable = [
        'consulta_id', 'medico_id',
        'descricao_exame', 'urgente',
        'estado', 'observacao', 'data_pedido',
    ];

    protected $casts = [
        'urgente'     => 'boolean',
        'data_pedido' => 'date',
    ];

    // ── Relações ────────────────────────────────────────────────────────────

    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'consulta_id');
    }

    public function medico()
    {
        return $this->belongsTo(User::class, 'medico_id');
    }

    public function resultado()
    {
        return $this->hasOne(ResultadoExame::class, 'pedido_exame_id');
    }

    public function isPendente()  { return $this->estado === 'pendente'; }
    public function isConcluido() { return $this->estado === 'concluido'; }
}
