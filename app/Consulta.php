<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $table    = 'consulta';
    protected $fillable = [
        'episodio_id', 'medico_id',
        'diagnostico', 'observacao', 'data',
    ];

    protected $casts = [
        'data' => 'date',
    ];

    // ── Relações ────────────────────────────────────────────────────────────

    public function episodio()
    {
        return $this->belongsTo(Episodio::class, 'episodio_id');
    }

    public function medico()
    {
        return $this->belongsTo(User::class, 'medico_id');
    }

    public function pedidosExame()
    {
        return $this->hasMany(PedidoExame::class, 'consulta_id');
    }

    public function receitas()
    {
        return $this->hasMany(Receita::class, 'consulta_id');
    }

    /** Verifica se há pedidos de exame pendentes */
    public function temExamesPendentes()
    {
        return $this->pedidosExame()->where('estado', 'pendente')->exists();
    }
}
