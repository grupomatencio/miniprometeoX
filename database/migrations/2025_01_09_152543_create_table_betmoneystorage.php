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
        Schema::create('betmoneystorage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('local_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->onUpdate('cascade'); // Clave foránea después del campo id
            $table->string('Machine', 32); // Campo Machine de tipo varchar(32)
            $table->decimal('MoneyIn', 10, 2); // Campo MoneyIn de tipo decimal(10,2)
            $table->decimal('MoneyOut', 10, 2); // Campo MoneyOut de tipo decimal(10,2)
            $table->char('State', 1)->default('A'); // Campo State de tipo char(1) con valor por defecto 'A'
            //$table->unique('Machine');
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('betmoneystorage');
    }
};
