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
        Schema::create('TipoDisp', function (Blueprint $table) {
            $table->id('IdTipo');

            //Nome del dispositivo
            $table->string('Nome', 50)->nullable();

            //Note sul dispositivo
            $table->text('Note')->nullable();

            // Icona tipologia dispositivo
            $table->string("Logo", 150)->nullable();

            // Icona da visualizzare in mappa
            $table->string("PinMappa")->nullable();

            //Dati del dispositivo LoRaWAN
            $table->string('Testo1', 50)->nullable();
            $table->string('Testo2', 50)->nullable();
            $table->string('Testo3', 50)->nullable();
            $table->string('Testo4', 50)->nullable();
            $table->string('Testo5', 50)->nullable();
            $table->string('Testo6', 50)->nullable();
            $table->string('Testo7', 50)->nullable();
            $table->string('Testo8', 50)->nullable();
            $table->string('Testo9', 50)->nullable();
            $table->string('Testo10', 50)->nullable();
            $table->string('Numero1', 50)->nullable();
            $table->string('Numero2', 50)->nullable();
            $table->string('Numero3', 50)->nullable();
            $table->string('Numero4', 50)->nullable();
            $table->string('Numero5', 50)->nullable();
            $table->string('Numero6', 50)->nullable();
            $table->string('Numero7', 50)->nullable();
            $table->string('Numero8', 50)->nullable();
            $table->string('Numero9', 50)->nullable();
            $table->string('Numero10', 50)->nullable();
            $table->string('Numero11', 50)->nullable();
            $table->string('FloatP1', 50)->nullable();
            $table->string('FloatP2', 50)->nullable();
            $table->string('FloatP3', 50)->nullable();
            $table->string('FloatP4', 50)->nullable();
            $table->string('FloatP5', 50)->nullable();
            $table->string('FloatP6', 50)->nullable();
            $table->string('FloatP7', 50)->nullable();
            $table->string('FloatP8', 50)->nullable();
            $table->string('FloatP9', 50)->nullable();
            $table->string('FloatP10', 50)->nullable();

            //Nome del parser per ROBOCOP
            $table->string('ParserName', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TipoDisp');
    }
};
