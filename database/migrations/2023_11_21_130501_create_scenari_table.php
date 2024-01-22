<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScenariTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Scenari', function (Blueprint $table) {
            $table->id();
            
            // id nel sistema
            $table->char('AxId', 30)->nullable();

            //AxAppTag automazione
            $table->string('AxAppTag', 8)->nullable();

            //AxSystemTag automazione
            $table->string('AxSystemTag', 15)->nullable();

            //Nome dell'automazione
            $table->string('Nome', 50)->nullable();

            //Stato dello scenario
            $table->tinyInteger("Stato")->default(0);

            // Chiave esterna automazione
            $table->bigInteger("codAutomazione");

            // Chiave esterna regola automazione
            $table->bigInteger("codRegolaAutoApertura");

            // Chiave esterna regola automazione
            $table->bigInteger("codRegolaAutoChiusura");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Scenari');
    }
}
