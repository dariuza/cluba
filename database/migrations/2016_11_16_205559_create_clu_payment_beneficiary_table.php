<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCluPaymentBeneficiaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('clu_payment_beneficiary', function(Blueprint $table){
    		$table->increments('id');
    		$table->dateTime('date_payment');
    		$table->integer('payment');
    		$table->integer('beneficiary_id')->unsigned();
    		$table->foreign('beneficiary_id')->references('id')->on('clu_beneficiary')->onDelete('cascade');
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
    	Schema::drop('clu_payment_beneficiary');
    }
}
