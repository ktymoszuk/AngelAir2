<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvioComandiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('InvioComandi', function (Blueprint $table) {
            $table->id();

            // Codice comando
            $table->string('Codice', 100)->nullable();

            // Chiave esterna dispositivo
            $table->string('DevEui', 16)->nullable();

            // Stato invio comando
            $table->boolean('isElaborato')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('InvioComandi');
    }
}
