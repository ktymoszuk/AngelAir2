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
        Schema::create('Ana2Liv', function (Blueprint $table) {

            $table->id('id_2liv');

            //Chiave esterna ana1liv
            $table->bigInteger('id_1liv')->nullable();

            //Categoria azienda
            $table->string('RagioneSociale', 50)->nullable();
          
            //Logo dell'azienda
            $table->string('LogoAziendale', 200)->nullable();
            
            //Indirizzo del network server
            $table->string('NSAddress', 100)->nullable();
            
            //Identificatvio applicazione network server 
            $table->string('AppID', 16)->nullable();
            
            //Azienda attiva nel sistema
            $table->boolean('Attivo')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Ana2Liv');
    }
};
