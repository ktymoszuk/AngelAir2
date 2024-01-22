<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Reports', function (Blueprint $table) {
            $table->id();
            
            //nominativo descrittivo del report
            $table->string("Nome", 100);

            // numero periodo
            $table->smallInteger("Frequenza")->default(0);

            // periodo temporale
            $table->tinyInteger("Periodo")->default(0);

            //indica se il file deve essere di tipo JSON
            $table->boolean("isJSON")->default(0);

            //indica se il file deve essere di tipo CSV
            $table->boolean("isCSV")->default(0);

            //indica se il file deve essere di tipo XML
            $table->boolean("isXML")->default(0);

            //data ora creazione automazione di reportistica
            $table->timestamp("DataOra")->useCurrent();

            //chiave esterna struttura
            $table->bigInteger("codStruttura");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Reports');
    }
}
