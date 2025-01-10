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
        Schema::create('betmoneystorageinfo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('local_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->onUpdate('cascade'); // Clave foránea después del campo id
            $table->string('Machine', 32); // Campo Machine de tipo varchar(32)
            $table->timestamp('LastUpdateDateTime')->default(DB::raw('CURRENT_TIMESTAMP')); // Campo LastUpdateDateTime de tipo timestamp con valor por defecto CURRENT_TIMESTAMP
            //$table->unique('Machine'); // Clave única para el campo Machine usando BTREE
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('betmoneystorageinfo');
    }
};
