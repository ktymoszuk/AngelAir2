<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronologiaComandiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CronologiaComandi', function (Blueprint $table) {
            $table->id();

            //AxId automazione
            $table->char('AxId', 30)->nullable();

            //AxAppTag automazione
            $table->string('AxAppTag', 8)->nullable();

            //AxSystemTag automazione
            $table->string('AxSystemTag', 15)->nullable();

            //Chiave esterna comando 
            $table->bigInteger('codComando')->nullable();

            //Chiave esterna utente
            $table->bigInteger('codUtente')->nullable();

            // Chiave esterna dispositivo
            $table->string("DevEui", 16);

            // Indica se il comando è stato inviato in modalità automatica o manuale
            $table->boolean("isAutomatico")->default(1);

            // Indica se il comando è stato inviato correttamente
            $table->boolean("isElaborato")->default(0);

            // Dataora invio comando
            $table->timestamp("DataOra")->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('CronologiaComandi');
    }
}
