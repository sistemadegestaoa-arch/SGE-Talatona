<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class requisicao extends Model
{
    //
    protected $table ='requisicao';
    protected $fillable=['requisicao','data','departamento_id','users_id','statos','departamento'];
}
