<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\{EstadoMuestra, TipoMuestra, TipoSemilla, TipoVegetal};

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('muestras', function (Blueprint $table) {
            $table->id();

            // FK al cliente que entrega la muestra (si ya lo tienes)
            $table->foreignId('cliente_id')->constrained()->cascadeOnDelete();

            // Código único (tu ID visible)
            $table->string('codigo')->unique();

            // Fechas
            $table->date('fecha_muestreo')->nullable();
            $table->date('fecha_recepcion'); // cuando llega al lab

            // Tipo (superclase)
            $table->enum('tipo', TipoMuestra::cases());

            $table->enum('estado', EstadoMuestra::cases())->default('recibida');

            // Campos comunes
            $table->string('cantidad')->nullable();
            $table->foreignId('unidad_id')->nullable()->constrained('unidads')->nullOnDelete(); // de tu catálogo
            $table->text('observaciones')->nullable();

            // ---- Subtipo: SEMILLA ----
            $table->enum('tamano_semilla', TipoSemilla::cases())->nullable();
            $table->foreignId('semilla_id')->nullable()->constrained('semillas')->nullOnDelete();

            // ---- Subtipo: VEGETAL ----
            $table->enum('parte_vegetal', TipoVegetal::cases())->nullable();
            $table->foreignId('vegetal_id')->nullable()->constrained('vegetals')->nullOnDelete();

            // ---- Subtipo: INSECTO ----
            $table->foreignId('insecto_id')->nullable()->constrained('insectos')->nullOnDelete();

            // ---- Subtipo: ACARO ----
            $table->foreignId('acaro_id')->nullable()->constrained('acaros')->nullOnDelete();

            // Suelo no necesita FKs extra por ahora
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muestras');
    }
};
