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
        Schema::connection('mysql')->create('CategorieDispositivi', function (Blueprint $table) {
           
            $table->id();

            //Nome categoria dispositivo
            $table->string('Nome', 50)->nullable();

            //Icona identificativa della categoria
            $table->string('Logo', 50)->nullable();

            //Icon marker mappa
            $table->string('PinMappa', 150)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('CategorieDispositivi');
    }
};
