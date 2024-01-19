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
        Schema::connection('mysql2')->create('Ana4Liv', function (Blueprint $table) {

            $table->id('id_4liv');
            
            //Chiave esterna ana3liv
            $table->unsignedInteger('id_3liv')->nullable();

            //Chiave esterna ana2liv
            $table->unsignedInteger('id_2liv')->nullable();
            
            //Chiave esterna ana1liv
            $table->unsignedInteger('id_1liv')->nullable();

            //Categoria azienda
            $table->string('RagioneSociale', 100)->nullable();
          
            //Logo dell'azienda
            $table->string('LogoAziendale', 150)->nullable();
            
            //Indirizzo del network server
            $table->string('NSAddress', 150)->nullable();
            
            //Identificatvio applicazione network server 
            $table->string('AppID', 16)->nullable();
 
            //Coordinate dell'azienda
            $table->decimal('Latitudine', 12, 7)->nullable();
            $table->decimal('Longitudine', 12, 7)->nullable();
            
            //Azienda attiva nel sistema
            $table->tinyInteger('Attivo')->default(1);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Ana4Liv');
    }
};
