<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //
    protected $table='categoria';
    protected $fillable = ['categoria','categoria_geral_id'];

    public function User()
{
    return $this->belongsTo('App\User');
}
}
