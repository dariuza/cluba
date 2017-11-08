<?php namespace App\Http\Controllers\Club;

use Validator;
use DateTime;
use App\Core\Club\Suscription;
use App\Core\Club\License;
use App\Core\Club\LicensePrint;
use App\Core\Club\Beneficiary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller {
	
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	protected $auth;
	
	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
		$this->middleware('guest');
	}
	
	
	public function getReportesuscripciones($id_app=null,$categoria=null,$id_mod=null){

		if(is_null($id_mod)) return Redirect::to('/')->with('error', 'Este modulo no se debe alcanzar por url, solo es valido desde las opciones del menú');

		$moduledata['id_mod']=$id_mod;
		$moduledata['modulo']='Reportes Suscripción';
		$moduledata['description']='Reportes del modulo de Suscripciones';
		$moduledata['id_app']=$id_app;
		$moduledata['categoria']=$categoria;

		//para el campo de asesor, como autocomplete
		$adviser = \DB::table('seg_user')
		->join('seg_rol', 'seg_user.rol_id', '=', 'seg_rol.id')
		->join('seg_user_profile','seg_user.id','=','seg_user_profile.user_id')
		->orwhere('rol_id', '=', 4)
		->orwhere('rol_id', '=', 9)
		->where('seg_user.active', '=', 1)
		->get();			
		foreach ($adviser as $ad){			
			$advisers[$ad->user_id] = $ad->names.' '.$ad->surnames.' ['.$ad->code_adviser.'] '.$ad->identificacion;
		}
		$moduledata['asesores']=$advisers;

		//para el campo de municipio, como autocomplete
		$citys = \DB::table('clu_city')		
		->get();			
		foreach ($citys as $ct){			
			$cityes[$ct->id] = $ct->city;
		}
		$moduledata['ciudades']=$cityes;

		//para el campo de estado, como autocomplete
		$states = \DB::table('clu_state')		
		->get();			
		foreach ($states as $st){			
			$stateses[$st->id] = $st->state;
		}
		$moduledata['estados']=$stateses;		
		
		Session::flash('modulo', $moduledata);
		return view('club.reporte.suscripciones');

	}

	public function postReportegeneral(Request $request){

		
		//se quieren consultar desde una fecha inicial			
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') == ""){			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', date( 'Y-m-j' )])
			->get();
		}

		//se quieren consultar una fecha final			
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') == ""){			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg').' 23:59:59'])
			->get();
		}

		//se quieren consultar desde una fecha inicial hasta una fecha final
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') == ""){						
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', $request->input('fin_rsg').' 23:59:59'])
			->get();
		}

		//se quieren consultar solo un estado	
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') == ""){					
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->where('clu_state.state',$request->input('state'))
			->get();
		}

		//se quieren consultar desde una fecha inicial y el estado
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') == ""){						
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->where('clu_state.state',$request->input('state'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', date( 'Y-m-j' )])
			->get();
		}

		//se quieren consultar desde una fecha final y el estado
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') == ""){						
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->where('clu_state.state',$request->input('state'))
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg').' 23:59:59'])
			->get();
		}

		//se quieren consultar desde una fecha inicial, fecha final y el estado
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') == ""){						
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->where('clu_state.state',$request->input('state'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', $request->input('fin_rsg').' 23:59:59'])
			->get();
		}

		//se quieren consultar con solo el asesor
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)			
			->get();
		}

		//se quieren consultar el asesor,fecha inicio
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', date( 'Y-m-j' )])		
			->get();
		}

		//se quieren consultar el asesor,fecha final
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg').' 23:59:59'])
			->get();
		}

		//se quieren consultar el asesor,fecha inicial,fecha final
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', $request->input('fin_rsg').' 23:59:59'])
			->get();
		}

		//se quieren consultar el asesor,fecha inicial,fecha final,estado
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', $request->input('fin_rsg').' 23:59:59'])
			->where('clu_state.state',$request->input('state'))
			->get();
		}


		//se quieren consultar el asesor,fecha inicio, estado
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', date( 'Y-m-j' )])
			->where('clu_state.state',$request->input('state'))		
			->get();
		}

		//se quieren consultar el asesor,fecha final,estado
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg').' 23:59:59'])
			->where('clu_state.state',$request->input('state'))
			->get();
		}

		//se quieren consultar con solo ciudad
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') != ""){		
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))					
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') != ""){		
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', date( 'Y-m-j' )]	)	
			->get();
		}

		//se quieren consultar con ciudad,fecha final
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') != ""){		
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg').' 23:59:59'])				
			->get();
		}

		//se quieren consultar con ciudad, estado
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') != ""){		
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))			
			->where('clu_state.state',$request->input('state'))			
			->get();
		}

		//se quieren consultar con ciudad, asesor
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') != ""){
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))			
			->where('ad.identificacion',$identificacion)		
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio, fecha fin
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') != ""){		
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', $request->input('fin_rsg').' 23:59:59'])		
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio, estado
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') != ""){		
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', date( 'Y-m-j' )]	)		
			->where('clu_state.state',$request->input('state'))
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio, asesor
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') != ""){		
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);

			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', date( 'Y-m-j' )])	
			->where('ad.identificacion',$identificacion)		
			->get();
		}

		//se quieren consultar con ciudad, fecha fin, estado
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') != ""){		

			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg').' 23:59:59'])		
			->where('clu_state.state',$request->input('state'))	
			->get();
		}

		//se quieren consultar con ciudad, fecha fin, asesor
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') != ""){		
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);

			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg').' 23:59:59'])			
			->where('ad.identificacion',$identificacion)
			->get();
		}

		//se quieren consultar con ciudad, estado, asesor
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') != "" && $request->input('city') != ""){		
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);

			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))			
			->where('clu_state.state',$request->input('state'))
			->where('ad.identificacion',$identificacion)
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio, fecha fin, estado
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') != ""){		
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', $request->input('fin_rsg').' 23:59:59'])		
			->where('clu_state.state',$request->input('state'))
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio, fecha fin, asesor
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') != ""){		
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);

			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))			
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', $request->input('fin_rsg').' 23:59:59'])	
			->where('ad.identificacion',$identificacion)
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio, estado, asesor
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') != "" && $request->input('city') != ""){		
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);

			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))			
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', date( 'Y-m-j' )]	)
			->where('clu_state.state',$request->input('state'))
			->where('ad.identificacion',$identificacion)
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio,fecha fin, estado, asesor
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') != "" && $request->input('city') != ""){		
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);

			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))			
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', $request->input('fin_rsg').' 23:59:59'])
			->where('clu_state.state',$request->input('state'))
			->where('ad.identificacion',$identificacion)
			->get();
		}

		//se quieren consultar todos
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') == ""){			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->get();			
		}


		foreach($export as $suscripcion){

			//Suscriptor			
			$suscripcion->Nombres_socio = $suscripcion->names_fr.' '.$suscripcion->surnames_fr;
			$suscripcion->Nombres_asesor = $suscripcion->names_ad.' ['.$suscripcion->Codigo_asesor.']';
			//proximo pago			
						
			$suscripcion->pagos = 0;
			//consultamos los pagos de cada suscripcion, solo para suscripciones con estado diferente de 1
			//lo siguinete es para la mora y para el estado
			
			if($suscripcion->state_id != 5 && $suscripcion->state_id != 6){
				//para pago  en mora y para pago pendiente

				$pagos = \DB::table('clu_payment')
				->select(\DB::raw('SUM(payment) as total_payment'))
				->groupBy('suscription_id')
				->where('suscription_id',$suscripcion->id)
				->get();

				$total_pagos = \DB::table('clu_payment')
				->select(\DB::raw('count(*) as total'))
				->where('suscription_id',$suscripcion->id)
				->get()[0]->total;

				
				//consultamos los costos por carnet				
				$suscripcion->precio_carnets = \DB::table('clu_license')
				->select(\DB::raw('SUM(price) as total_price'))
				->groupBy('suscription_id')
				->where('suscription_id',$suscripcion->id)
				->get();			
				
				if(!empty($suscripcion->precio_carnets)){
					$suscripcion->precio_carnets = $suscripcion->precio_carnets[0]->total_price;
				}else{
					$suscripcion->precio_carnets = 0;
				}
				
				//consultamos los costos por beneficiarios adicionales
				$cnts =
				License::
				where('clu_license.suscription_id', $suscripcion->id)
				->get()
				->toArray();
								
				$costo_bnes = \DB::table('clu_beneficiary')
				->select(\DB::raw('SUM(price) as total_price'))
				->groupBy('license_id')
				->where(function ($query) use ($cnts){
					foreach($cnts as $key => $value){
						$query->orwhere('clu_beneficiary.license_id', $value['id']);
					}
				})
				->get();
				
				//consultamos costos por reimpresion
				$suscripcion->precio_carnets_reimpresion = \DB::table('clu_license_print')
				->select(\DB::raw('SUM(price) as total_price'))
				->groupBy('suscription_id')
				->where('suscription_id',$suscripcion->id)
				->get();
				if(!empty($suscripcion->precio_carnets_reimpresion)){
					$suscripcion->precio_carnets_reimpresion = $suscripcion->precio_carnets_reimpresion[0]->total_price;
				}else{
					$suscripcion->precio_carnets_reimpresion = 0;
				}
				
				//pueden ser varios carnets
				$suscripcion->precio_beneficiarios_adicionales = 0;
				foreach($costo_bnes as $cb){
					$suscripcion->precio_beneficiarios_adicionales = $suscripcion->precio_beneficiarios_adicionales + $cb->total_price;
				}
				
				$hoy = new DateTime();
				$diff = $hoy->diff(new DateTime($suscripcion->next_pay), true)->days + 1;
				$suscripcion->edad = $hoy->diff(new DateTime($suscripcion->Fecha_nacimiento), true)->y;
				
				//actualización de estado y mora, solo para los estados diferentes a retirado y vencido
				$bandera_pago = false;
				
				if(count($pagos)){					
					$suscripcion->pagos = $pagos[0]->total_payment;
					$suscripcion->mora = ($suscripcion->precio + $suscripcion->precio_carnets + $suscripcion->precio_beneficiarios_adicionales + $suscripcion->precio_carnets_reimpresion) - ($pagos[0]->total_payment);					
					if(!$suscripcion->mora){						
						//si la mora es cero
						//se cambia el estado de las suscripción a 1, que es el estado de pago
						try {
							$SuscripctionAffectedRows = Suscription::where('id', $suscripcion->id)->update(array('state_id' => 1));
							$suscripcion->state = "Cancelado";
																					
							$bandera_pago = true;
						}catch (\Illuminate\Database\QueryException $e) {
							$message = 'La Suscripción no se logro editar';
							return Redirect::to('suscripcion/listar')->with('error', $message)->withInput()->with('modulo',$moduledata);;
						}
					}else{
						//hay uno o varios pagos
						if($pagos[0]->total_payment < 55000){
							try {
								$SuscripctionAffectedRows = Suscription::where('id', $suscripcion->id)->update(array('state_id' => 2));
								$suscripcion->state = "Pago pendiente";
								
							}catch (\Illuminate\Database\QueryException $e) {
								$message = 'La Suscripción no se logro editar';
								return Redirect::to('suscripcion/listar')->with('error', $message)->withInput()->with('modulo',$moduledata);;
							}
						}else{
							try {
								$SuscripctionAffectedRows = Suscription::where('id', $suscripcion->id)->update(array('state_id' => 7));
								$suscripcion->state = "Activa";
								
							}catch (\Illuminate\Database\QueryException $e) {
								$message = 'La Suscripción no se logro editar';
								return Redirect::to('suscripcion/listar')->with('error', $message)->withInput()->with('modulo',$moduledata);;
							}
						}
						
					}
				}else{
					//no tienen ningun pago
					//mora total
					$suscripcion->mora = $suscripcion->precio + $suscripcion->precio_carnets + $suscripcion->precio_beneficiarios_adicionales + $suscripcion->precio_carnets_reimpresion;
					try {
						$SuscripctionAffectedRows = Suscription::where('id', $suscripcion->id)->update(array('state_id' => 8));
						$suscripcion->state = "Prospecto";
						
					}catch (\Illuminate\Database\QueryException $e) {
						$message = 'La Suscripción no se logro editar';
						return Redirect::to('suscripcion/listar')->with('error', $message)->withInput()->with('modulo',$moduledata);;
					}
					
					
				}
				//las que tiene cuotas retrasadas
				if(!$bandera_pago){					
					if($diff < 0){
						//para los que estan en pago en mora
						try {
							$SuscripctionAffectedRows = Suscription::where('id', $suscripcion->id)->update(array('state_id' => 3));
							$suscripcion->state = "Pago en mora";
							
						}catch (\Illuminate\Database\QueryException $e) {
							$message = 'La Suscripción no se logro editar';
							return Redirect::to('suscripcion/listar')->with('error', $message)->withInput()->with('modulo',$moduledata);;
						}
					}					
				}
				
				//las suscripciones vencidas
				$fecha_ex = date_create($suscripcion->fecha_expiracion);//fecha de creacion de suscripción
				//$fecha_ex = $fecha_ex->format('Y-m-d');
				//$fecha_ex = date_create($fecha_ex);
				$diff_ex = $hoy->diff(new DateTime($suscripcion->fecha_expiracion), true)->days;
					
				if($hoy > $fecha_ex){
					try {
						$SuscripctionAffectedRows = Suscription::where('id', $suscripcion->id)->update(array('state_id' => 4));
						$suscripcion->state = "Suscripcion vencida";						
					}catch (\Illuminate\Database\QueryException $e) {
						$message = 'La Suscripción no se logro editar';
						return Redirect::to('suscripcion/listar')->with('error', $message)->withInput()->with('modulo',$moduledata);;
					}
				}elseif($diff_ex < 30){
					$suscripcion->fecha_expiracion = $suscripcion->fecha_expiracion;
						
				}
				
			}
			else{
				//pago realizado
				$suscripcion->mora = 0;
								
				$pagos = \DB::table('clu_payment')
				->select(\DB::raw('SUM(payment) as total_payment'))
				->groupBy('suscription_id')
				->where('suscription_id',$suscripcion->id)
				->get();
				if(count($pagos)){
					$suscripcion->pagos = $pagos[0]->total_payment;
				}
				
			}
			unset($suscripcion->id);
			unset($suscripcion->adviser_id);			
			unset($suscripcion->friend_id);
			unset($suscripcion->state_id);
			unset($suscripcion->pay_interval);
			unset($suscripcion->names_fr);
			unset($suscripcion->names_ad);
			unset($suscripcion->surnames_fr);
			unset($suscripcion->identificacion_fr);
					
		}
		
        \Excel::create('ReporteGeneral',function($excel) use ($export){
            $excel->sheet('Sheet 1',function($sheet) use ($export){
                $sheet->fromArray($export);
            });
        })->export('xlsx');

	}

	public function getReportefacturacion($id_app=null,$categoria=null,$id_mod=null){
		if(is_null($id_mod)) return Redirect::to('/')->with('error', 'Este modulo no se debe alcanzar por url, solo es valido desde las opciones del menú');

		$moduledata['id_mod']=$id_mod;
		$moduledata['modulo']='Facturación Suscripción';
		$moduledata['description']='Facturción del modulo de Suscripciones';
		$moduledata['id_app']=$id_app;
		$moduledata['categoria']=$categoria;

		//para el campo de asesor, como autocomplete
		$adviser = \DB::table('seg_user')
		->join('seg_rol', 'seg_user.rol_id', '=', 'seg_rol.id')
		->join('seg_user_profile','seg_user.id','=','seg_user_profile.user_id')
		->orwhere('rol_id', '=', 4)
		->orwhere('rol_id', '=', 9)
		->where('seg_user.active', '=', 1)
		->get();			
		foreach ($adviser as $ad){			
			$advisers[$ad->user_id] = $ad->names.' '.$ad->surnames.' ['.$ad->code_adviser.'] '.$ad->identificacion;
		}
		$moduledata['asesores']=$advisers;

		//para el campo de municipio, como autocomplete
		$citys = \DB::table('clu_city')		
		->get();			
		foreach ($citys as $ct){			
			$cityes[$ct->id] = $ct->city;
		}
		$moduledata['ciudades']=$cityes;

		//para el campo de estado, como autocomplete
		$states = \DB::table('clu_state')		
		->get();			
		foreach ($states as $st){			
			$stateses[$st->id] = $st->state;
		}
		$moduledata['estados']=$stateses;		
		
		Session::flash('modulo', $moduledata);
		return view('club.reporte.facturacion');
	}

	public function postFacturaciongeneral(Request $request){
		//consultamos las facturas
		$array = array();	
z
		//se quieren consultar desde una fecha inicial			
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') == ""){			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', date( 'Y-m-j' )])
			->get();
		}

		//se quieren consultar una fecha final			
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') == ""){			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg').' 23:59:59'])
			->get();
		}

		//se quieren consultar desde una fecha inicial hasta una fecha final
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') == ""){						
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', $request->input('fin_rsg').' 23:59:59'])
			->get();
		}

		//se quieren consultar solo un estado	
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') == ""){					
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->where('clu_state.state',$request->input('state'))
			->get();
		}

		//se quieren consultar desde una fecha inicial y el estado
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') == ""){						
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->where('clu_state.state',$request->input('state'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', date( 'Y-m-j' )])
			->get();
		}

		//se quieren consultar desde una fecha final y el estado
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') == ""){						
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->where('clu_state.state',$request->input('state'))
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg').' 23:59:59'])
			->get();
		}

		//se quieren consultar desde una fecha inicial, fecha final y el estado
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') == ""){						
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->where('clu_state.state',$request->input('state'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', $request->input('fin_rsg').' 23:59:59'])
			->get();
		}

		//se quieren consultar con solo el asesor
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)			
			->get();
		}

		//se quieren consultar el asesor,fecha inicio
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', date( 'Y-m-j' )])		
			->get();
		}

		//se quieren consultar el asesor,fecha final
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg').' 23:59:59'])
			->get();
		}

		//se quieren consultar el asesor,fecha inicial,fecha final
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', $request->input('fin_rsg').' 23:59:59'])
			->get();
		}

		//se quieren consultar el asesor,fecha inicial,fecha final,estado
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', $request->input('fin_rsg').' 23:59:59'])
			->where('clu_state.state',$request->input('state'))
			->get();
		}


		//se quieren consultar el asesor,fecha inicio, estado
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', date( 'Y-m-j' )])
			->where('clu_state.state',$request->input('state'))		
			->get();
		}

		//se quieren consultar el asesor,fecha final,estado
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg').' 23:59:59'])
			->where('clu_state.state',$request->input('state'))
			->get();
		}

		//se quieren consultar con solo ciudad
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') != ""){		
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))					
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') != ""){		
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', date( 'Y-m-j' )]	)	
			->get();
		}

		//se quieren consultar con ciudad,fecha final
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') != ""){		
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg').' 23:59:59'])				
			->get();
		}

		//se quieren consultar con ciudad, estado
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') != ""){		
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))			
			->where('clu_state.state',$request->input('state'))			
			->get();
		}

		//se quieren consultar con ciudad, asesor
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') != ""){
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))			
			->where('ad.identificacion',$identificacion)		
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio, fecha fin
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') != ""){		
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', $request->input('fin_rsg').' 23:59:59'])		
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio, estado
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') != ""){		
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', date( 'Y-m-j' )]	)		
			->where('clu_state.state',$request->input('state'))
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio, asesor
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') != ""){		
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);

			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', date( 'Y-m-j' )])	
			->where('ad.identificacion',$identificacion)		
			->get();
		}

		//se quieren consultar con ciudad, fecha fin, estado
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') != ""){		

			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg').' 23:59:59'])		
			->where('clu_state.state',$request->input('state'))	
			->get();
		}

		//se quieren consultar con ciudad, fecha fin, asesor
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') != ""){		
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);

			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg').' 23:59:59'])			
			->where('ad.identificacion',$identificacion)
			->get();
		}

		//se quieren consultar con ciudad, estado, asesor
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') != "" && $request->input('city') != ""){		
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);

			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))			
			->where('clu_state.state',$request->input('state'))
			->where('ad.identificacion',$identificacion)
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio, fecha fin, estado
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') != ""){		
			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', $request->input('fin_rsg').' 23:59:59'])		
			->where('clu_state.state',$request->input('state'))
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio, fecha fin, asesor
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') != ""){		
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);

			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))			
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', $request->input('fin_rsg').' 23:59:59'])	
			->where('ad.identificacion',$identificacion)
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio, estado, asesor
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') != "" && $request->input('city') != ""){		
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);

			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))			
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', date( 'Y-m-j' )]	)
			->where('clu_state.state',$request->input('state'))
			->where('ad.identificacion',$identificacion)
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio,fecha fin, estado, asesor
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') != "" && $request->input('city') != ""){		
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);

			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))			
			->whereBetween('date_suscription', [$request->input('inicio_rsg').' 00:00:00', $request->input('fin_rsg').' 23:59:59'])
			->where('clu_state.state',$request->input('state'))
			->where('ad.identificacion',$identificacion)
			->get();
		}

		//se quieren consultar con codigo			
		if($request->input('code') != ""){			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->where('clu_suscription.code','like', '%'. $request->input('code').'%')
			->get();
		}

		//se quieren consultar todos
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') == "" && $request->input('code') == ""){			
			$export=Suscription::
			select('clu_suscription.id','clu_suscription.code','clu_suscription.date_suscription as fecha_suscipcion','clu_suscription.date_expiration as fecha_expiracion','clu_suscription.price as precio','clu_suscription.pay_interval','clu_suscription.adviser_id','clu_suscription.observation as Observaciones','clu_suscription.reason as n_provisional','clu_suscription.waytopay as forma_pago','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email as Correo','fr.movil_number as Celular','fr.fix_number as Fijo','fr.birthdate as Fecha_nacimiento','fr.birthplace as Lugar_nacimiento','fr.sex as Genero','fr.adress as Direccion','fr.state as Departamento','fr.city as Municipio','fr.paymentadress as Dir_pago','fr.reference as Referencia','fr.reference_phone as Tel_referencia','ad.names as names_ad','ad.identificacion as identificacion_asesor','ad.code_adviser as Codigo_asesor','clu_state.state','clu_suscription.state_id')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->get();			
		}	

		foreach($export as $suscripcion){

			//Suscriptor			
			$suscripcion->Nombres_socio = $suscripcion->names_fr.' '.$suscripcion->surnames_fr;
			$suscripcion->Nombres_asesor = $suscripcion->names_ad.' ['.$suscripcion->Codigo_asesor.']';
			//proximo pago			
						
			$suscripcion->pagos = 0;
			$suscripcion->precio_beneficiarios_adicionales = 0;
			$suscripcion->precio_carnets_reimpresion=0;
			$suscripcion->precio_carnets=0;
			//consultamos los pagos de cada suscripcion, solo para suscripciones con estado diferente de 1
			//lo siguinete es para la mora y para el estado
			
			if($suscripcion->state_id != 5 && $suscripcion->state_id != 6){
				//para pago  en mora y para pago pendiente

				$pagos = \DB::table('clu_payment')
				->select(\DB::raw('SUM(payment) as total_payment'))
				->groupBy('suscription_id')
				->where('suscription_id',$suscripcion->id)
				->get();

				$total_pagos = \DB::table('clu_payment')
				->select(\DB::raw('count(*) as total'))
				->where('suscription_id',$suscripcion->id)
				->get()[0]->total;

				
				//consultamos los costos por carnet				
				$suscripcion->precio_carnets = \DB::table('clu_license')
				->select(\DB::raw('SUM(price) as total_price'))
				->groupBy('suscription_id')
				->where('suscription_id',$suscripcion->id)
				->get();			
				
				if(!empty($suscripcion->precio_carnets)){
					$suscripcion->precio_carnets = $suscripcion->precio_carnets[0]->total_price;
				}else{
					$suscripcion->precio_carnets = 0;
				}
				
				//consultamos los costos por beneficiarios adicionales
				$cnts =
				License::
				where('clu_license.suscription_id', $suscripcion->id)
				->get()
				->toArray();
								
				$costo_bnes = \DB::table('clu_beneficiary')
				->select(\DB::raw('SUM(price) as total_price'))
				->groupBy('license_id')
				->where(function ($query) use ($cnts){
					foreach($cnts as $key => $value){
						$query->orwhere('clu_beneficiary.license_id', $value['id']);
					}
				})
				->get();
				
				//consultamos costos por reimpresion
				$suscripcion->precio_carnets_reimpresion = \DB::table('clu_license_print')
				->select(\DB::raw('SUM(price) as total_price'))
				->groupBy('suscription_id')
				->where('suscription_id',$suscripcion->id)
				->get();
				if(!empty($suscripcion->precio_carnets_reimpresion)){
					$suscripcion->precio_carnets_reimpresion = $suscripcion->precio_carnets_reimpresion[0]->total_price;
				}else{
					$suscripcion->precio_carnets_reimpresion = 0;
				}
				
				//pueden ser varios carnets
				$suscripcion->precio_beneficiarios_adicionales = 0;
				foreach($costo_bnes as $cb){
					$suscripcion->precio_beneficiarios_adicionales = $suscripcion->precio_beneficiarios_adicionales + $cb->total_price;
				}
				
				$hoy = new DateTime();
				$diff = $hoy->diff(new DateTime($suscripcion->next_pay), true)->days + 1;
				$suscripcion->edad = $hoy->diff(new DateTime($suscripcion->Fecha_nacimiento), true)->y;
				
				//actualización de estado y mora
				$bandera_pago = false;
				
				if(count($pagos)){					
					$suscripcion->pagos = $pagos[0]->total_payment;
					$suscripcion->mora = ($suscripcion->precio + $suscripcion->precio_carnets + $suscripcion->precio_beneficiarios_adicionales + $suscripcion->precio_carnets_reimpresion) - ($pagos[0]->total_payment);					
					if(!$suscripcion->mora){						
						//si la mora es cero
						//se cambia el estado de las suscripción a 1, que es el estado de pago
						try {
							$SuscripctionAffectedRows = Suscription::where('id', $suscripcion->id)->update(array('state_id' => 1));
							$suscripcion->state = "Cancelado";
																					
							$bandera_pago = true;
						}catch (\Illuminate\Database\QueryException $e) {
							$message = 'La Suscripción no se logro editar';
							return Redirect::to('suscripcion/listar')->with('error', $message)->withInput()->with('modulo',$moduledata);;
						}
					}else{
						//hay uno o varios pagos
						if($pagos[0]->total_payment < 55000){
							try {
								$SuscripctionAffectedRows = Suscription::where('id', $suscripcion->id)->update(array('state_id' => 2));
								$suscripcion->state = "Pago pendiente";
								
							}catch (\Illuminate\Database\QueryException $e) {
								$message = 'La Suscripción no se logro editar';
								return Redirect::to('suscripcion/listar')->with('error', $message)->withInput()->with('modulo',$moduledata);;
							}
						}else{
							try {
								$SuscripctionAffectedRows = Suscription::where('id', $suscripcion->id)->update(array('state_id' => 7));
								$suscripcion->state = "Activa";
								
							}catch (\Illuminate\Database\QueryException $e) {
								$message = 'La Suscripción no se logro editar';
								return Redirect::to('suscripcion/listar')->with('error', $message)->withInput()->with('modulo',$moduledata);;
							}
						}
						
					}
				}else{
					//no tienen ningun pago
					//mora total
					$suscripcion->mora = $suscripcion->precio + $suscripcion->precio_carnets + $suscripcion->precio_beneficiarios_adicionales + $suscripcion->precio_carnets_reimpresion;
					try {
						$SuscripctionAffectedRows = Suscription::where('id', $suscripcion->id)->update(array('state_id' => 8));
						$suscripcion->state = "Prospecto";
						
					}catch (\Illuminate\Database\QueryException $e) {
						$message = 'La Suscripción no se logro editar';
						return Redirect::to('suscripcion/listar')->with('error', $message)->withInput()->with('modulo',$moduledata);;
					}
					
					
				}
				//las que tiene cuotas retrasadas
				if(!$bandera_pago){					
					if($diff < 0){
						//para los que estan en pago en mora
						try {
							$SuscripctionAffectedRows = Suscription::where('id', $suscripcion->id)->update(array('state_id' => 3));
							$suscripcion->state = "Pago en mora";
							
						}catch (\Illuminate\Database\QueryException $e) {
							$message = 'La Suscripción no se logro editar';
							return Redirect::to('suscripcion/listar')->with('error', $message)->withInput()->with('modulo',$moduledata);;
						}
					}					
				}
				
				//las suscripciones vencidas
				$fecha_ex = date_create($suscripcion->fecha_expiracion);//fecha de creacion de suscripción
				//$fecha_ex = $fecha_ex->format('Y-m-d');
				//$fecha_ex = date_create($fecha_ex);
				$diff_ex = $hoy->diff(new DateTime($suscripcion->fecha_expiracion), true)->days;
					
				if($hoy > $fecha_ex){
					try {
						$SuscripctionAffectedRows = Suscription::where('id', $suscripcion->id)->update(array('state_id' => 4));
						$suscripcion->state = "Suscripcion vencida";						
					}catch (\Illuminate\Database\QueryException $e) {
						$message = 'La Suscripción no se logro editar';
						return Redirect::to('suscripcion/listar')->with('error', $message)->withInput()->with('modulo',$moduledata);;
					}
				}elseif($diff_ex < 30){
					$suscripcion->fecha_expiracion = $suscripcion->fecha_expiracion;
						
				}
				
			}
			else{
				//pago realizado
				$suscripcion->mora = 0;
								
				$pagos = \DB::table('clu_payment')
				->select(\DB::raw('SUM(payment) as total_payment'))
				->groupBy('suscription_id')
				->where('suscription_id',$suscripcion->id)
				->get();
				if(count($pagos)){
					$suscripcion->pagos = $pagos[0]->total_payment;
				}
				
			}

			try{
				$suscripcion->fecha_suscipcion = date_create($suscripcion->fecha_suscipcion);
				$suscripcion->fecha_suscipcion = $suscripcion->fecha_suscipcion->format('Y-m-d');				
				$suscripcion->fecha_expiracion = date_create($suscripcion->fecha_expiracion);
				$suscripcion->fecha_expiracion = $suscripcion->fecha_expiracion->format('Y-m-d');

			}catch (Exception  $e) {
				dd($suscripcion);
			}
			unset($suscripcion->id);
			unset($suscripcion->adviser_id);			
			unset($suscripcion->friend_id);
			unset($suscripcion->state_id);
			unset($suscripcion->pay_interval);
			unset($suscripcion->names_fr);
			unset($suscripcion->names_ad);
			unset($suscripcion->surnames_fr);
			//unset($suscripcion->identificacion_fr);
			$suscripcion->annos=date('Y',strtotime($suscripcion->fecha_suscipcion));
			$suscripcion->mess=date('m',strtotime($suscripcion->fecha_suscipcion));
			$suscripcion->dias=date('d',strtotime($suscripcion->fecha_suscipcion));
			$suscripcion->annoe=date('Y',strtotime($suscripcion->fecha_expiracion));
			$suscripcion->mese=date('m',strtotime($suscripcion->fecha_expiracion));
			$suscripcion->diae=date('d',strtotime($suscripcion->fecha_expiracion));
			
		}

		$array['suscripciones']=$export->toArray();
		//dd($array['suscripciones']);
		//return view('invoice.generalpdf')->with('array',$array);
		
		//exportamos a pdf
		
		ini_set('max_execution_time', 1200); //300 seconds = 5 minutes
		$pdf = \PDF::loadView('invoice.generalpdf',$array);
		return $pdf->download(''.'facturacion'.'.pdf');		
	}
		
	
	
}
