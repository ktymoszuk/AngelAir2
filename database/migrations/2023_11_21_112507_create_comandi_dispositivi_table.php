<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComandiDispositiviTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ComandiDispositivi', function (Blueprint $table) {
            $table->id();

            //AxId automazione
            $table->char('AxId', 30)->nullable();

            //AxAppTag automazione
            $table->string('AxAppTag', 8)->nullable();

            //AxSystemTag automazione
            $table->string('AxSystemTag', 15)->nullable();

            //Chiave esterna comando 
            $table->bigInteger('codComando')->nullable();

            //Chiave esterna dispositivo
            $table->bigInteger('codDispositivo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ComandiDispositivi');
    }
}
