<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSincronizzazioniTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Sincronizzazioni', function (Blueprint $table) {
            $table->id();

            // id nel sistema
            $table->char("AxId", 30);

            // id applicazione nel sistema
            $table->string("AxAppTag", 8);

            // id soluzione IoT nel sistema
            $table->string("AxSystemTag", 15);

            //Operazione da svolgere quando le tabelle non sono sincronizzate
            $table->string('Operazione', 250)->default("0");

            //Tabella di appartenenza del dato
            $table->string('NomeTabella', 50)->nullable();

            //Stato sincronizzazione 0 --> non sincronizzato 1 --> sincronizzato
            $table->boolean('isSincCampo')->default(0);

            //Stato sincronizzazione 0 --> non sincronizzato 1 --> sincronizzato
            $table->boolean('isSincCartografia')->default(0);

            //Stato sincronizzazione 0 --> non sincronizzato 1 --> sincronizzato
            $table->boolean('isSincAngel')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Sincronizzazioni');
    }
}
