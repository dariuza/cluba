<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCliSuscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('clu_suscription', function(Blueprint $table)
    	{
    		$table->increments('id');
    		$table->string('code');
    		$table->dateTime('date_suscription');
    		$table->dateTime('date_expiration');
    		$table->integer('price');
			$table->string('waytopay')->nullable();
    		$table->date('pay_interval'); 
    		$table->integer('fee');
    		$table->string('reason')->nullable();
    		$table->string('observation')->nullable();
            $table->string('pat')->nullable();
    		$table->integer('adviser_id')->unsigned();
    		$table->integer('friend_id')->unsigned();
    		$table->integer('state_id')->unsigned();
    		$table->foreign('adviser_id')->references('id')->on('seg_user')->onDelete('cascade');
    		$table->foreign('friend_id')->references('id')->on('seg_user')->onDelete('cascade');
    		$table->foreign('state_id')->references('id')->on('clu_state')->onDelete('cascade');
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
    	Schema::drop('clu_suscription');
    }
}
