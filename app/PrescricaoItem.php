<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrescricaoItem extends Model
{
    protected $table    = 'prescricao_item';
    protected $fillable = [
        'prescricao_id', 'medicamento', 'forma_farmaceutica',
        'dosagem', 'dose', 'frequencia', 'duracao', 'quantidade', 'instrucoes',
    ];
}
