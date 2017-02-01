<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegUserProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('seg_user_profile', function(Blueprint $table)
    	{
    		$table->increments('id');
    		$table->integer('identificacion')->unique();
			$table->string('type_id')->nullable();
    		$table->string('names', 60)->nullable();
    		$table->string('surnames', 60)->nullable();
    		$table->date('birthdate')->nullable();
			$table->string('birthplace')->nullable();			
    		$table->string('sex')->nullable();
    		$table->string('civil_status')->nullable();    		
    		$table->string('adress')->nullable();
			$table->string('home')->nullable();
			$table->string('state')->nullable();
			$table->string('city')->nullable();
			$table->string('neighborhood')->nullable();			
    		$table->string('avatar', 60)->nullable();
    		$table->string('description', 512)->nullable();
    		$table->string('template', 60)->nullable();
    		$table->bigInteger('movil_number')->default(0);
    		$table->bigInteger('fix_number')->default(0);
    		$table->date('date_start')->nullable();    		
    		$table->string('code_adviser')->nullable();
    		$table->string('zone')->nullable();
    		$table->date('date_in')->nullable();
    		$table->date('date_out')->nullable();
    		$table->integer('salary')->nullable();
			$table->string('profession')->nullable();
			$table->string('paymentadress')->nullable();
			$table->string('reference')->nullable();
			$table->string('reference_adress')->nullable();
			$table->string('reference_phone')->nullable();
    		$table->integer('location')->default(0);
    		$table->timestamps();
    		$table->integer('user_id')->unsigned();			
    		$table->foreign('user_id')->references('id')->on('seg_user')->onDelete('cascade');
			
    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('seg_user_profile');
    }
}
