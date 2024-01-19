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
        Schema::connection('mysql2')->create('NomiFirebase', function (Blueprint $table) {

            $table->id();

            //Chiave esterna tipo dispositivo
            $table->string('NomeBart', 120)->nullable();

            //Chiave esterna tipo dispositivo
            $table->string('NomeFB', 120)->nullable();

            //Chiave esterna categorie dispositivi db applicativi
            $table->string('Nominativo', 150)->nullable();

            //Gestione dal db applicativo
            $table->char('AppTag', 8)->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('NomiFirebase');
    }
};
