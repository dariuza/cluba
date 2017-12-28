<?php

use Illuminate\Database\Seeder;

class CluStateServiceTableSeeder extends Illuminate\Database\Seeder {
	
	public function run(){
		\DB::table('clu_state_service')->insert(array(
			'state'=>'Asignada',
			'alert'=>'#e6fff7',
			'order'=>'2',
			'description'=>'La Suscripción se ha terminado de pagar'				
			)
		);
		\DB::table('clu_state_service')->insert(array(
			'state'=>'Confirmada',						
			'alert'=>'#fff5cc',	
			'order'=>'1',
			'description'=>'El pago esta pendiente por pagar'
			)
		);
		\DB::table('clu_state_service')->insert(array(
			'state'=>'Cancelada',						
			'alert'=>'#f2dede',	
			'order'=>'3',
			'description'=>'El pago esta retrasado'
			)
		);
		\DB::table('clu_state_service')->insert(array(
			'state'=>'Realizada',						
			'alert'=>'#d9edf7',	
			'order'=>'4',
			'description'=>'La Suscripción se halla vencida'
			)
		);
		
	}
}
