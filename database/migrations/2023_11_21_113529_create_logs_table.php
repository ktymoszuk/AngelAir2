<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Logs', function (Blueprint $table) {
            $table->id();

            // id nel sistema
            $table->char('AxId', 30)->nullable();

            //AxAppTag automazione
            $table->string('AxAppTag', 8)->nullable();

            //AxSystemTag automazione
            $table->string('AxSystemTag', 15)->nullable();

            //Codice identificativo log
            $table->string('Codice', 16)->nullable();

            //Chiave esterna utente
            $table->bigInteger('codUtente')->deafult(0);

            //Chiave esterna struttura
            $table->bigInteger('codStruttura')->nullable();

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
}
