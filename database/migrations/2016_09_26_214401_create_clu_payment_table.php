<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCluPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('clu_payment', function(Blueprint $table)
    	{
    		$table->increments('id');
    		$table->dateTime('date_payment');
    		$table->integer('payment');
    		$table->string('n_receipt');
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
    	Schema::drop('clu_payment');
    }
}
