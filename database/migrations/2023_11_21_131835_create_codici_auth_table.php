<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodiciAuthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CodiciAuth', function (Blueprint $table) {
            $table->id();

            // codice numerico random per verifica accesso
            $table->char("Codice", 6);

            // chiave esterna utente
            $table->bigInteger("codUtente");

            // Dataora creazione del token
            $table->timestamp("DataCreazione")->useCurrent();

            // Dataora scadenza del token
            $table->timestamp("DataScadenza")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('CodiciAuth');
    }
}
