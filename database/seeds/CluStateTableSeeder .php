<?php

use Illuminate\Database\Seeder;

class CluStateTableSeeder extends Illuminate\Database\Seeder {
	
	public function run(){
		\DB::table('clu_state')->insert(array(
			'state'=>'Cancelado',
			'alert'=>'#dff0d8',
			'order'=>'2',
			'description'=>'La Suscripción se ha terminado de pagar'				
			)
		);
		\DB::table('clu_state')->insert(array(
			'state'=>'Pago pendiente',						
			'alert'=>'#fff5cc',	
			'order'=>'1',
			'description'=>'El pago esta pendiente por pagar'
			)
		);
		\DB::table('clu_state')->insert(array(
			'state'=>'Pago en mora',						
			'alert'=>'#f2dede',	
			'order'=>'3',
			'description'=>'El pago esta retrasado'
			)
		);
		\DB::table('clu_state')->insert(array(
			'state'=>'Suscripcion vencida',						
			'alert'=>'#d9edf7',	
			'order'=>'4',
			'description'=>'La Suscripción se halla vencida'
			)
		);
		\DB::table('clu_state')->insert(array(
			'state'=>'Retirado',						
			'alert'=>'#f9e6ff',	
			'order'=>'5',
			'description'=>'La Suscripción de halla bloqueada'
			)
		);
		\DB::table('clu_state')->insert(array(
			'state'=>'Suscripcion Renovada',
			'alert'=>'#ccfff5',
			'order'=>'6',
			'description'=>'La Suscripción de halla renovada'
			)
		);
		\DB::table('clu_state')->insert(array(
			'state'=>'Activa',
			'alert'=>'#e6fff7',
			'order'=>'7',
			'description'=>'La Suscripción de halla activada, total abonado es mayor que 55000'
			)
		);
		\DB::table('clu_state')->insert(array(
			'state'=>'Prospecto',
			'alert'=>'#ffe6f2',
			'order'=>'8',
			'description'=>'La Suscripción no reporta ningun pago'
			)
			);
	}
}
