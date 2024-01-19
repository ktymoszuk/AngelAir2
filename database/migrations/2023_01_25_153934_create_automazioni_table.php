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
        Schema::connection('mysql')->create('Automazioni', function (Blueprint $table) {
            
            $table->id();
            
            //Nome dell' automazione
            $table->string('Nome', 50)->nullable();

            //Posizione dell'automazione
            $table->decimal('Latitudine', 10, 7)->default(0.0000000);
            $table->decimal('Longitudine', 10, 7)->default(0.0000000);

            //Posizione kilometrica stradale
            $table->string('Km', 10)->default('0+000');

            //Sinottico sistema
            $table->string('Cartografia', 100)->nullable();

            //Stato dell'automazione attiva o disattiva
            $table->boolean('Stato')->default(1);

            //Keep alive automazione
            $table->timestamp('DataUpdate')->useCurrent();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Automazioni');
    }
};
