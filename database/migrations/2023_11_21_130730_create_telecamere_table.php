<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelecamereTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Telecamere', function (Blueprint $table) {
            $table->id();

            // Chiave esterna dispositivo
            $table->bigInteger("codDispositivo");

            //Indirizzo IP della telecamera
            $table->string("Indirizzo", 50)->nullable();

            //Token per lo streaming
            $table->string("Streaming", 100)->nullable();

            //Km
            $table->string("Km", 10)->default("0+000");

            // stato della telecamera (accesa o spenta)
            $table->boolean('Stato')->default(0);

            // data aggiornamento cambio stato (accesa o spenta) telecamera
            $table->timestamp("DataUpdate")->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Telecamere');
    }
}
