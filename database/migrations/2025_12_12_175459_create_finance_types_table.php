<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('finance_types', function (Blueprint $table) {
            $table->id();

            // Nombre visible
            $table->string('name');
            // Ej: Diezmos, Ofrendas, Primicias, Evento

            // Slug interno para lógica
            $table->string('slug')->unique();
            // Ej: diezmos, ofrendas, primicias

            /*
            |--------------------------------------------------------------------------
            | Reglas de negocio
            |--------------------------------------------------------------------------
            */

            // ¿Requiere seleccionar un hermano?
            // true  → Diezmos, Primicias
            // false → Ofrendas, Evento
            $table->boolean('requires_brother')->default(false);

            // ¿Permite múltiples registros del mismo tipo en una finanza?
            // false → Ofrenda única por jornada
            // true  → Diezmos múltiples
            $table->boolean('allows_multiple')->default(true);

            // Activar / desactivar sin borrar historial
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Índices útiles
            $table->index('is_active');
            $table->index('requires_brother');
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_types');
    }
};
