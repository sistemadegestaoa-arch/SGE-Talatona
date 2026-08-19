<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receita extends Model
{
    protected $table    = 'receita';
    protected $fillable = [
        'consulta_id', 'medico_id',
        'estado', 'observacao', 'data',
    ];

    protected $casts = [
        'data' => 'date',
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

    public function itens()
    {
        return $this->hasMany(ReceitaItem::class, 'receita_id');
    }

    public function isPendente()   { return $this->estado === 'pendente'; }
    public function isDispensada() { return $this->estado === 'dispensada'; }
}
