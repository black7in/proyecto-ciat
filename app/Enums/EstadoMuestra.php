<?php
namespace App\Enums;

enum EstadoMuestra: string
{
    case RECIBIDA   = 'recibida';
    case EN_PROCESO = 'en_proceso';
    case FINALIZADA = 'finalizada';
    case REPORTADA  = 'reportada';
    case RECHAZADA  = 'rechazada';

    public function label(): string
    {
        return match($this) {
            self::RECIBIDA   => 'Recibida',
            self::EN_PROCESO => 'En proceso',
            self::FINALIZADA => 'Finalizada',
            self::REPORTADA  => 'Reportada',
            self::RECHAZADA  => 'Rechazada',
        };
    }

    public static function options(): array
    {
        return array_map(fn($e) => $e->value, self::cases());
    }
}
