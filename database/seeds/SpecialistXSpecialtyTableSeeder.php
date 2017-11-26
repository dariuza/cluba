<?php

use Illuminate\Database\Seeder;

class SpecialistXSpecialtyTableSeeder extends Illuminate\Database\Seeder {
	
	public function run(){
		\DB::table('clu_specialist_x_specialty')->insert(array(
			'rate_particular'=>230000,
			'rate_suscriptor'=>110000,			
			'tiempo'=>'1:30',			
			'specialist_id'=>1,
			'specialty_id'=>1,
			)
		);
		\DB::table('clu_specialist_x_specialty')->insert(array(
			'rate_particular'=>240000,
			'rate_suscriptor'=>150000,			
			'tiempo'=>'1:30',			
			'specialist_id'=>1,
			'specialty_id'=>2,
			)
		);	
		\DB::table('clu_specialist_x_specialty')->insert(array(
			'rate_particular'=>200000,
			'rate_suscriptor'=>125000,			
			'tiempo'=>'1:00',			
			'specialist_id'=>2,
			'specialty_id'=>2,
			)
		);	
		\DB::table('clu_specialist_x_specialty')->insert(array(
			'rate_particular'=>50000,
			'rate_suscriptor'=>42000,			
			'tiempo'=>'1:00',			
			'specialist_id'=>3,
			'specialty_id'=>3,
			)
		);						
	}
}
