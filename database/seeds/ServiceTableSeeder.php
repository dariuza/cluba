<?php

use Illuminate\Database\Seeder;

class ServiceTableSeeder extends Illuminate\Database\Seeder {
	
	public function run(){
		\DB::table('clu_service')->insert(array(
			'city'=>'BELLO',
			'price'=>150000,
			'identification_user'=>'1039546956',
			'names_user'=>'FABIOLO',
			'surnames_user'=>'RUA',
			'day'=>'VIERNES',
			'date_service'=>'2017-11-29 14:30',			
			'date_service_time'=>'2017-11-29 14:30',			
			'hour_start'=>'14:30',			
			'duration'=>'1:30',			
			'especialty_id'=>2,			
			'especialist_id'=>1,
			'subentity_id'=>2,
			'suscription_id'=>1,
			)
		);						
	}
}
