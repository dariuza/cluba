<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCluSpecialistXSpecialtyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('clu_specialist_x_specialty', function(Blueprint $table){
    		$table->increments('id');
    		$table->integer('rate_particular');
    		$table->integer('rate_suscriptor');
    		$table->string('tiempo');
		    $table->integer('active');	
    		$table->integer('specialist_id')->unsigned();
    		$table->foreign('specialist_id')->references('id')->on('clu_specialist')->onDelete('cascade');
		    $table->integer('specialty_id')->unsigned();
    		$table->foreign('specialty_id')->references('id')->on('clu_specialty')->onDelete('cascade');
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
    	Schema::drop('clu_specialist_x_specialty');
    }
}
