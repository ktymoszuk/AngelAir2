<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComandiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Comandi', function (Blueprint $table) {
            $table->id();

            //AxId automazione
            $table->char('AxId', 30)->nullable();

            //AxAppTag automazione
            $table->string('AxAppTag', 8)->nullable();

            //AxSystemTag automazione
            $table->string('AxSystemTag', 15)->nullable();

            //Nome del comando
            $table->string('Nome', 50)->nullable();

            //Codice comando LoRaWAN per invio
            $table->string('Codice', 16)->nullable();

            //Descrizione generale del comando
            $table->text('Descrizione')->nullable();

            //Comando invio automatico o meno
            $table->boolean('isAutomatico')->default(0);

            //AInvio manuale o non manuale
            $table->boolean('isManuale')->default(0);

            //Chiave esterna categoria dispositivi
            $table->bigInteger('codTipoDisp');

            //Chiave esterna automazione
            $table->bigInteger('codAutomazione');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Comandi');
    }
}