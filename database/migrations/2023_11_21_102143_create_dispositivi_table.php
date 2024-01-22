<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispositiviTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Dispositivi', function (Blueprint $table) {
            $table->id();

            // id nel sistema
            $table->char("AxId", 30)->nullable(); // In produziona togliere il nullable

            // id applicazione nel sistema
            $table->string("AxAppTag", 8)->nullable(); // In produziona togliere il nullable

            // id soluzione IoT nel sistema
            $table->string("AxSystemTag", 15)->nullable(); // In produziona togliere il nullable
            
            //Nome del dispositivo
            $table->string('Nome', 50)->nullable();
            
            //DevEui del dispositvo LoRaWAN
            $table->string('DevEui', 16)->unique();

            //Descrizione del sispositivo
            $table->text('Descrizione')->nullable();

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

            // Stato di allarme del dispositivo
            $table->tinyInteger('isAllarme')->default(0);

            //Dispositivo attivo nel sistema
            $table->boolean('isAbilitato')->default(1);
            
            //Dispositivo sincronizzato
            $table->boolean('isSincronizzato')->default(0);        

            //Numero identificativo dispositivo
            $table->string('SerialNumber', 20)->nullable();

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

            // Chiave esterna tipo disp
            $table->bigInteger("codTipoDisp");

            // Chiave esterna struttura
            $table->bigInteger("codStruttura");

            //Gestione dal db applicativo
            $table->string('AppTag', 10)->nullable();

            // Codice associazione tra più dispositivi
            $table->string("CodiceStazione", 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Dispositivi');
    }
}
