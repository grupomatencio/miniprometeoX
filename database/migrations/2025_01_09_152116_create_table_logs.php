<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('local_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->onUpdate('cascade'); // Clave foránea después del campo id
            $table->string('Type', 50); // Campo Type de tipo varchar(50)
            $table->text('Text'); // Campo Text de tipo text
            $table->string('Link', 255); // Campo Link de tipo varchar(255)
            $table->timestamp('DateTime')->default(DB::raw('CURRENT_TIMESTAMP')); // Campo DateTime de tipo timestamp con valor por defecto CURRENT_TIMESTAMP
            $table->integer('DateTimeEx')->nullable(); // Campo DateTimeEx de tipo int(10) nullable
            $table->string('IP', 15); // Campo IP de tipo varchar(15)
            $table->string('User', 45); // Campo User de tipo varchar(45)
            $table->timestamps(); // Campos created_at y updated_at
            $table->index('Type'); // Índice para el campo Type
            $table->index('DateTime'); // Índice para el campo DateTime
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
