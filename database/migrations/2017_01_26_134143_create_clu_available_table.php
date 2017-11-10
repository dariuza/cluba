<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCluAvailableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('clu_available', function(Blueprint $table){
    		$table->increments('id');
    		$table->string('day');
    		$table->string('hour_start');
		    $table->string('hour_end');
		    $table->string('observations');
            $table->integer('subentity_id')->unsigned();
            $table->foreign('subentity_id')->references('id')->on('clu_subentity')->onDelete('cascade');
		    $table->integer('specialist_id')->unsigned();
    		$table->foreign('specialist_id')->references('id')->on('clu_specialist')->onDelete('cascade');
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
    	Schema::drop('clu_available');
    }
}
