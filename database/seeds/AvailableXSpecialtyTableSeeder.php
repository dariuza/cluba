<?php

use Illuminate\Database\Seeder;

class AvailableXSpecialtyTableSeeder extends Illuminate\Database\Seeder {
	
	public function run(){
		\DB::table('clu_available_x_specialty')->insert(array(
			'available_id'=>1,
			'specialty_id'=>1,			
			'subentity_id'=>2,			
			)
		);
		\DB::table('clu_available_x_specialty')->insert(array(
			'available_id'=>1,
			'specialty_id'=>2,			
			'subentity_id'=>2,			
			)
		);
		\DB::table('clu_available_x_specialty')->insert(array(
			'available_id'=>2,
			'specialty_id'=>1,			
			'subentity_id'=>2,			
			)
		);
		\DB::table('clu_available_x_specialty')->insert(array(
			'available_id'=>2,
			'specialty_id'=>2,			
			'subentity_id'=>2,			
			)
		);	
		\DB::table('clu_available_x_specialty')->insert(array(
			'available_id'=>3,
			'specialty_id'=>2,			
			'subentity_id'=>4,			
			)
		);
		\DB::table('clu_available_x_specialty')->insert(array(
			'available_id'=>4,
			'specialty_id'=>3,			
			'subentity_id'=>3,			
			)
		);						
	}
}
