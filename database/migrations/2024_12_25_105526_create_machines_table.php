<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alias');

            // Relación opcional con la tabla locals
            $table->foreignId('local_id')
                ->nullable()
                ->constrained('locals')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // Relación opcional con la tabla bars
            $table->foreignId('bar_id')
                ->nullable()
                ->constrained('bars')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('delegation_id')
                ->nullable()
                ->constrained('delegations')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('identificador');

            // Definir un campo tipo que indica si es parent (1) o roulette (2)
            $table->enum('type', ['parent', 'roulette','single'])->nullable();

            // Campo para asociar hijos a una máquina (ya sea tipo parent o roulette)
            $table->foreignId('parent_id')->nullable()->constrained('machines')->cascadeOnDelete();

            // Campo numérico r_auxiliar money (valor de 1 a 50)
            $table->integer('r_auxiliar')->unsigned()->nullable()->check('r_auxiliar >= 1 AND r_auxiliar <= 50');

            // placa de ComData, numero repetitivo que se diferencia por el machine_id, cada placa va asociada a una maquina
            //$table->intenger('Number_comData');

            $table->timestamps();

            // Restricción única para asegurar que una máquina no esté en ambos simultáneamente
            $table->unique(['local_id', 'bar_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};
