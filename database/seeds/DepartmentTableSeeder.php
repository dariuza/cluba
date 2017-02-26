<?php

use Illuminate\Database\Seeder;

class DepartmentTableSeeder extends Illuminate\Database\Seeder {
	
	public function run(){
		\DB::table('clu_department')->insert(array(
			'code'=>5,
			'department'=>'ANTIOQUIA'			
			)
		);
		\DB::table('clu_department')->insert(array(
			'code'=>41,
			'department'=>'HUILA'			
			)
		);
	}
}
