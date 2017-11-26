<?php

use Illuminate\Database\Seeder;

class BeneficiaryTableSeeder extends Illuminate\Database\Seeder {
	
	public function run(){
		\DB::table('clu_beneficiary')->insert(array(
			'type_id'=>'CEDULA CIUDADANIA',
			'identification'=>'1039546956',
			'names'=>'FABIOLO',
			'surnames'=>'RUA',
			'relationship'=>'PRIMO',
			'movil_number'=>'13112233243',			
			'state'=>'Pago por suscripción',			
			'alert'=>'#dff0d8',			
			'price'=>0,			
			'civil_status'=>'SOLTERO',			
			'license_id'=>1,
			)
		);						
	}
}
