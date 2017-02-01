<?php

use Illuminate\Database\Seeder;

class CluSuscriptionTableSeeder extends Illuminate\Database\Seeder {
	
	public function run(){
		\DB::table('clu_suscription')->insert(array(
			'code'=>1001,
			'date_suscription'=>'2016/10/25',
			'date_expiration'=>'2017/05/25',
			'price'=>130000,
			'waytopay'=>'Efectivo',
			'pay_interval'=>'2016/11/25',
			'fee'=>4,
			'reason'=>'Ser amigo del club',
			'observation'=>'Suscripción exitosa',
			'adviser_id'=>3,
			'friend_id'=>5,
			'state_id'=>2			
			)
		);
		\DB::table('clu_suscription')->insert(array(
			'code'=>1002,
			'date_suscription'=>'2016/8/26',
			'date_expiration'=>'2017/05/25',
			'price'=>130000,
			'waytopay'=>'Efectivo',
			'pay_interval'=>'2016/9/26',
			'fee'=>12,
			'reason'=>'Ser amigo del club',
			'observation'=>'Suscripción exitosa',
			'adviser_id'=>3,
			'friend_id'=>6,
			'state_id'=>2
			)
		);
		\DB::table('clu_suscription')->insert(array(
			'code'=>1003,
			'date_suscription'=>'2015/11/25',
			'date_expiration'=>'2016/11/25',
			'price'=>130000,
			'waytopay'=>'Efectivo',
			'pay_interval'=>'2016/10/25',
			'fee'=>6,
			'reason'=>'Ser amigo del club',
			'observation'=>'Suscripción exitosa',
			'adviser_id'=>4,
			'friend_id'=>7,
			'state_id'=>2
			)
		);
						
	}
}
