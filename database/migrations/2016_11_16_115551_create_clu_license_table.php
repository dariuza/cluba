<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCluLicenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('clu_license', function(Blueprint $table){
    		$table->increments('id');
    		$table->string('type');//por suscripcion o por pago adicional
    		$table->integer('price');
    		$table->date('date');
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
    	Schema::drop('clu_license');
    }
}
