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
        Schema::create('Strutture', function (Blueprint $table) {
           
            $table->id();
            
            //Nome della struttura
            $table->string('Nome', 50)->nullable();

            //Indirizzo geografico struttura
            $table->string('Indirizzo', 50)->default('Località Sconosciuta');
           
            //Coordinate struttura
            $table->decimal('Latitudine', 10, 7)->default(0.0000000);
            $table->decimal('Longitudine', 10, 7)->default(0.0000000);
            
            //Posizione kilometrica stradale
            $table->string('Km', 10)->default('0+000');

            //Identificativo del sistema per integrazione con altri moduli Axatel
            $table->string('Identificativo', 10)->nullable();
            
            //Sinottico di campo della struttura
            $table->string('Cartografia', 150)->nullable();
            
            //Ing. responsabile di riferimento della struttura
            $table->string('Referente', 150)->nullable();
            
            //Chiave esterna automazione
            $table->integer('codAutomazione')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Strutture');
    }
};
