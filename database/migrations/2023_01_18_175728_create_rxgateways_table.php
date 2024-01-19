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
        Schema::connection('mysql2')->create('RxGateways', function (Blueprint $table) {
            
            $table->id();

            //Identificativo gateway
            $table->string('GwEui', 50)->nullable();

            //RSSI in rx
            $table->integer('RSSI')->nullable();
          
            //Signal to noise ratio
            $table->decimal('SNR', 6, 2)->nullable();

            //Creazione dati del pacchetto nella tabella
            $table->timestamp('Timestamp')->useCurrent();

            //Ora del pacchetto LoRaWan
            $table->timestamp('DataOra')->useCurrent();

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
        Schema::dropIfExists('RxGateways');
    }
};
