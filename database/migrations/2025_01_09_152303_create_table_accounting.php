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
        Schema::create('accounting', function (Blueprint $table) {
            $table->id();
            $table->foreignId('local_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade'); // Definir la clave foránea
            $table->string('Machine', 32); // Campo Machine de tipo varchar(32)
            $table->string('Counter'); // Campo Counter de tipo varchar
            $table->string('Category'); // Campo Category de tipo varchar
            $table->string('Description'); // Campo Description de tipo varchar
            $table->decimal('Amount', 10, 2); // Campo Amount de tipo decimal(10,2)
            $table->string('Text'); // Campo Text de tipo varchar
            $table->timestamp('LastAccess')->useCurrent(); // Campo LastAccess de tipo timestamp con valor por defecto CURRENT_TIMESTAMP
            //$table->unique('Machine');
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting');
    }
};
