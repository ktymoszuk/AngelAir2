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
        Schema::connection('mysql2')->create('StatoDisp', function (Blueprint $table) {

            $table->id('id_statodisp');

            $table->unsignedInteger('id')->nullable();

            //Numero Frame ricevuto dal dispositivo
            $table->unsignedInteger('Frame')->nullable();

            //DevEui dispositivo
            $table->string('DevEui', 16)->nullable();

            //Indirizzo
            $table->string('AddrSec', 30)->nullable();

            //Ora del pacchetto LoRaWan
            $table->dateTime('DataOra')->nullable();

            //Dati del dispositivo LoRaWAN
            $table->string('Testo1', 150)->nullable();
            $table->string('Testo2', 150)->nullable();
            $table->string('Testo3', 150)->nullable();
            $table->string('Testo4', 150)->nullable();
            $table->string('Testo5', 150)->nullable();
            $table->string('Testo6', 150)->nullable();
            $table->string('Testo7', 150)->nullable();
            $table->string('Testo8', 150)->nullable();
            $table->string('Testo9', 150)->nullable();
            $table->string('Testo10', 150)->nullable();
            $table->string('Numero1', 150)->nullable();
            $table->string('Numero2', 150)->nullable();
            $table->string('Numero3', 150)->nullable();
            $table->string('Numero4', 150)->nullable();
            $table->string('Numero5', 150)->nullable();
            $table->string('Numero6', 150)->nullable();
            $table->string('Numero7', 150)->nullable();
            $table->string('Numero8', 150)->nullable();
            $table->string('Numero9', 150)->nullable();
            $table->string('Numero10', 150)->nullable();
            $table->string('FloatP1', 150)->nullable();
            $table->string('FloatP2', 150)->nullable();
            $table->string('FloatP3', 150)->nullable();
            $table->string('FloatP4', 150)->nullable();
            $table->string('FloatP5', 150)->nullable();
            $table->string('FloatP6', 150)->nullable();
            $table->string('FloatP7', 150)->nullable();
            $table->string('FloatP8', 150)->nullable();
            $table->string('FloatP9', 150)->nullable();
            $table->string('FloatP10', 150)->nullable();

            //Stato del dispositivo
            $table->tinyInteger('Stato')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('StatoDisp');
    }
};
