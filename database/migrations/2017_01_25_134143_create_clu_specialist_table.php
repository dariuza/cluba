<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCluSpecialistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('clu_specialist', function(Blueprint $table){
    		$table->increments('id');
    		$table->string('name');
    		$table->string('identification');
    		$table->string('phone1')->nullable();
    		$table->string('phone2')->nullable();
    		$table->string('email')->nullable();
    		$table->string('name_assistant')->nullable();
    		$table->string('phone1_assistant')->nullable();
    		$table->string('phone2_assistant')->nullable();
    		$table->string('email_assistant')->nullable();
    		$table->string('description')->nullable();
		$table->integer('entity_id')->unsigned();    		
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
    	Schema::drop('clu_specialist');
    }
}
