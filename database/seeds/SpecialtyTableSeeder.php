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
	}
}
