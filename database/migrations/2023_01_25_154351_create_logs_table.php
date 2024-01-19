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
        Schema::connection('mysql')->create('Logs', function (Blueprint $table) {
           
            $table->id();
            
            //Codice identificativo log
            $table->string('Codice', 16)->nullable();

            //Chiave esterna utente
            $table->integer('codUtente')->deafult(0);

            //Chiave esterna struttura
            $table->integer('codStruttura')->nullable();

            //Messaggio descrittivo log
            $table->text('Messaggio')->nullable();

            //DataOra inserimento log
            $table->timestamp('DataOra')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Logs');
    }
};
