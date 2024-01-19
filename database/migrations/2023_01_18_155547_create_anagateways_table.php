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
        Schema::connection('mysql2')->create('AnaGateways', function (Blueprint $table) {

            $table->id();

            //Nome gateway
            $table->string('Eui', 32)->nullable();

            //Indirizzo MacAddress
            $table->string('MacAddress', 30)->nullable();

            //Identificativo gateway
            $table->string('Nome', 120)->nullable();

            //Descrizione
            $table->text('Descrizione')->nullable();

            //Coordinate gateway
            $table->decimal('Latitudine', 12, 7)->default(0.0000000);
            $table->decimal('Longitudine', 12, 7)->default(0.0000000);
            
            //Descrizione versione gateway
            $table->string('Tipologia', 64)->nullable();
          
            //Frequenza di comunicazione
            $table->string('Frequenza', 64)->nullable();
            
            //Posizione del gateway
            $table->string('Indirizzo', 250)->nullable();

            $table->text('Note')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('AnaGateways');
    }
};
