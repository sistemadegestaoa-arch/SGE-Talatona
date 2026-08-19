<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table    = 'paciente';
    protected $fillable = [
        'nome', 'data_nascimento', 'sexo',
        'numero_processo', 'telefone', 'morada',
    ];

    /** Todos os episódios deste paciente */
    public function episodios()
    {
        return $this->hasMany(Episodio::class, 'paciente_id');
    }

    /** Episódio de hoje (se existir) */
    public function episodioHoje()
    {
        return $this->hasOne(Episodio::class, 'paciente_id')
                    ->whereDate('data', today());
    }

    /** Calcula a idade a partir da data de nascimento */
    public function getIdadeAttribute()
    {
        if (!$this->data_nascimento) return null;
        return \Carbon\Carbon::parse($this->data_nascimento)->age;
    }
}
