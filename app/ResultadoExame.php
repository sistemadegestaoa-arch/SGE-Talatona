<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResultadoExame extends Model
{
    protected $table    = 'resultado_exame';
    protected $fillable = [
        'pedido_exame_id', 'tecnico_id',
        'resultado', 'ficheiro_path', 'data_resultado',
    ];

    protected $casts = [
        'data_resultado' => 'date',
    ];

    // ── Relações ────────────────────────────────────────────────────────────

    public function pedidoExame()
    {
        return $this->belongsTo(PedidoExame::class, 'pedido_exame_id');
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    /** Verifica se tem ficheiro anexado */
    public function temFicheiro()
    {
        return !empty($this->ficheiro_path);
    }
}
