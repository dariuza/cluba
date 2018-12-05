<?php

use Illuminate\Database\Seeder;

class OptionTableSeeder extends Illuminate\Database\Seeder {
	
	public function run(){
		\DB::table('seg_option')->insert(array(
				'option'=>'Listar',
				'action'=>'enumerar',
				'preference'=>'{"lugar":"escritorio","vista":"none","icono":"fa fa-th-list"}',
				'active'=>1
				)
		);
		\DB::table('seg_option')->insert(array(
				'option'=>'Listar',
				'action'=>'enumerar',
				'preference'=>'{"lugar":"papelera","vista":"none","icono":"fa fa-th-list"}',
				'active'=>1
				)
		);
		\DB::table('seg_option')->insert(array(
				'option'=>'Ver',
				'action'=>'mirar',
				'preference'=>'{"lugar":"escritorio","vista":"listar","icono":"fa fa-eye"}',
				'active'=>1
				)
		);
		\DB::table('seg_option')->insert(array(
				'option'=>'Agregar',
				'action'=>'crear',
				'preference'=>'{"lugar":"escritorio","vista":"listar","icono":"fa fa-plus"}',
				'active'=>1
				)
		);
		\DB::table('seg_option')->insert(array(
				'option'=>'Agregar',
				'action'=>'crear',
				'preference'=>'{"lugar":"escritorio","vista":"none","icono":"fa fa-plus"}',
				'active'=>1
				)
		);
		\DB::table('seg_option')->insert(array(
				'option'=>'Editar',
				'action'=>'actualizar',
				'preference'=>'{"lugar":"escritorio","vista":"listar","icono":"fa fa-gears"}',
				'active'=>1
			)
		);	
		\DB::table('seg_option')->insert(array(
				'option'=>'Reciclar',
				'action'=>'botar',
				'preference'=>'{"lugar":"escritorio","vista":"listar","icono":"fa fa-trash-o"}',
				'active'=>1
			)
		);
		\DB::table('seg_option')->insert(array(
				'option'=>'Restaurar',
				'action'=>'recuperar',
				'preference'=>'{"lugar":"papelera","vista":"listar","icono":"fa fa-long-arrow-up"}',
				'active'=>1
			)
		);
		\DB::table('seg_option')->insert(array(
				'option'=>'Borrar',
				'action'=>'eliminar',
				'preference'=>'{"lugar":"papelera","vista":"listar","icono":"fa fa-times"}',
				'active'=>1
			)
		);	
		\DB::table('seg_option')->insert(array(
				'option'=>'Borrar',
				'action'=>'eliminar',
				'preference'=>'{"lugar":"escritorio","vista":"listar","icono":"fa fa-times"}',
				'active'=>1
			)
		);
		\DB::table('seg_option')->insert(array(
				'option'=>'Borrar',
				'action'=>'eliminar',
				'preference'=>'{"lugar":"escritorio","vista":"none","icono":"fa fa-times"}',
				'active'=>1
			)
		);
		\DB::table('seg_option')->insert(array(
				'option'=>'Abonar',
				'action'=>'abonar',
				'preference'=>'{"lugar":"escritorio","vista":"listar","icono":"fa fa-money"}',
				'active'=>1
			)
		);
		\DB::table('seg_option')->insert(array(
				'option'=>'Suscriptores',
				'action'=>'enumerar',
				'preference'=>'{"lugar":"escritorio","vista":"none","icono":"fa fa-money"}',
				'active'=>1
				)
		);
		\DB::table('seg_option')->insert(array(
				'option'=>'Beneficiarios',
				'action'=>'beneficiarios',
				'preference'=>'{"lugar":"escritorio","vista":"none","icono":"fa fa-money"}',
				'active'=>1
				)
		);
		\DB::table('seg_option')->insert(array(
				'option'=>'Entidades',
				'action'=>'enumerar',
				'preference'=>'{"lugar":"escritorio","vista":"none","icono":"fa fa-money"}',
				'active'=>1
				)
		);
		\DB::table('seg_option')->insert(array(
				'option'=>'Especialistas',
				'action'=>'especialistas',
				'preference'=>'{"lugar":"escritorio","vista":"none","icono":"fa fa-money"}',
				'active'=>1
				)
		);
		\DB::table('seg_option')->insert(array(
				'option'=>'Especialidades',
				'action'=>'especialidades',
				'preference'=>'{"lugar":"escritorio","vista":"none","icono":"fa fa-money"}',
				'active'=>1
				)
		);
		\DB::table('seg_option')->insert(array(
				'option'=>'Renovar',
				'action'=>'renovar',
				'preference'=>'{"lugar":"escritorio","vista":"listar","icono":"fa fa-repeat"}',
				'active'=>1
				)
		);
		\DB::table('seg_option')->insert(array(
				'option'=>'Carnet',
				'action'=>'carnet',
				'preference'=>'{"lugar":"escritorio","vista":"listar","icono":"fa fa-cloud-download"}',
				'active'=>1
				)
		);
		\DB::table('seg_option')->insert(array(
				'option'=>'CargaSuscriptor',
				'action'=>'cargasus',
				'preference'=>'{"lugar":"escritorio","vista":"listar","icono":"glyphicon glyphicon-book"}',
				'active'=>1
			)
		);
		\DB::table('seg_option')->insert(array(
				'option'=>'RenovarSuscripcion',
				'action'=>'renovarsuscripcion',
				'preference'=>'{"lugar":"escritorio","vista":"listar","icono":"fa fa-repeat"}',
				'active'=>1
			)
		);
		\DB::table('seg_option')->insert(array(
				'option'=>'Suscripciones',
				'action'=>'reportesuscripciones',
				'preference'=>'{"lugar":"escritorio","vista":"none","icono":"fa fa-money"}',
				'active'=>1
			)
		);
		\DB::table('seg_option')->insert(array(
			'option'=>'Facturacion',
			'action'=>'reportefacturacion',
			'preference'=>'{"lugar":"escritorio","vista":"none","icono":"fa fa-money"}',
			'active'=>1
			)
		);
		\DB::table('seg_option')->insert(array(
			'option'=>'ReporteGeneral',
			'action'=>'reportegeneral',
			'preference'=>'{"lugar":"escritorio","vista":"listar","icono":"fa fa-building-o"}',
			'active'=>1
			)
		);	
		\DB::table('seg_option')->insert(array(
			'option'=>'FacturacionGeneral',
			'action'=>'facturaciongeneral',
			'preference'=>'{"lugar":"escritorio","vista":"listar","icono":"fa fa-building-o"}',
			'active'=>1
			)
		);	

		\DB::table('seg_option')->insert(array(
			'option'=>'Carnet',
			'action'=>'imprimir_carnet',
			'preference'=>'{"lugar":"escritorio","vista":"listar","icono":"fa fa-file-pdf-o"}',
			'active'=>1
			)
		);	

		\DB::table('seg_option')->insert(array(
			'option'=>'FacturacionXLSX',
			'action'=>'facturacionxlsx',
			'preference'=>'{"lugar":"escritorio","vista":"listar","icono":"fa fa-file-excel-o"}',
			'active'=>1
			)
		);	
			
		
	}
}