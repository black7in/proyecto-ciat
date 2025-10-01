<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insecto extends Model
{
    //
    protected $fillable = ['nombre','especie'];
    public function muestras() { return $this->hasMany(Muestra::class); }
}
