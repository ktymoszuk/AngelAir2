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
        Schema::connection('mysql')->create('CronologiaComandi', function (Blueprint $table) {

            $table->id();

            //Chiave esterna comando
            $table->integer('codComando')->nullable();

            //Chiave esterna utente
            $table->integer('codUtente')->nullable();

            //deveui dispositivo
            $table->string('Deveui', 16)->nullable();

            //Indica se il comando è stato inviato in modo automatico o manuale
            $table->boolean('isAutomatico')->default(1);

            //Indica se il comando è stato inviato correttamente
            $table->boolean('isElaborato')->default(0);

            //DataOra invio comando
            $table->timestamp('DataOra')->useCurrent();
         
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
};
