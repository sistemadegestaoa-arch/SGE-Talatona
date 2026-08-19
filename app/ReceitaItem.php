<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceitaItem extends Model
{
    protected $table    = 'receita_item';
    protected $fillable = [
        'receita_id', 'produto_id',
        'dose', 'frequencia', 'duracao', 'quantidade',
    ];

    // ── Relações ────────────────────────────────────────────────────────────

    public function receita()
    {
        return $this->belongsTo(Receita::class, 'receita_id');
    }

    /** Produto do catálogo existente */
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
