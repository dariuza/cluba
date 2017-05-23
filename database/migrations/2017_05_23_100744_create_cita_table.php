<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clu_cita', function(Blueprint $table){
            $table->increments('id');
            $table->dateTime('date_crt');//creacion
            $table->dateTime('date_evt');//fecha de cita
            $table->dateTime('date_end');//duracion de cita, en horas
            $table->string('description');                        
            $table->integer('client_id')->unsigned(); 
            $table->string('tipe');//si es suscriptor o beneficiario 
            $table->boolean('active')->default(true);
            $table->integer('suscription_id')->unsigned();         
            $table->foreign('suscription_id')->references('id')->on('clu_suscription')->onDelete('cascade');
            $table->integer('specialist_id')->unsigned();         
            $table->foreign('specialist_id')->references('id')->on('clu_specialist')->onDelete('cascade');
            $table->integer('specialty_id')->unsigned();         
            $table->foreign('specialty_id')->references('id')->on('clu_specialty')->onDelete('cascade');
            $table->integer('subentity_id')->unsigned(); //contiene el municipio
                 
            $table->foreign('subentity_id')->references('id')->on('clu_subentity')->onDelete('cascade');
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
          Schema::drop('clu_cita');
    }
}
