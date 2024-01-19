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
        Schema::connection('mysql2')->create('AnaDisp', function (Blueprint $table) {

            $table->id();
            
            //DevEui del dispositvo LoRaWAN
            $table->string('DevEui', 16)->nullable();

            //Nome del dispositivo
            $table->string('Nome', 150)->nullable();

            //Descrizione del sispositivo
            $table->text('Descrizione')->nullable();

            //Chiave esterna ana1liv
            $table->unsignedInteger('id_1liv')->default(1);

            //Chiave esterna ana2liv
            $table->unsignedInteger('id_2liv')->nullable();

            //Chiave esterna ana3liv
            $table->unsignedInteger('id_3liv')->nullable();
                        
            //Chiave esterna ana4liv
            $table->unsignedInteger('id_4liv')->nullable();

            //Chiave esterna tipo dispositivo
            $table->unsignedInteger('idTipologia')->nullable();
        
            //Numero identificativo dispositivo
            $table->string('SerialNumber', 64)->nullable();

            //Id applicazione nel network server
            $table->string('AppID', 16)->nullable();

            //Tipologia registrazione rete LoRaWAN
            $table->string('JoinType', 5)->nullable();

            //Stringa specifica del dispositivo
            $table->string('AppEui', 16)->nullable();

            //Chiave per dispositivi di tipo OTAA
            $table->string('AppKey', 32)->nullable();

            //Modalità importazione nel netwrok server
            $table->char('Class', 1)->nullable();

            //Parametro per dispositivi ABP
            $table->string('DevAddr', 16)->nullable();

            //Parametro per dispositivi ABP
            $table->string('NwkSessionKey', 32)->nullable();

            //Parametro per dispositivi ABP
            $table->string('AppSessionKey', 32)->nullable();

            //Dispositivo attivo nel sistema
            $table->tinyInteger('isAttivo')->default(1);

            //Dispositivo sincronizzato
            $table->tinyInteger('isSincronizzato')->default(0);
            
            //Gestione dal db applicativo
            $table->char('AppTag', 10)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('AnaDisp');
    }
};
