<?php

use Illuminate\Database\Seeder;

class AvailableTableSeeder extends Illuminate\Database\Seeder {
	
	public function run(){
		\DB::table('clu_available')->insert(array(
			'day'=>'MIERCOLES',
			'hour_start'=>'13:00',			
			'hour_end'=>'17:30',			
			'observations'=>'',			
			'subentity_id'=>2,			
			'specialist_id'=>1,			
			)
		);
		\DB::table('clu_available')->insert(array(
			'day'=>'VIERNES',
			'hour_start'=>'14:15',			
			'hour_end'=>'19:15',			
			'observations'=>'',			
			'subentity_id'=>2,			
			'specialist_id'=>1,			
			)
		);
		\DB::table('clu_available')->insert(array(
			'day'=>'LUNES',
			'hour_start'=>'14:00',			
			'hour_end'=>'19:00',			
			'observations'=>'',			
			'subentity_id'=>4,			
			'specialist_id'=>2,			
			)
		);

		\DB::table('clu_available')->insert(array(
			'day'=>'MARTES',
			'hour_start'=>'8:00',			
			'hour_end'=>'12:00',			
			'observations'=>'',			
			'subentity_id'=>3,			
			'specialist_id'=>3,			
			)
		);
							
	}
}
