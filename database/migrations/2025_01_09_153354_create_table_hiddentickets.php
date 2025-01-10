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
        Schema::create('hiddentickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('local_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->onUpdate('cascade'); // Clave foránea después del campo id
            $table->timestamp('DateTime')->default(DB::raw('CURRENT_TIMESTAMP')); // Campo DateTime de tipo timestamp con valor por defecto CURRENT_TIMESTAMP
            $table->decimal('Value', 10, 2); // Campo Value de tipo decimal(10,2)
            $table->text('Comment'); // Campo Comment de tipo text
            $table->unsignedBigInteger('LinkedTicketId'); // Campo LinkedTicketId de tipo int(10) unsigned
            $table->foreign('LinkedTicketId')->references('id')->on('tickets')->onDelete('cascade')->onUpdate('cascade'); // Clave foránea para LinkedTicketId
            $table->timestamps(); // Campos created_at y updated_at
            $table->index('DateTime'); // Índice para el campo DateTime
            $table->index('LinkedTicketId'); // Índice para el campo LinkedTicketId
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hiddentickets');
    }
};
