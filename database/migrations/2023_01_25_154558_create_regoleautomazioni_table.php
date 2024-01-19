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
        Schema::connection('mysql')->create('RegoleAutomazioni', function (Blueprint $table) {

            $table->id();

            //Chiave esterna scenario di riferimento
            $table->integer('Scenario')->nullable();

            //Nome regola
            $table->string('Nome', 50)->nullable();

            //Chiave esterna soglia
            $table->integer('codSoglia')->nullable();

            //Lista chiave esterna comando
            $table->longText('codComando')->nullable();

            //Lista chiave esterna dispositivo
            $table->longText('codDispositivo')->nullable();

            //Chiave esterna categoria dispositivo
            $table->integer('codCategoriaDisp')->nullable();

            //Chiave esterna categoria automazione
            $table->integer('codCategoriaAutomazione')->nullable();

            //Lista chiave esterna struttura
            $table->longText('codStruttura')->nullable();

            //Chiave esterna automazione
            $table->integer('codAutomazione')->nullable();

            //Chiave esterna categoria dispositivo che fa scattare la condizione della regola 
            $table->integer('Appartenenza')->nullable();

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('RegoleAutomazioni');
    }
};
