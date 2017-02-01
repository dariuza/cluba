<?php

use Illuminate\Database\Seeder;

class ModuleTableSeeder extends Illuminate\Database\Seeder {
	
	public function run(){
		\DB::table('seg_module')->insert(array(
			'module'=>'Aplicaciones',
			'preference'=>'{"js":"seg_aplicacion","categoria":"Componentes","controlador":"/aplicacion/","uiicono":"ui-jqueri","icono":"fa fa-th-large fa-fw"}',
			'description'=>'Este modulo contine toda la información de las aplicaciones de la pieza de software',
			'active'=>1,
			'app_id'=>1
			)
		);
		\DB::table('seg_module')->insert(array(
			'module'=>'Modulos',
			'preference'=>'{"js":"seg_modulo","categoria":"Componentes","controlador":"/modulo/","uiicono":"ui-jqueri","icono":"fa fa-th fa-fw"}',
			'description'=>'Este modulo contine toda la información de los modulos de la aplicación',
			'active'=>1,
			'app_id'=>1
			)
		);
		\DB::table('seg_module')->insert(array(
			'module'=>'Opciones',
			'preference'=>'{"js":"seg_opcion","categoria":"Componentes","controlador":"/opcion/","uiicono":"ui-jqueri","icono":"fa fa-tags fa-fw"}',
			'description'=>'Este modulo contine toda la información de las opciones de los modulos de la aplicación',
			'active'=>1,
			'app_id'=>1
			)
		);
		\DB::table('seg_module')->insert(array(
			'module'=>'Permisos',
			'preference'=>'{"js":"seg_permiso","categoria":"Acceso","controlador":"/permiso/","uiicono":"ui-jqueri","icono":"fa fa-retweet fa-fw"}',
			'description'=>'Este modulo contine toda la información de los permisos de los usuarios en la aplicación',
			'active'=>1,
			'app_id'=>1
			)
		);
		\DB::table('seg_module')->insert(array(
			'module'=>'Roles',
			'preference'=>'{"js":"seg_rol","categoria":"Componentes","controlador":"/rol/","uiicono":"ui-jqueri","icono":"fa fa-file-o fa-fw"}',
			'description'=>'Este modulo contine toda la información de los roles que pueden tomar los usuarios de la aplicación',
			'active'=>1,
			'app_id'=>1
			)
		);
		\DB::table('seg_module')->insert(array(
			'module'=>'Usuarios',
			'preference'=>'{"js":"seg_usuario","categoria":"Agentes","controlador":"/usuario/","uiicono":"ui-jqueri","icono":"fa fa-users fa-fw"}',
			'description'=>'Este modulo contine toda la información de los usuarios de la aplicación',
			'active'=>1,
			'app_id'=>1
			)
		);
		\DB::table('seg_module')->insert(array(
			'module'=>'Suscripciones',
			'preference'=>'{"js":"clu_suscripcion","categoria":"Componentes","controlador":"/suscripcion/","uiicono":"ui-jqueri","icono":"fa fa-file-text fa-fw"}',
			'description'=>'Este modulo gestiona las subscripciones del club de amigos',
			'active'=>1,
			'app_id'=>2
			)
		);
		\DB::table('seg_module')->insert(array(
			'module'=>'Beneficiarios',
			'preference'=>'{"js":"clu_beneficiario","categoria":"Componentes","controlador":"/beneficiario/","uiicono":"ui-jqueri","icono":"fa fa-share-alt fa-fw"}',
			'description'=>'Este modulo gestiona los beneficiarios de los amigos',
			'active'=>1,
			'app_id'=>2
			)
		);
		\DB::table('seg_module')->insert(array(
			'module'=>'Convenios',
			'preference'=>'{"js":"clu_entidad","categoria":"Componentes","controlador":"/entidad/","uiicono":"ui-jqueri","icono":"fa fa-institution fa-fw"}',
			'description'=>'Este modulo gestiona las entidades que ofrecen sus servicios',
			'active'=>1,
			'app_id'=>2
			)
		);		
		\DB::table('seg_module')->insert(array(
			'module'=>'Especialidades',
			'preference'=>'{"js":"clu_especialidad","categoria":"Componentes","controlador":"/especialidad/","uiicono":"ui-jqueri","icono":"fa fa-tags fa-fw"}',
			'description'=>'Este modulo gestiona las especialidades de los especialistas',
			'active'=>1,
			'app_id'=>2
			)
		);
		\DB::table('seg_module')->insert(array(
			'module'=>'Especialistas',
			'preference'=>'{"js":"clu_especialista","categoria":"Componentes","controlador":"/especialista/","uiicono":"ui-jqueri","icono":"fa fa-tags fa-fw"}',
			'description'=>'Este modulo gestiona los especialistas de las entidades',
			'active'=>1,
			'app_id'=>2
			)
		);
		\DB::table('seg_module')->insert(array(
			'module'=>'Servicios',
			'preference'=>'{"js":"clu_servicio","categoria":"Componentes","controlador":"/servicio/","uiicono":"ui-jqueri","icono":"fa fa-file-text fa-fw"}',
			'description'=>'Este modulo gestiona la relacion entre cupones amigos y dependientes',
			'active'=>1,
			'app_id'=>2
			)
		);
		\DB::table('seg_module')->insert(array(
			'module'=>'Reportes',
			'preference'=>'{"js":"clu_reporte","categoria":"Componentes","controlador":"/reporte/","uiicono":"ui-jqueri","icono":"fa fa-pie-chart fa-fw"}',
			'description'=>'Este modulo gestiona los reportes de la aplicación',
			'active'=>1,
			'app_id'=>2
			)
		);
		\DB::table('seg_module')->insert(array(
			'module'=>'Boletines',
			'preference'=>'{"js":"clu_boletin","categoria":"Componentes","controlador":"/boletin/","uiicono":"ui-jqueri","icono":"fa fa-list-alt fa-fw"}',
			'description'=>'Este modulo gestiona los boletines de la revista',
			'active'=>1,
			'app_id'=>2
			)
		);
		\DB::table('seg_module')->insert(array(
			'module'=>'Notificaciones',
			'preference'=>'{"js":"clu_notificacion","categoria":"Componentes","controlador":"/notificacion/","uiicono":"ui-jqueri","icono":"fa fa-envelope-o fa-fw"}',
			'description'=>'Este modulo gestiona las notificaciones de la aplicación',
			'active'=>1,
			'app_id'=>2
			)
		);
				
	}
}
