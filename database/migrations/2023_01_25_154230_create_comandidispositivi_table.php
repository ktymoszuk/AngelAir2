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
        Schema::connection('mysql')->create('ComandiDispositivi', function (Blueprint $table) {
            
            $table->id();
            
            //Chiave esterna comando 
            $table->integer('codComando')->nullable();

            //Chiave esterna dispositivo
            $table->integer('codDispositivo')->nullable();

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
};
