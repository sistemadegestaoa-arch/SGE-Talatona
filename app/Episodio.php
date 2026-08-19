<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Episodio extends Model
{
    protected $table    = 'episodio';
    protected $fillable = [
        'paciente_id', 'triagem_user_id', 'data', 'estado', 'urgente', 'senha', 'chamado_em',
    ];

    protected $casts = [
        'data'       => 'date',
        'urgente'    => 'boolean',
        'chamado_em' => 'datetime',
    ];

    // ── Relações ────────────────────────────────────────────────────────────

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function triagemUser()
    {
        return $this->belongsTo(User::class, 'triagem_user_id');
    }

    public function triagem()
    {
        return $this->hasOne(Triagem::class, 'episodio_id');
    }

    public function consulta()
    {
        return $this->hasOne(Consulta::class, 'episodio_id');
    }

    // ── Helpers de estado ───────────────────────────────────────────────────

    public function isEmEspera()    { return $this->estado === 'em_espera'; }
    public function isEmConsulta()  { return $this->estado === 'em_consulta'; }
    public function isAguardaExame(){ return $this->estado === 'aguarda_exame'; }
    public function isConcluido()   { return $this->estado === 'concluido'; }
}
