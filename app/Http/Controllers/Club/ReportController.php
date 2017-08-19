<?php namespace App\Http\Controllers\Club;

use Validator;
use App\Core\Club\Suscription;
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
		
		//dd($moduledata);
		Session::flash('modulo', $moduledata);
		return view('club.reporte.suscripciones');

	}

	public function postReportegeneral(Request $request){
		
		//se quieren consultar todos
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') == ""){			
			$export=Suscription::
			select('clu_suscription.*','ufr.*','fr.*','fr.state as department','ad.names as names_ad','ad.surnames as surnames_ad','ad.identificacion as identificacion_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->get();			
		}

		//se quieren consultar desde una fecha inicial			
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') == ""){			
			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->whereBetween('date_suscription', [$request->input('inicio_rsg'), date( 'Y-m-j' )])
			->get();
		}

		//se quieren consultar una fecha final			
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') == ""){			
			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg')])
			->get();
		}

		//se quieren consultar desde una fecha inicial hasta una fecha final
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') == ""){						
			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->whereBetween('date_suscription', [$request->input('inicio_rsg'), $request->input('fin_rsg')])
			->get();
		}

		//se quieren consultar solo un estado	
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') == ""){					
			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
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
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->where('clu_state.state',$request->input('state'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg'), date( 'Y-m-j' )])
			->get();
		}

		//se quieren consultar desde una fecha final y el estado
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') == ""){						
			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->where('clu_state.state',$request->input('state'))
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg')])
			->get();
		}

		//se quieren consultar desde una fecha inicial, fecha final y el estado
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') == ""){						
			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->where('clu_state.state',$request->input('state'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg'), $request->input('fin_rsg')])
			->get();
		}

		//se quieren consultar con solo el asesor
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
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
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)
			->whereBetween('date_suscription', [$request->input('inicio_rsg'), date( 'Y-m-j' )])		
			->get();
		}

		//se quieren consultar el asesor,fecha final
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg')])
			->get();
		}

		//se quieren consultar el asesor,fecha inicial,fecha final
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)
			->whereBetween('date_suscription', [$request->input('inicio_rsg'), $request->input('fin_rsg')])
			->get();
		}

		//se quieren consultar el asesor,fecha inicial,fecha final,estado
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)
			->whereBetween('date_suscription', [$request->input('inicio_rsg'), $request->input('fin_rsg')])
			->where('clu_state.state',$request->input('state'))
			->get();
		}


		//se quieren consultar el asesor,fecha inicio, estado
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)
			->whereBetween('date_suscription', [$request->input('inicio_rsg'), date( 'Y-m-j' )])
			->where('clu_state.state',$request->input('state'))		
			->get();
		}

		//se quieren consultar el asesor,fecha final,estado
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') != "" && $request->input('city') == ""){					
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);
			
			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('ad.identificacion',$identificacion)
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg')])
			->where('clu_state.state',$request->input('state'))
			->get();
		}

		//se quieren consultar con solo ciudad
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') != ""){		
			
			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
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
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg'), date( 'Y-m-j' )]	)	
			->get();
		}

		//se quieren consultar con ciudad,fecha final
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') == "" && $request->input('city') != ""){		
			
			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg')])				
			->get();
		}

		//se quieren consultar con ciudad, estado
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') != ""){		
			
			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
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
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
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
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg'), $request->input('fin_rsg')])		
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio, estado
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') != ""){		
			
			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg'), date( 'Y-m-j' )]	)		
			->where('clu_state.state',$request->input('state'))
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio, asesor
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') != ""){		
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);

			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg'), date( 'Y-m-j' )])	
			->where('ad.identificacion',$identificacion)		
			->get();
		}

		//se quieren consultar con ciudad, fecha fin, estado
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') == "" && $request->input('city') != ""){		

			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg')])		
			->where('clu_state.state',$request->input('state'))	
			->get();
		}

		//se quieren consultar con ciudad, fecha fin, asesor
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') != ""){		
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);

			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', ['0000-00-00', $request->input('fin_rsg')])			
			->where('ad.identificacion',$identificacion)
			->get();
		}

		//se quieren consultar con ciudad, estado, asesor
		if($request->input('inicio_rsg') == "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') != "" && $request->input('city') != ""){		
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);

			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
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
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))
			->whereBetween('date_suscription', [$request->input('inicio_rsg'), $request->input('fin_rsg')])		
			->where('clu_state.state',$request->input('state'))
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio, fecha fin, asesor
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') == "" && $request->input('adviser') != "" && $request->input('city') != ""){		
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);

			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))			
			->whereBetween('date_suscription', [$request->input('inicio_rsg'), $request->input('fin_rsg')])	
			->where('ad.identificacion',$identificacion)
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio, estado, asesor
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') == "" && $request->input('state') != "" && $request->input('adviser') != "" && $request->input('city') != ""){		
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);

			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))			
			->whereBetween('date_suscription', [$request->input('inicio_rsg'), date( 'Y-m-j' )]	)
			->where('clu_state.state',$request->input('state'))
			->where('ad.identificacion',$identificacion)
			->get();
		}

		//se quieren consultar con ciudad, fecha inicio,fecha fin, estado, asesor
		if($request->input('inicio_rsg') != "" && $request->input('fin_rsg') != "" && $request->input('state') != "" && $request->input('adviser') != "" && $request->input('city') != ""){		
			$identificacion=(explode(' ',$request->input('adviser')));	
			$identificacion = end($identificacion);

			$export=Suscription::
			select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->where('fr.city',$request->input('city'))			
			->whereBetween('date_suscription', [$request->input('inicio_rsg'), $request->input('fin_rsg')])
			->where('clu_state.state',$request->input('state'))
			->where('ad.identificacion',$identificacion)
			->get();
		}


		//dd($export);
        \Excel::create('ReporteGeneral',function($excel) use ($export){
            $excel->sheet('Sheet 1',function($sheet) use ($export){
                $sheet->fromArray($export);
            });
        })->export('xlsx');

	}
		
	
	
}
