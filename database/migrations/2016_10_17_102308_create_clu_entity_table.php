<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCluEntityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('clu_entity', function(Blueprint $table)
    	{
    		$table->increments('id');    		
    		$table->string('business_name')->unique();
    		$table->integer('nit')->unique();
    		$table->string('legal_representative');
    		$table->string('contact_representative');
    		$table->string('phone1_contact');
    		$table->string('phone2_contact');
    		$table->string('email_contact');
    		$table->string('description');
    		$table->boolean('active')->default(true);
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
    	Schema::drop('clu_entity');
    }
}
