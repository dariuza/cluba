<?php

use Illuminate\Database\Seeder;

class CluLicenseTableSeeder extends Illuminate\Database\Seeder {
	
	public function run(){
		\DB::table('clu_license')->insert(array(
			'type'=>'suscription',
			'price'=>0,
			'date'=>'2016/10/25',
			'suscription_id'=>1				
			)
		);
		\DB::table('clu_license')->insert(array(
			'type'=>'suscription',
			'price'=>0,
			'date'=>'2016/8/26',
			'suscription_id'=>2				
			)
		);
		\DB::table('clu_license')->insert(array(
			'type'=>'suscription',
			'price'=>0,
			'date'=>'2015/11/25',
			'suscription_id'=>3				
			)
		);
		
	}
}
