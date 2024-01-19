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
        Schema::connection('mysql')->create('Scenari', function (Blueprint $table) {
          
            $table->id();

            //Nome scenario
            $table->string('Nome', 100)->nullable();

            //Stato dello scenario 
            $table->tinyInteger('Stato')->default(0);

            //Chaive esterna automazione
            $table->integer('codAutomazione')->nullable();

            //Chiave esterna regola automazione attivazione allarme
            $table->integer('codRegolaAutoApertura')->nullable();

            //Chiave esterna regola automazione disattivazione allarme
            $table->integer('codRegolaAutoChiusura')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Scenari');
    }
};
