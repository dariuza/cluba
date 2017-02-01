<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCluLicensePrintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('clu_license_print', function(Blueprint $table){
    		$table->increments('id');
    		$table->dateTime('date');
    		$table->integer('price');
    		$table->string('description');
    		$table->integer('suscription_id')->unsigned();
    		$table->foreign('suscription_id')->references('id')->on('clu_suscription')->onDelete('cascade');
    		$table->timestamps();
    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::drop('clu_license_print');
    }
}
