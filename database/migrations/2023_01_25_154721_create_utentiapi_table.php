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
        Schema::create('UtentiApi', function (Blueprint $table) {

            $table->id();

            //Nome identificativo utenza
            $table->string('Nominativo', 100)->nullable();
            
            //Token autenticazione rest api
            $table->char('Token', 250)->nullable();

            //Indica se l'utente è abilitato ad usare servizi rest api
            $table->boolean('isAttivo')->default(0);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('UtentiApi');
    }
};
