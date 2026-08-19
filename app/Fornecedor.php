<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    //
    protected $table ='fornecedor';

    protected $fillable=['fornecedor','telefone','nif','email','emdereco'];
}
