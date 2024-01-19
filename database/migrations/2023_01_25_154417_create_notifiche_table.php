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
        Schema::connection('mysql')->create('Notifiche', function (Blueprint $table) {

            $table->id();
            
            //Chiave esterna soglia
            $table->integer('codSoglia')->nullable();

            //Chiave esterna utente
            $table->integer('codUtente')->nullable();

            //Verifica la possibilità di ricevere notifiche di allarma nell' app
            $table->boolean('isNotifica')->default(1);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Notifiche');
    }
};
