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
        Schema::connection('mysql2')->create('RawData', function (Blueprint $table) {

            $table->id();

            //DevEui dispositivo
            $table->string('Deveui', 16)->nullable();

            //Numero Frame ricevuto dal dispositivo
            $table->unsignedInteger('Frame')->nullable();
            
            //Porta per il ricevimento dati
            $table->unsignedInteger('Porta')->nullable();

            //Frequenza di comunicazione
            $table->unsignedInteger('Frequenza')->nullable();
           
            //Datarate del dispositivo
            $table->string('DataRate', 18)->nullable();

            //Dispositivo attivo o non attivo
            $table->tinyInteger('ACK')->nullable();

            //RSSI in rx
            $table->integer('RSSI')->nullable();

            //Signal to noise ratio
            $table->decimal('SNR', 6, 2)->nullable();

            //Creazione dati del pacchetto nella tabella
            $table->timestamp('TimeStamp')->useCurrent();

            //Dataora del pacchetto
            $table->timestamp('DataOra')->useCurrent();

            //Pacchetto elaborato
            $table->string('Payload', 250)->nullable();

            //Pacchetto elaborato o non elaborato
            $table->tinyInteger('Elaborato')->default(0);

            //Identificatore azienda
            $table->unsignedInteger('id_1liv')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('RawData');
    }
};
