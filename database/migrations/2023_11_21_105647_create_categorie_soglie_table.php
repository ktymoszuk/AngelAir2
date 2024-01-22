<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategorieSoglieTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CategorieSoglie', function (Blueprint $table) {
            $table->id();
            
            // Nome categoria
            $table->string("Nome", 50);

            // Colore associato alla categoria
            $table->string("Colore", 10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('CategorieSoglie');
    }
}
