<?php

use Illuminate\Database\Seeder;

class RolTableSeeder extends Illuminate\Database\Seeder {
	
	public function run(){
		\DB::table('seg_rol')->insert(array(
			'rol'=>'Super Administrador',
			'description'=>'Posee acceso a todas las aplicaciones y sus opciones'				
			)
		);
		\DB::table('seg_rol')->insert(array(
			'rol'=>'Administrador',
			'description'=>'Administra los recursos de Club de Amigos'
			)
		);
		\DB::table('seg_rol')->insert(array(
			'rol'=>'Suscriptor',
			'description'=>'Puede realizar consultas a la base de datos'
			)
		);
		\DB::table('seg_rol')->insert(array(
			'rol'=>'Asesor',
			'description'=>'Agente de que realiza el primer contacto con un Amigo'
			)
		);
		\DB::table('seg_rol')->insert(array(
			'rol'=>'Jefe de Area',
			'description'=>'Recolecta las solicitudes de suscripcion'
			)
		);
		\DB::table('seg_rol')->insert(array(
			'rol'=>'Asesor Financiero',
			'description'=>'Recolecta las solicitudes de suscripcion'
			)
		);
		\DB::table('seg_rol')->insert(array(
			'rol'=>'Convenio',
			'description'=>'Recolecta las solicitudes de suscripcion'
			)
		);
		\DB::table('seg_rol')->insert(array(
			'rol'=>'Dependiente',
			'description'=>'Recolecta las solicitudes de suscripcion'
			)
		);
	}
}
