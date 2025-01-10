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
        Schema::create('auxmoneystorageinfo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('local_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->onUpdate('cascade'); // Clave foránea después del campo id
            $table->string('Machine', 32); // Campo Machine de tipo varchar(32)
            $table->timestamp('LastUpdateDateTime')->useCurrent(); // Campo LastUpdateDateTime de tipo timestamp con valor por defecto CURRENT_TIMESTAMP
            //$table->unique('Machine');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auxmoneystorageinfo');
    }
};
