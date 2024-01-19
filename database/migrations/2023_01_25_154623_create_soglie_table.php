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
        Schema::connection('mysql')->create('Soglie', function (Blueprint $table) {
           
            $table->id();

            //Nome della soglia
            $table->string('Nome', 50)->nullable();
            
            //Tipologia dato della soglia
            $table->string('TipoDatoSoglia', 50)->nullable();
            
            //Tipo di soglia
            $table->string('TipoSoglia', 50)->nullable();
            
            //Operatore logico soglia valore minimo
            $table->string('OperatoreMinimo', 2)->nullable();
            
            //Valore soglia valore minima
            $table->string('ValoreMinimo', 20)->default('0');
            
            //Nome soglia valore minima 
            $table->string('AliasMinimo', 50)->default('Valore Minimo');
            
            //Colore soglia minimo per visualizzare su grafico
            $table->string('ColoreMinimo', 10)->default('#ffff00');
            
            //Operatore logico soglia valroe massimo
            $table->string('OperatoreMassimo', 2)->nullable();
            
            //Valore soglia massimo per visualizzare su grafico
            $table->string('ValoreMassimo', 20)->nullable();
            
            //Nome soglia valore massimo 
            $table->string('AliasMassimo', 50)->default('Valore Massimo');
            
            //Colore soglia massimo per visualizzare su grafico
            $table->string('ColoreMassimo', 10)->default('#ff0000');
            
            //Nome colonna associata di tipodisp a soglia
            $table->string('ColonnaAssociata', 50)->nullable();
            
            //Valore colonna tipodisp associata a soglia
            $table->string('ValoreAssociato', 50)->nullable();
            
            //Descrizione soglia
            $table->text('Descrizione')->nullable();
            
            //Indica se la soglia può essere eliminata dal sistema
            $table->boolean('isDefault')->default(0);
            
            //Chiave esterna automazione
            $table->integer('codAutomazione')->nullable();
            
            //Chiave esterna categoria soglia
            $table->integer('codCategoriaSoglia')->nullable();
            
            //Chiave esterna categoria dispositivo
            $table->integer('codCategoriaDisp')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Soglie');
    }
};
