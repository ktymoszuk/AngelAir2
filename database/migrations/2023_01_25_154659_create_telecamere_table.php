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
        Schema::create('Telecamere', function (Blueprint $table) {

            $table->id();
            
            //Nome scenario
            $table->string('Nome', 100)->nullable();

            //Chiave esterna dispositivo
            $table->integer('codDispositivo')->nullable();

            //Indirizzo Ip della videocamera
            $table->string('Indirizzo', 50)->nullable();
            
            //Url flusso video
            $table->string('Streaming', 100)->nullable();
            
            //Posizione kilometrica della rete stradale
            $table->string('Km', 10)->nullable();
            
            //Stato della telecamera (accesa o spenta)
            $table->boolean('Stato')->default(0);
            
            //Data aggiornamento cambio stato telecamera 
            $table->timestamp('DataUpdate')->useCurrent()->useCurrentOnUpdate();
            
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
};
