<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semilla extends Model
{
    //
    protected $fillable = ['nombre_comun', 'nombre_cientifico'];
    public function muestras()
    {
        return $this->hasMany(Muestra::class);
    }
}
