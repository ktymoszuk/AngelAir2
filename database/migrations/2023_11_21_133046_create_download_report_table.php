<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDownloadReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DownloadReport', function (Blueprint $table) {
            $table->id();
            
            // nome file
            $table->string("Nome", 50);

            // chiave esterna report
            $table->bigInteger("codReport");

            //indica se il report è stato scaricato
            $table->boolean("isDownload")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('DownloadReport');
    }
}
