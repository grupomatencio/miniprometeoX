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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('local_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->onUpdate('cascade'); // Clave foránea después del campo id
            $table->string('Player', 45)->nullable(false); // Campo Player de tipo varchar(45) no nulo
            $table->string('Password', 45)->nullable(false); // Campo Password de tipo varchar(45) no nulo
            $table->decimal('MoneyIn', 10, 2)->default('0.00'); // Campo MoneyIn de tipo decimal(10,2) con valor por defecto '0.00'
            $table->decimal('MoneyOut', 10, 2)->default('0.00'); // Campo MoneyOut de tipo decimal(10,2) con valor por defecto '0.00'
            $table->decimal('MoneyDrop', 10, 2)->default('0.00'); // Campo MoneyDrop de tipo decimal(10,2) con valor por defecto '0.00'
            $table->decimal('Points', 10, 2)->default('0.00'); // Campo Points de tipo decimal(10,2) con valor por defecto '0.00'
            $table->string('PID', 128)->default(''); // Campo PID de tipo varchar(128) con valor por defecto ''
            $table->string('NickName', 64)->default(''); // Campo NickName de tipo varchar(64) con valor por defecto ''
            $table->string('Avatar', 1024)->default(''); // Campo Avatar de tipo varchar(1024) con valor por defecto ''
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
