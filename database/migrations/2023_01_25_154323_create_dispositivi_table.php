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
        Schema::connection('mysql')->create('Dispositivi', function (Blueprint $table) {

            $table->id();

            //Nome del dispositivo
            $table->string('Nome', 50)->nullable();

            //Identificativo univoco del dispositivo LoRaWAN 
            $table->string('Deveui', 16)->nullable();

            //Localizzazione del dispositivo
            $table->decimal('Latitudine', 10, 7)->default(0.0000000);
            $table->decimal('Longitudine', 10, 7)->default(0.0000000);

            //Posizione kilometrica stradale
            $table->string('Km', 10)->default('0+000');

            //Indica se il dispositivo è in stato di allarme
            $table->boolean('isAllarme')->default(0);

            //Indica se il dispositivo è abilitato all' automazione di sistema
            $table->boolean('isAbilitato')->default(1);

            //Chiave esterna categoria dispositivo
            $table->integer('codCategoriaDisp')->nullable();

            //Chiave esterna struttura
            $table->integer('codStruttura')->nullable();

            //Identificativo nel sistema per l'integrazione con altri modui axatel
            $table->char('AppTag', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Dispositivi');
    }
};
