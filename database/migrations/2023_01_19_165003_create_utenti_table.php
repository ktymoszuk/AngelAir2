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
        Schema::connection('mysql2')->create('Utenti', function (Blueprint $table) {

            $table->id();

            //Username utente
            $table->string('Username', 50)->nullable();

            //Email utente
            $table->string('Email', 50)->nullable();
            $table->string('Password', 100)->nullable();

            //Path della foto profilo
            $table->string('FotoProfilo', 100)->nullable();
            $table->string('Telefono', 10)->nullable();

            //Utente è o non è admin
            $table->tinyInteger('isAdmin')->default(0);

            //Utente è o non è abilitato all' assistenza
            $table->tinyInteger('isAssistenza')->default(0);

            //Utente è o non è abilitato ad accedere alla piattaforma
            $table->tinyInteger('isAbilitato')->default(0);

            //chiave esterna ana1liv
            $table->unsignedInteger('id_1liv')->nullable();

            //chiave esterna ana2liv
            $table->unsignedInteger('id_2liv')->nullable();

            //chiave esterna ana3liv
            $table->unsignedInteger('id_3liv')->nullable();

            //chiave esterna ana4liv
            $table->unsignedInteger('id_4liv')->nullable();

            //Token utente
            $table->string('Token', 100)->nullable();

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
