<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Triagem extends Model
{
    protected $table    = 'triagem';
    protected $fillable = [
        'episodio_id',
        'pressao_arterial',
        'temperatura',
        'peso',
        'altura',
        'frequencia_cardiaca',
        'frequencia_respiratoria',
        'saturacao_oxigenio',
        'observacao',
    ];

    public function episodio()
    {
        return $this->belongsTo(Episodio::class, 'episodio_id');
    }

    /** Calcula o IMC se peso e altura estiverem preenchidos */
    public function getImcAttribute()
    {
        if (!$this->peso || !$this->altura) return null;
        $alturaM = $this->altura / 100;
        return round($this->peso / ($alturaM * $alturaM), 1);
    }
}
