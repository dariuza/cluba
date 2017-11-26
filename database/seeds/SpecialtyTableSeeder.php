<?php

use Illuminate\Database\Seeder;

class SpecialtyTableSeeder extends Illuminate\Database\Seeder {
	
	public function run(){
		\DB::table('clu_specialty')->insert(array(
			'name'=>'Endodoncia',
			'code'=>'EN1243',
			'description'=>'Procedimiento dental correctivo',
			'active'=>1,
			)
		);
		\DB::table('clu_specialty')->insert(array(
			'name'=>'ORTODONCIA',
			'code'=>'OR1243',
			'description'=>'Procedimiento dental preventivo',
			'active'=>1,
			)
		);
		\DB::table('clu_specialty')->insert(array(
			'name'=>'ACONDICIONAMIENTO FISICO',
			'code'=>'AF2232',
			'description'=>'Procedimiento CORPORAL preventivo',
			'active'=>1,
			)
		);						
	}
}
