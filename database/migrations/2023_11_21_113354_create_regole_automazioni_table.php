<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegoleAutomazioniTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('RegoleAutomazioni', function (Blueprint $table) {
            $table->id();

            // Chiave esterna scenario
            $table->bigInteger("Scenario");

            // Nome regola automazione
            $table->string("Nome", 50);

            // Lista comandi da inviare
            $table->longText("codComando");

            // Lista dispositivi che devono ricevere i comandi
            $table->longText("codDispositivo");
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
}
