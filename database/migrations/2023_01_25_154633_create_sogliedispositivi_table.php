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
        Schema::create('SoglieDispositivi', function (Blueprint $table) {
           
            $table->id();
            
            //Chiave esterna dispositivo
            $table->integer('codDispositivo')->nullable();

            //Chiave esterna soglia
            $table->integer('codSoglia')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('SoglieDispositivi');
    }
};
