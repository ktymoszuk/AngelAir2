<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutomazioniTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Automazioni', function (Blueprint $table) {
            $table->id();

            //AxId automazione
            $table->char('AxId', 30)->nullable();

            //AxAppTag automazione
            $table->string('AxAppTag', 8)->nullable();

            //AxSystemTag automazione
            $table->string('AxSystemTag', 15)->nullable();

            //Nome dell'automazione
            $table->string('Nome', 50)->nullable();

            //Chiave esterna ana1liv
            $table->bigInteger('id_1liv')->nullable();

            //Chiave esterna ana2liv
            $table->bigInteger('id_2liv')->nullable();

            //Chiave esterna ana3liv
            $table->bigInteger('id_3liv')->nullable();

            //Chiave esterna ana4liv
            $table->bigInteger('id_4liv')->nullable();

            //Latitudine
            $table->decimal('Latitudine',12, 7)->default(0.0000000);

            //Longitudine
            $table->decimal('Longitudine', 12, 7)->default(0.0000000);

            //Km
            $table->string("Km", 10)->default("0+000");

            //Sinottico di sistema
            $table->string("Cartografia", 100)->nullable();

            // Stato automazione attiva o disattiva
            $table->boolean('Stato')->default(0);

            // id tratta da Angel
            $table->bigInteger("IdTratta")->default(0);

            // id carreggiata da Angel
            $table->bigInteger("IdCarreggiata")->default(0);

            // id svincolo da Angel
            $table->bigInteger("IdSvincolo")->default(0);

            // id ramo da Angel
            $table->bigInteger("IdRamo")->default(0);

            // id rotatoria da Angel
            $table->bigInteger("IdRotatoria")->default(0);

            // Dataora aggiornamento stato automazione
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
        Schema::dropIfExists('Automazioni');
    }
}
