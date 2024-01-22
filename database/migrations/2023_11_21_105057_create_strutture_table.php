<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStruttureTable extends Migration
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

            // id nel sistema
            $table->char("AxId", 30);

            // id applicazione nel sistema
            $table->string("AxAppTag", 8);

            // id soluzione IoT nel sistema
            $table->string("AxSystemTag", 15);

            //Chiave esterna ana1liv
            $table->bigInteger('id_1liv')->nullable();

            //Chiave esterna ana2liv
            $table->bigInteger('id_2liv')->nullable();

            //Chiave esterna ana3liv
            $table->bigInteger('id_3liv')->nullable();

            //Chiave esterna ana4liv
            $table->bigInteger('id_4liv')->nullable();

            //Nome del dispositivo
            $table->string('Nome', 50)->nullable();

            //Indirizzo geografico struttura
            $table->string("Indirizzo", 50)->nullable();

            //Coordinate geografice
            $table->decimal("Latitudine", 10, 7)->default(0.0000000);
            $table->decimal("Longitudine", 10, 7)->default(0.0000000);

            //Km
            $table->string("Km", 10)->default("0+000");

            // Sinottico di campo della struttura
            $table->string("Cartografia", 150)->nullable();
            $table->string("Cartografia2", 150)->nullable();
            $table->string("Cartografia3", 150)->nullable();

            //Responsabile di riferimento della struttura
            $table->string("Referente", 150)->nullable();

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

            // Chiave esterna automazione
            $table->bigInteger("codAutomazione");
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
}
