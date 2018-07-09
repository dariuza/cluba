<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCluServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clu_service', function(Blueprint $table){
            $table->increments('id');
            
            //basicos
            $table->string('city');//municipio
            $table->integer('price');//municipio

            //usuario de la cita
            $table->string('identification_user');//usuario de la cita
            $table->string('names_user');//usuario de la cita
            $table->string('surnames_user');//usuario de la cita

            //fechas
            $table->string('day');//usuario de la cita
            $table->date('date_service');//tiene tambien la hora de inicio
            $table->dateTime('date_service_time');//tiene tambien la hora de inicio
            $table->string('hour_start');
            $table->string('duration');//viene de el especialista

            //estados
            
            $table->boolean('active')->default(true);
            $table->string('description');            

            //realciones

            $table->integer('especialty_id')->unsigned();//especialidad
            $table->integer('especialist_id')->unsigned();//especialidad
            $table->integer('subentity_id')->unsigned();//subentidad
            $table->integer('suscription_id')->unsigned();//suscripcion - titular - estado
            $table->integer('status')->unsigned()->default(1);//asignada, por confirmar, terminada, cancelada

            $table->foreign('especialty_id')->references('id')->on('clu_specialty')->onDelete('cascade');
            $table->foreign('especialist_id')->references('id')->on('clu_specialist')->onDelete('cascade');
            $table->foreign('subentity_id')->references('id')->on('clu_subentity')->onDelete('cascade');
            $table->foreign('suscription_id')->references('id')->on('clu_suscription')->onDelete('cascade');
            $table->foreign('status')->references('id')->on('clu_state_service')->onDelete('cascade');

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
        Schema::drop('clu_service');
    }
}
