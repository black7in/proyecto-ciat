<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acaro extends Model
{
    //
    protected $fillable = ['nombre','especie'];
    public function muestras() { return $this->hasMany(Muestra::class); }
}
