<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificheTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Notifiche', function (Blueprint $table) {
            $table->id();

            //Chiave esterna soglia
            $table->bigInteger("codSoglia");

            //Chiave esterna utente
            $table->bigInteger("codUtente");

            //Indica se il ricevimento della notifica è attivo o no
            $table->boolean("isNotifica")->default(1);
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
}
