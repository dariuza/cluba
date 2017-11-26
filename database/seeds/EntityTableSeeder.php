<?php

use Illuminate\Database\Seeder;

class EntityTableSeeder extends Illuminate\Database\Seeder {
	
	public function run(){
		\DB::table('clu_entity')->insert(array(
			'business_name'=>'Odonto Center',
			'nit'=>'8456789-2',
			'legal_representative'=>'Andres Calle',
			'contact_representative'=>'David Sepulveda',
			'phone1_contact'=>'31133009897',
			'phone2_contact'=>'4200010',
			'email_contact'=>'sepulvedadavid@odontocenter.com',
			'description'=>'Organización dedicada a la labor dental',				
			)
		);
		\DB::table('clu_entity')->insert(array(
			'business_name'=>'Open GIM',
			'nit'=>'1234789-2',
			'legal_representative'=>'Sandra Rua',
			'contact_representative'=>'Ronald Restrepo',
			'phone1_contact'=>'34422331232',
			'phone2_contact'=>'3217099',
			'email_contact'=>'restreporonald@opengim.com',
			'description'=>'Organización dedicada al cuidado corporal',				
			)
		);
		\DB::table('clu_entity')->insert(array(
			'business_name'=>'carieton',
			'nit'=>'34235609-2',
			'legal_representative'=>'Andres Calle',
			'contact_representative'=>'David Sepulveda',
			'phone1_contact'=>'31133009897',
			'phone2_contact'=>'4200010',
			'email_contact'=>'sepulvedadavid@odontocenter.com',
			'description'=>'Organización dedicada a la labor dental',				
			)
		);
						
	}
}
