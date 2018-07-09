<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCluStateServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clu_state_service', function(Blueprint $table){
            $table->increments('id');
            $table->string('state', 60);            
            $table->string('alert', 60);
            $table->integer('order')->unsigned();
            $table->string('description', 60)->nullable();
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
        Schema::drop('clu_state_service');
    }
}
