<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCluAvailableXSpecialtyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('clu_available_x_specialty', function(Blueprint $table){
    		$table->increments('id');
    		$table->integer('available_id')->unsigned();
    		$table->foreign('available_id')->references('id')->on('clu_available')->onDelete('cascade');
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
    	Schema::drop('clu_available_x_specialty');
    }
}
