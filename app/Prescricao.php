<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prescricao extends Model
{
    protected $table    = 'prescricao';
    protected $fillable = [
        'consulta_id', 'medico_id', 'diagnostico', 'observacao', 'data',
    ];

    protected $casts = ['data' => 'date'];

    public function itens()
    {
        return $this->hasMany(PrescricaoItem::class, 'prescricao_id');
    }

    public function medico()
    {
        return $this->belongsTo(User::class, 'medico_id');
    }

    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'consulta_id');
    }
}
