<?php

use Illuminate\Database\Seeder;

class SubEntityTableSeeder extends Illuminate\Database\Seeder {
	
	public function run(){
		\DB::table('clu_subentity')->insert(array(
			'sucursal_name'=>'Sede Principal ODC ',
			'adress'=>'Cr 34 # 21 -12',			
			'phone1_contact'=>'31133009897',
			'phone2_contact'=>'4200010',
			'email_contact'=>'sepulvedadavid@odontocenter.com',
			'description'=>'Organización dedicada a la labor dental',
			'city'=>'MEDELLIN',
			'entity_id'=>1,
			)
		);
		\DB::table('clu_subentity')->insert(array(
			'sucursal_name'=>'Sede BELLO ODC ',
			'adress'=>'Cr 11 # 56 -12',			
			'phone1_contact'=>'31133009897',
			'phone2_contact'=>'4200010',
			'email_contact'=>'sepulvedadavid@odontocenter.com',
			'description'=>'Organización dedicada a la labor dental',
			'city'=>'BELLO',
			'entity_id'=>1,
			)
		);
		\DB::table('clu_subentity')->insert(array(
			'sucursal_name'=>'Sede Unica OPG',
			'adress'=>'Cr 54 # 21 - 45',			
			'phone1_contact'=>'3108256878',
			'phone2_contact'=>'2713345',
			'email_contact'=>'restreporonald@opengim.com',
			'description'=>'Organización dedicada al cuidado del cuerpo',
			'city'=>'BELLO',
			'entity_id'=>2,
			)
		);
		\DB::table('clu_subentity')->insert(array(
			'sucursal_name'=>'Carieton bello',
			'adress'=>'Cr 54 # 21 - 45',			
			'phone1_contact'=>'3108256878',
			'phone2_contact'=>'2713345',
			'email_contact'=>'restreporonald@opengim.com',
			'description'=>'Organización dedicada al cuidado del cuerpo',
			'city'=>'BELLO',
			'entity_id'=>3,
			)
		);
						
	}
}
