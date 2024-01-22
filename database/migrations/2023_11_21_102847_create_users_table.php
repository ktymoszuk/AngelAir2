<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // id applicazione nel sistema
            $table->string("AxAppTag", 8)->nullable();
            
            //Username utente
            $table->string('name', 50)->nullable();

            //Email utente
            $table->text('email')->nullable();
            $table->text('password')->nullable();

            //Path della foto profilo
            $table->string('FotoProfilo', 100)->default("axatel.png");
            $table->text('Telefono')->nullable();

            //Utente è o non è admin
            $table->tinyInteger('Ruolo')->default(0);

            //Utente è o non è abilitato all' assistenza
            $table->boolean('isAssistenza')->default(0);

            //Utente è o non è abilitato ad accedere alla piattaforma
            $table->boolean('isAbilitato')->default(0);

            //indica se la chiave software della sua utenza è attiva
            $table->boolean('isAttivo')->default(0);

            //chiave esterna ana1liv
            $table->bigInteger('id_1liv')->nullable();

            //chiave esterna ana2liv
            $table->bigInteger('id_2liv')->nullable();

            //chiave esterna ana3liv
            $table->bigInteger('id_3liv')->nullable();

            //chiave esterna ana4liv
            $table->bigInteger('id_4liv')->nullable();

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
        Schema::dropIfExists('users');
    }
}
