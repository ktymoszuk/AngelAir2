<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('CategorieSoglie', function (Blueprint $table) {
            
            $table->id();

            //Nome della catgoria soglia
            $table->string('Nome', 50)->nullable();

            //Colore riferimento della categoria 
            $table->string('Colore', 10)->nullable();

            //Chiave esterna categoria automazione
            $table->integer('codCategoriaAutomazione')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('CategorieSoglie');
    }
};
