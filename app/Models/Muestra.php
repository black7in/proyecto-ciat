<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Muestra extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cliente_id','codigo','fecha_muestreo','fecha_recepcion','tipo',
        'cantidad','unidad_id','observaciones', 'estado',
        'tamano_semilla','semilla_id',
        'parte_vegetal','vegetal_id',
        'insecto_id','acaro_id',
    ];

    // Relaciones
    public function cliente(){ return $this->belongsTo(Cliente::class); }
    public function unidad(){ return $this->belongsTo(Unidad::class); }
    public function semilla(){ return $this->belongsTo(Semilla::class); }
    public function vegetal(){ return $this->belongsTo(Vegetal::class); }
    public function insecto(){ return $this->belongsTo(Insecto::class); }
    public function acaro(){ return $this->belongsTo(Acaro::class); }

    // Generación de código (ej: M-2025-000123)
    protected static function booted(){
        static::creating(function(self $m){
            if (! $m->codigo) {
                $seq = (self::whereYear('created_at', now()->year)->count() + 1);
                $m->codigo = 'M-'.now()->year.'-'.str_pad($seq, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
