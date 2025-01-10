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
        Schema::create('collectdetails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('local_id')
                    ->references('id')
                    ->on('locals')
                    ->onDelete('cascade')
                    ->onUpdate('cascade'); // Definir la clave foránea
            $table->string('UserMoney');
            $table->string('Name', 64);
            $table->decimal('Money1', 10, 2);
            $table->decimal('Money2', 10, 2);
            $table->decimal('Money3', 10, 2);
            $table->char('CollectDetailType', 1)->default('0');
            $table->char('State', 1)->default('A');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collectdetails');
    }
};
