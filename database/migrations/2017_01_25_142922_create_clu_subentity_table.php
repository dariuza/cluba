<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCluSubentityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clu_subentity', function(Blueprint $table)
        {
            $table->increments('id');           
            $table->string('sucursal_name');            
            $table->string('adress');            
            $table->string('phone1_contact');
            $table->string('phone2_contact');
            $table->string('email_contact');
            $table->string('description');
            $table->string('city');
            $table->string('neighborhood');
            $table->boolean('active')->default(true);
            $table->integer('entity_id')->unsigned();         
            $table->foreign('entity_id')->references('id')->on('clu_entity')->onDelete('cascade');
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
        
        Schema::drop('clu_subentity');
       
    }
}
