<?php

use Illuminate\Database\Seeder;

class SpecialistTableSeeder extends Illuminate\Database\Seeder {
	
	public function run(){
		\DB::table('clu_specialist')->insert(array(
			'name'=>'Pedro Coral ',
			'identification'=>'1231223133',			
			'phone1'=>'31133009897',
			'phone2'=>'4200010',
			'email'=>'escamoso@yopmail.com',
			'name_assistant'=>'Camila',
			'phone1_assistant'=>'1231344',
			'phone2_assistant'=>'4343423',
			'email_assistant'=>'cami@yopmail.com',			
			'description'=>'Para obtener el mejor servicio necesitas el mejor especialista',
			'entity_id'=>1,
			)
		);	

		\DB::table('clu_specialist')->insert(array(
			'name'=>'Camilo Ochoa ',
			'identification'=>'1232987675',			
			'phone1'=>'231342',
			'phone2'=>'231232',
			'email'=>'cami@yopmail.com',
			'name_assistant'=>'Camila',
			'phone1_assistant'=>'221312',
			'phone2_assistant'=>'43432432',
			'email_assistant'=>'cami@yopmail.com',			
			'description'=>'Para obtener el mejor servicio necesitas el mejor especialista',
			'entity_id'=>3,
			)
		);

		\DB::table('clu_specialist')->insert(array(
			'name'=>'Eugenio',
			'identification'=>'1039845654',			
			'phone1'=>'231342',
			'phone2'=>'231232',
			'email'=>'eugenio@yopmail.com',
			'name_assistant'=>'Sandra',
			'phone1_assistant'=>'221312',
			'phone2_assistant'=>'43432432',
			'email_assistant'=>'sandra@yopmail.com',			
			'description'=>'Para obtener el mejor servicio necesitas el mejor especialista',
			'entity_id'=>3,
			)
		);		
						
	}
}
