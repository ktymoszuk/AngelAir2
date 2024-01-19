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
        Schema::connection('mysql')->create('Utenti', function (Blueprint $table) {

            $table->id();

            //Username utente
            $table->string('Username', 50)->nullable();

            //Email utente
            $table->string('Email', 50)->nullable();

            //
            $table->string('Password', 100)->nullable();

            //Path della foto profilo
            $table->string('FotoProfilo', 100)->nullable();

            //Numero di telefono
            $table->string('Telefono', 10)->nullable();

            //Utente è o non è admin
            $table->boolean('Ruolo')->default(0);

            //Utente è o non è abilitato all' assistenza
            $table->boolean('isAssistenza')->default(0);

            //Utente è o non è abilitato ad accedere alla piattaforma
            $table->boolean('isAbilitato')->default(0);

            //Token utente per sms firebase
            $table->string('Token', 250)->nullable();

            //Update dei dati dell' utente
            $table->timestamp('DataUpdate')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Utenti');
    }
};
