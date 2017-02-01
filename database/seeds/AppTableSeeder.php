<?php

use Illuminate\Database\Seeder;

class AppTableSeeder extends Illuminate\Database\Seeder {
	
	public function run(){
		\DB::table('seg_app')->insert(array(
				'app'=>'Seguridad',
				'description'=>'Contiene todos los modulos de seguridad',
				'preferences'=>'{"icono":"glyphicon glyphicon-lock","js":"seguridad"}',
				'active'=>1
				)
		);
		\DB::table('seg_app')->insert(array(
				'app'=>'ClubAmigos',
				'description'=>'Contiene los modulos de la Aplicación',
				'preferences'=>'{"icono":"glyphicon glyphicon-lock","js":"clubamigos"}',
				'active'=>1
			)
		);		
		
	}
}
