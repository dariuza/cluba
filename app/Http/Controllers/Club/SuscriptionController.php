<?php namespace App\Http\Controllers\Club;

use Validator;
use DateTime;
use App\User;
use App\Core\Security\UserProfile;
use App\Core\Security\AppUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\Club\Suscription;
use App\Core\Club\Payment;
use App\Core\Club\Kardex;
use App\Core\Club\License;
use App\Core\Club\LicensePrint;
use App\Core\Club\Beneficiary;
use App\Core\Club\City;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class SuscriptionController extends Controller {
	
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

	public function getIndex($id=null, $modulo=null, $descripcion= null, $id_aplicacion = null, $categoria = null){
		//Modo de evitar que otros roles ingresen por la url
		if(is_null($id)) return Redirect::to('/')->with('error', 'Este modulo no se debe alcanzar por url, solo es valido desde las opciones del menú');
		//preparación de los datos
		$moduledata['id']=$id;
		$moduledata['modulo']=$modulo;
		$moduledata['description']=$descripcion;
		$moduledata['id_aplicacion']=$id_aplicacion;
		$moduledata['categoria']=$categoria;
		
		//consultas de suscripciones
		//total
		try {
			$moduledata['total_suscripciones']=\DB::table('clu_suscription')
			->select(\DB::raw('count(*) as total'))			
			->get()[0]->total;
		}catch (ModelNotFoundException $e) {
			$message = 'Problemas al hallar datos de '.$modulo;
			return Redirect::to('suscripcion/general')->with('error', $message);
		}
		
		//total agrupado por estados
		try {
			$moduledata['suscripciones_state']=\DB::table('clu_suscription')
			->select('state','alert', \DB::raw('count(*) as total'))
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->groupBy('state')
			->get();
		}catch (ModelNotFoundException $e) {
			$message = 'Problemas al hallar datos de '.$modulo.' de usuario';
			return Redirect::to('suscripcion/general')->with('error', $message);
		}
		
		//total agrupado por asesores
		try {
			$moduledata['suscripciones_advser']=\DB::table('clu_suscription')
			->select('names','surnames','identificacion', \DB::raw('count(*) as total'))
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->groupBy('clu_suscription.adviser_id')
			->get();
		}catch (ModelNotFoundException $e) {
			$message = 'Problemas al hallar datos de '.$modulo.' de usuario';
			return Redirect::to('suscripcion/general')->with('error', $message);
		}
		
		return Redirect::to('suscripcion/general')->with('modulo',$moduledata);
	}

	public function getGeneral(){
		if(is_null(Session::get('modulo.id'))) return Redirect::to('/')->with('error', 'Este modulo no se debe alcanzar por url, solo es valido desde las opciones del menú');
		return view('club.suscripcion.suscripcion_index');		
	}

	public function getEnumerar($id_app=null,$categoria=null,$id_mod=null){
		if(is_null($id_mod)) return Redirect::to('/')->with('error', 'Este modulo no se debe alcanzar por url, solo es valido desde las opciones del menú');
		
		return Redirect::to('suscripcion/listar');
	}

	public function getListar(){
		
		//preguntamos si tiene mensaje para no asignar las rutas.
		if(empty(Session::get('message'))){
			//las siguientes dos lineas solo son utiles cuando se refresca la pagina, ya que al refrescar no se pasa por el controlador
			$moduledata['fillable'] = ['N° Contrato','Suscriptor','Municipio','Asesor','Saldo','Abonos','Estado','Próximo abono','Fecha Vencimiento'];
			if(Session::get('opaplus.usuario.rol_id') == 4){
				$moduledata['fillable'] = ['N° Contrato','suscriptor','Identificación','Fecha Vencimiento'];
			}
			
			//recuperamos las variables del controlador anterior ante el echo de una actualización de pagina
			$url = explode("/", Session::get('_previous.url'));
			
			//estas opciones se usaran para pintar las opciones adecuadamente con respecto al modulo
			/*
			$moduledata['modulo'] = $url[count($url)-5];
			$moduledata['id_app'] = $url[count($url)-3];
			$moduledata['categoria'] = $url[count($url)-2];
			$moduledata['id_mod'] = $url[count($url)-1];
			*/
			$moduledata['modulo'] = 'suscripcion';
			$moduledata['id_app'] = '2';
			$moduledata['categoria'] = 'Componentes';
			$moduledata['id_mod'] = '7';
			
			Session::flash('modulo', $moduledata);
		}		
		
		return view('club.suscripcion.listar');
	}

	public function getListarajax(Request $request){
		//otros parametros
		$moduledata['total']=Suscription::count();
		
		$order_column = 'code';
		$order_dir = 'desc';
		
		if(!empty($request->input('columns'))){
			$order_column = $request->input('columns')[$request->input('order')[0]['column']]['data'];
			$order_dir = $request->input('order')[0]['dir'];
			if($request->input('order')[0]['column'] == 6 || $request->input('order')[0]['column'] == 7){
				$order_column = $request->input('columns')[0]['data'];
			}
		}		
		Session::flash('buscador', $request->input());
		//realizamos la consulta
		if(!empty($request->input('search')['value'])){
			Session::flash('search', $request->input('search')['value']);
			
			//condición para rol administrador y super administrador
			if(Session::get('opaplus.usuario.rol_id') == 1 || Session::get('opaplus.usuario.rol_id') == 2 || Session::get('opaplus.usuario.rol_id') == 9){
				//es supeadministrador
				$moduledata['suscripciones']= \DB::table('clu_suscription')
				->select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state','clu_state.alert')
				->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
				->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
				->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
				->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
				->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
				->where('clu_state.id','<>',6)
				->where(function ($query) {
					$query->where('clu_suscription.code', 'like', '%'.Session::get('search').'%')
					->orWhere('clu_state.state', 'like', '%'.Session::get('search').'%')
					->orWhere('clu_suscription.price', 'like', '%'.Session::get('search').'%')
					->orWhere('fr.identificacion', 'like',  '%'.Session::get('search').'%')
					->orWhere('ad.identificacion', 'like',  '%'.Session::get('search').'%')
					->orWhere('ad.code_adviser', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.names', 'like',  '%'.Session::get('search').'%')
					->orWhere('ad.names', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.surnames', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.state', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.city', 'like',  '%'.Session::get('search').'%')
					->orWhere('clu_suscription.date_suscription', 'like', '%'.Session::get('search').'%');
				})
				->orderBy($order_column, $order_dir)
				->skip($request->input('start'))->take($request->input('length'))
				->get();
			}elseif( Session::get('opaplus.usuario.rol_id') == 4){
				//es un asesor
				$moduledata['suscripciones']= \DB::table('clu_suscription')
				->select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state','clu_state.alert')
				->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
				->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
				->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
				->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
				->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
				->where('clu_suscription.adviser_id',Session::get('opaplus.usuario.id'))
				->where('clu_state.id','<>',6)
				->where(function ($query) {
					$query->where('clu_suscription.code', 'like', '%'.Session::get('search').'%')->orWhere('clu_state.state', 'like', '%'.Session::get('search').'%')
					->orWhere('clu_state.state', 'like', '%'.Session::get('search').'%')
					->orWhere('clu_suscription.price', 'like', '%'.Session::get('search').'%')
					->orWhere('fr.identificacion', 'like',  '%'.Session::get('search').'%')
					->orWhere('ad.identificacion', 'like',  '%'.Session::get('search').'%')
					->orWhere('ad.code_adviser', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.names', 'like',  '%'.Session::get('search').'%')
					->orWhere('ad.names', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.surnames', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.state', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.city', 'like',  '%'.Session::get('search').'%')
					->orWhere('clu_suscription.date_suscription', 'like', '%'.Session::get('search').'%');
				})
				->orderBy($order_column, $order_dir)
				->skip($request->input('start'))->take($request->input('length'))
				->get();
			}elseif( Session::get('opaplus.usuario.rol_id') == 3){
				//es un amigo
				$moduledata['suscripciones']= \DB::table('clu_suscription')
				->select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state','clu_state.alert')
				->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
				->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
				->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
				->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
				->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
				->where('clu_suscription.friend_id',Session::get('opaplus.usuario.id'))
				->where('clu_state.id','<>',6)
				->where(function ($query) {
					$query->where('clu_suscription.code', 'like', '%'.Session::get('search').'%')
					->orWhere('clu_state.state', 'like', '%'.Session::get('search').'%')
					->orWhere('clu_suscription.price', 'like', '%'.Session::get('search').'%')
					->orWhere('fr.identificacion', 'like',  '%'.Session::get('search').'%')
					->orWhere('ad.identificacion', 'like',  '%'.Session::get('search').'%')
					->orWhere('ad.code_adviser', 'like',  '%'.Session::get('search').'%')
					->orWhere('ad.names', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.names', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.surnames', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.state', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.city', 'like',  '%'.Session::get('search').'%')
					->orWhere('clu_suscription.date_suscription', 'like', '%'.Session::get('search').'%');
				})
				->orderBy($order_column, $order_dir)
				->skip($request->input('start'))->take($request->input('length'))
				->get();
			}elseif(Session::get('opaplus.usuario.rol_id') == 5 || Session::get('opaplus.usuario.rol_id') == 6){
				//jefe de area y asesor financiero, muestra solo las suscritas en su zona
				//primero vamos por las ciudades
				$ciudades = array();
				if(!empty(Session::get('opaplus.usuario.zone'))){
					$ciudades_id = explode(",", Session::get('opaplus.usuario.zone'));
				
					$zonas=\DB::table('clu_city')
					->select('city')
					->where(function($q) use ($ciudades_id){
						foreach($ciudades_id as $key => $value){
							$q->orwhere('id', '=', $value);
						}
					})->get();
				
					foreach($zonas as $zona){
						$ciudades[] = $zona->city;
					}
				
					$moduledata['suscripciones'] =\DB::table('clu_suscription')
					->select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state','clu_state.alert')
					->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
					->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
					->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
					->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
					->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
					->where('clu_state.id','<>',6)
					->where(function($q) use ($ciudades){
						foreach($ciudades as $key => $value){
							$q->orwhere('fr.city', '=', $value);
						}
					})
					->where(function ($query) {
						$query->where('clu_suscription.code', 'like', '%'.Session::get('search').'%')
						->orWhere('clu_state.state', 'like', '%'.Session::get('search').'%')
						->orWhere('clu_suscription.price', 'like', '%'.Session::get('search').'%')
						->orWhere('fr.identificacion', 'like',  '%'.Session::get('search').'%')
						->orWhere('ad.identificacion', 'like',  '%'.Session::get('search').'%')
						->orWhere('ad.code_adviser', 'like',  '%'.Session::get('search').'%')
						->orWhere('ad.names', 'like',  '%'.Session::get('search').'%')
						->orWhere('fr.names', 'like',  '%'.Session::get('search').'%')
						->orWhere('fr.surnames', 'like',  '%'.Session::get('search').'%')
						->orWhere('fr.state', 'like',  '%'.Session::get('search').'%')						
						->orWhere('clu_suscription.date_suscription', 'like', '%'.Session::get('search').'%');
					})
					->orderBy($order_column, $order_dir)
					->skip($request->input('start'))->take($request->input('length'))
					->get();
					$moduledata['filtro'] = count($moduledata['suscripciones']);
				}else{
					//dd('vacio');
					$moduledata['suscripciones'];
				}
				
			}			

			//total del filtro
			$moduledata['suscripciones_ft']= \DB::table('clu_suscription')
				->select('clu_suscription.*')
				->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
				->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
				->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
				->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
				->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
				->where('clu_state.id','<>',6)
				->where(function ($query) {
					$query->where('clu_suscription.code', 'like', '%'.Session::get('search').'%')
					->orWhere('clu_state.state', 'like', '%'.Session::get('search').'%')
					->orWhere('clu_suscription.price', 'like', '%'.Session::get('search').'%')
					->orWhere('fr.identificacion', 'like',  '%'.Session::get('search').'%')
					->orWhere('ad.identificacion', 'like',  '%'.Session::get('search').'%')
					->orWhere('ad.code_adviser', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.names', 'like',  '%'.Session::get('search').'%')
					->orWhere('ad.names', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.surnames', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.state', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.city', 'like',  '%'.Session::get('search').'%')
					->orWhere('clu_suscription.date_suscription', 'like', '%'.Session::get('search').'%');
				})
				->orderBy($order_column, $order_dir)				
				->
				count();
			$moduledata['filtro'] = $moduledata['suscripciones_ft'];

			
		}else{
			
			if(Session::get('opaplus.usuario.rol_id') == 1 || Session::get('opaplus.usuario.rol_id') == 2 || Session::get('opaplus.usuario.rol_id') == 9){
				//es un administrador,muestra todas las suscripciones
				$moduledata['suscripciones'] =\DB::table('clu_suscription')
				->select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state','clu_state.alert')
				->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
				->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
				->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
				->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
				->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
				->where('clu_state.id','<>',6)
				->orderBy($order_column, $order_dir)
				->skip($request->input('start'))->take($request->input('length'))
				->get();
				//$moduledata['filtro'] = count($moduledata['suscripciones']);
			}elseif( Session::get('opaplus.usuario.rol_id') == 4){
				//es un asesor muestra las suscripciones vendidas por el
				$moduledata['suscripciones'] =\DB::table('clu_suscription')
				->select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state','clu_state.alert')
				->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
				->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
				->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
				->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
				->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
				->where('clu_state.id','<>',6)
				->where('clu_suscription.adviser_id',Session::get('opaplus.usuario.id'))
				->orderBy($order_column, $order_dir)
				->skip($request->input('start'))->take($request->input('length'))
				->get();
				//$moduledata['filtro'] = count($moduledata['suscripciones']);
				
			}elseif(Session::get('opaplus.usuario.rol_id') == 3){
				//es un amigo, muestra solo sus suscripciones, incluidas susu renovaciones
				$moduledata['suscripciones'] =\DB::table('clu_suscription')
				->select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state','clu_state.alert')
				->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
				->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
				->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
				->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
				->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
				->where('clu_state.id','<>',6)
				->where('clu_suscription.friend_id',Session::get('opaplus.usuario.id'))
				->orderBy($order_column, $order_dir)
				->skip($request->input('start'))->take($request->input('length'))
				->get();
				//$moduledata['filtro'] = count($moduledata['suscripciones']);
				
			}elseif(Session::get('opaplus.usuario.rol_id') == 5 || Session::get('opaplus.usuario.rol_id') == 6){
				//jefe de area y asesor financiero, muestra solo las suscritas en su zona
				//primero vamos por las ciudades
				$ciudades = array();
				if(!empty(Session::get('opaplus.usuario.zone'))){
					$ciudades_id = explode(",", Session::get('opaplus.usuario.zone'));
						
					$zonas=\DB::table('clu_city')
					->select('city')
					->where(function($q) use ($ciudades_id){
						foreach($ciudades_id as $key => $value){
							$q->orwhere('id', '=', $value);
						}
					})->get();
						
					foreach($zonas as $zona){
						$ciudades[] = $zona->city;
					}
						
					$moduledata['suscripciones'] =\DB::table('clu_suscription')
					->select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state','clu_state.alert')
					->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
					->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
					->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
					->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
					->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
					->where('clu_state.id','<>',6)
					->where(function($q) use ($ciudades){
						foreach($ciudades as $key => $value){
							$q->orwhere('fr.city', '=', $value);
						}
					})
					->orderBy($order_column, $order_dir)
					->skip($request->input('start'))->take($request->input('length'))
					->get();
					//$moduledata['filtro'] = count($moduledata['suscripciones']);
				}else{
					//dd('vacio');
					$moduledata['suscripciones'];
				}
				
			}

			$moduledata['filtro'] = $moduledata['total'];
		}
		
		foreach($moduledata['suscripciones'] as $suscripcion){
			//Suscriptor			
			$suscripcion->names_fr = $suscripcion->names_fr.' '.$suscripcion->surnames_fr;
			$suscripcion->names_ad = $suscripcion->names_ad.' ['.$suscripcion->code_adviser_ad.']';
			//proximo pago			
			$suscripcion->next_pay = 'NA';
			$suscripcion->next_alert = '#dff0d8';//pago efectuado
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
				$suscripcion->price_cnt = \DB::table('clu_license')
				->select(\DB::raw('SUM(price) as total_price'))
				->groupBy('suscription_id')
				->where('suscription_id',$suscripcion->id)
				->get();			
				
				if(!empty($suscripcion->price_cnt)){
					$suscripcion->price_cnt = $suscripcion->price_cnt[0]->total_price;
				}else{
					$suscripcion->price_cnt = 0;
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
				$suscripcion->price_cnt_reprint = \DB::table('clu_license_print')
				->select(\DB::raw('SUM(price) as total_price'))
				->groupBy('suscription_id')
				->where('suscription_id',$suscripcion->id)
				->get();
				if(!empty($suscripcion->price_cnt_reprint)){
					$suscripcion->price_cnt_reprint = $suscripcion->price_cnt_reprint[0]->total_price;
				}else{
					$suscripcion->price_cnt_reprint = 0;
				}
				
				//pueden ser varios carnets
				$suscripcion->price_bnes = 0;
				foreach($costo_bnes as $cb){
					$suscripcion->price_bnes = $suscripcion->price_bnes + $cb->total_price;
				}
				
				//Para el proximo pago y las alertas de colores
				$suscripcion->next_pay = $suscripcion->pay_interval;
				//$fecha = date_create($suscripcion->pay_interval);//fecha del proximo pago
				//$fecha = $fecha->format('Y-m-d');//fecha proximo pago
				$next_pay = date_create($suscripcion->pay_interval);//fecha proximo pago	
				
				//alert para proximo pago
				$hoy = new DateTime();
				//$hoy = $hoy->format('Y-m-d');
				//$hoy = date_create($hoy);
					
				$diff = $hoy->diff(new DateTime($suscripcion->next_pay), true)->days + 1;
									
				if($hoy > $next_pay) {$diff = $diff*-1;}
					
				if($diff <= 0){
					$suscripcion->next_alert = '#f2dede';				
				}elseif($diff <= 7){
					$suscripcion->next_alert = '#fff5cc';
				}elseif($diff >= 8){
					$suscripcion->next_alert = '#d9edf7';
				}
					
				$suscripcion->next_pay = $suscripcion->next_pay.' ('.$diff.')';
				//fin priximo pago y alertas de colores
				
				$suscripcion->edad = $hoy->diff(new DateTime($suscripcion->birthdate), true)->y;
				
				//actualización de estado y mora
				$bandera_pago = false;
				if(count($pagos)){
					$suscripcion->pagos = $pagos[0]->total_payment;
					$suscripcion->mora = ($suscripcion->price + $suscripcion->price_cnt + $suscripcion->price_bnes + $suscripcion->price_cnt_reprint) - ($pagos[0]->total_payment);					
					if(!$suscripcion->mora){						
						//si la mora es cero
						//se cambia el estado de las suscripción a 1, que es el estado de pago
						try {
							$SuscripctionAffectedRows = Suscription::where('id', $suscripcion->id)->update(array('state_id' => 1));
							$suscripcion->state = "Cancelado";
							$suscripcion->state_id = 1;
							$suscripcion->alert = "#dff0d8";
							$suscripcion->next_pay = "NA";							
							$bandera_pago = true;
						}catch (\Illuminate\Database\QueryException $e) {
							$message = 'La Suscripción no se logro editar';
							return Redirect::to('suscripcion/listar')->with('error', $message)->withInput()->with('modulo',$moduledata);
						}
					}else{
						//hay uno o varios pagos
						if($pagos[0]->total_payment < 55000){
							try {
								$SuscripctionAffectedRows = Suscription::where('id', $suscripcion->id)->update(array('state_id' => 2));
								$suscripcion->state = "Pago pendiente";
								$suscripcion->alert = "#fff5cc";
								$suscripcion->state_id = 2;
							}catch (\Illuminate\Database\QueryException $e) {
								$message = 'La Suscripción no se logro editar';
								return Redirect::to('suscripcion/listar')->with('error', $message)->withInput()->with('modulo',$moduledata);;
							}
						}else{
							try {
								$SuscripctionAffectedRows = Suscription::where('id', $suscripcion->id)->update(array('state_id' => 7));
								$suscripcion->state = "Activa";
								$suscripcion->alert = "#e6fff7";
								$suscripcion->state_id = 7;
							}catch (\Illuminate\Database\QueryException $e) {
								$message = 'La Suscripción no se logro editar';
								return Redirect::to('suscripcion/listar')->with('error', $message)->withInput()->with('modulo',$moduledata);;
							}
						}
						
					}
				}else{
					//no tienen ningun pago
					//mora total
					$suscripcion->mora = $suscripcion->price + $suscripcion->price_cnt + $suscripcion->price_bnes + $suscripcion->price_cnt_reprint;
					try {
						$SuscripctionAffectedRows = Suscription::where('id', $suscripcion->id)->update(array('state_id' => 8));
						$suscripcion->state = "Prospecto";
						$suscripcion->alert = "#ffe6f2";
						$suscripcion->state_id = 8;
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
							$suscripcion->alert = "#f2dede";
							$suscripcion->state_id = 3;
						}catch (\Illuminate\Database\QueryException $e) {
							$message = 'La Suscripción no se logro editar';
							return Redirect::to('suscripcion/listar')->with('error', $message)->withInput()->with('modulo',$moduledata);;
						}
					}
					/*
					else{
						try {
							$SuscripctionAffectedRows = Suscription::where('id', $suscripcion->id)->update(array('state_id' => 2));
							$suscripcion->state = "Pago Pendiente";
							$suscripcion->alert = "#fff5cc";
						}catch (\Illuminate\Database\QueryException $e) {
							$message = 'La Suscripción no se logro editar';
							return Redirect::to('suscripcion/listar')->with('error', $message)->withInput()->with('modulo',$moduledata);;
						}
					}
					*/
				}
				
				//las suscripciones vencidas
				$fecha_ex = date_create($suscripcion->date_expiration);//fecha de creacion de suscripción
				//$fecha_ex = $fecha_ex->format('Y-m-d');
				//$fecha_ex = date_create($fecha_ex);
				$diff_ex = $hoy->diff(new DateTime($suscripcion->date_expiration), true)->days;
					
				if($hoy > $fecha_ex){
					try {
						$SuscripctionAffectedRows = Suscription::where('id', $suscripcion->id)->update(array('state_id' => 4));
						$suscripcion->state = "Suscripcion vencida";
						$suscripcion->alert = "#d9edf7";
						$suscripcion->state_id = 4;
					}catch (\Illuminate\Database\QueryException $e) {
						$message = 'La Suscripción no se logro editar';
						return Redirect::to('suscripcion/listar')->with('error', $message)->withInput()->with('modulo',$moduledata);;
					}
				}elseif($diff_ex < 30){
					$suscripcion->date_expiration = $suscripcion->date_expiration. ' ['.$diff_ex.']';
						
				}
				
			}
			else{
				//pago realizado
				$suscripcion->mora = 0;
				/*
				if($suscripcion->state_id == 6){
					$suscripcion->next_alert = "#ccfff5";
				}
				*/
				
				$pagos = \DB::table('clu_payment')
				->select(\DB::raw('SUM(payment) as total_payment'))
				->groupBy('suscription_id')
				->where('suscription_id',$suscripcion->id)
				->get();
				if(count($pagos)){
					$suscripcion->pagos = $pagos[0]->total_payment;
				}
				
			}
		
		}	
		
		return response()->json(['draw'=>$request->input('draw')+1,'recordsTotal'=>$moduledata['total'],'recordsFiltered'=>$moduledata['filtro'],'data'=>$moduledata['suscripciones']]);
	}

	//Función para la opción: agregar
	public function getCrear($id_app=null,$categoria=null,$id_mod=null){	
		//Modo de evitar que otros roles ingresen por la url		
		if(is_null($id_mod)) return Redirect::to('/')->with('error', 'Este modulo no se puede alcanzar por url, solo es valido desde las opciones del menú');
		
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

		Session::flash('_old_input.code', env('CODE_SUSCRIPTION',10000));
		if(!empty(\DB::table('clu_suscription')->max('code'))){
			Session::flash('_old_input.code', \DB::table('clu_suscription')->max('code') + 1);
		}
		
		$hoy = new DateTime();
		$hoy = $hoy->format('Y-m-d H:i:s');		
		Session::flash('_old_input.date_suscription', $hoy);
		Session::flash('_old_input.date_expiration', date ( 'Y-m-j' , strtotime ( '+1 year' , strtotime ( date('Y-m-j')))));
		Session::flash('_old_input.pay_interval', date ( 'Y-m-j' , strtotime ( '+1 month' , strtotime ( date('Y-m-j')))));
		
		//consultamos los departamentos
		$departments = \DB::table('clu_department')->get();		
		foreach ($departments as $department){
			$departamentos[$department->id] = $department->department;
		}
		$moduledata['departamentos']=$departamentos;

		$citys = \DB::table('clu_city')->get();	
		foreach ($citys as $city){
			$ciudades[$city->city] = $city->city;
		}
		$moduledata['ciudades2']=$ciudades;
			
		return Redirect::to('suscripcion/agregar')->with('modulo',$moduledata);
	}

	public function getAgregar(){
		
		return view('club.suscripcion.agregar');
	}

	//función para guardar usuarios con su perfil
	public function postSave(Request $request){
		
		$array_input = array();
		$array_input['_token'] = $request->input('_token');
		$array_input['email'] = $request->input('email');
		$array_input['name'] = $request->input('name');		
		foreach($request->input() as $key=>$value){
			if($key != "_token" && $key != "email" && $key != "name"){				
				$array_input[$key] = strtoupper($value);				
			}
		}
		$request->replace($array_input);	

		//preparación de datos
		$adviser = \DB::table('seg_user')
		->join('seg_rol', 'seg_user.rol_id', '=', 'seg_rol.id')
		->join('seg_user_profile','seg_user.id','=','seg_user_profile.user_id')
		->where('rol_id', '=', 4)
		->orwhere('rol_id', '=', 9)
		->where('seg_user.active', '=', 1)
		->get();
		
		foreach ($adviser as $ad){
			$advisers[$ad->user_id] = $ad->names.' '.$ad->surnames.' '.$ad->identificacion;
		}
		$moduledata['asesores']=$advisers;

		$hoy = new DateTime();
		$hoy = $hoy->format('Y-m-d H:i:s');		
		Session::flash('_old_input.date_suscription', $hoy);
		Session::flash('_old_input.date_expiration', date ( 'Y-m-j' , strtotime ( '+1 year' , strtotime ( date('Y-m-j')))));
		Session::flash('_old_input.pay_interval', date ( 'Y-m-j' , strtotime ( '+1 month' , strtotime ( date('Y-m-j')))));		
		
		//consultamos los departamentos
		$departments = \DB::table('clu_department')->get();
		foreach ($departments as $department){
			$departamentos[$department->id] = $department->department;
		}
		$moduledata['departamentos']=$departamentos;

		//consultamos las ciudades
		$citys = \DB::table('clu_city')->get();
		foreach ($citys as $city){
			$ciudades[$city->id] = $city->city;
		}
		$moduledata['ciudades']=$ciudades;

		foreach ($citys as $city){
			$ciudades[$city->city] = $city->city;
		}
		$moduledata['ciudades2']=$ciudades;
		
		$moduledata['estados']=\DB::table('clu_state')
		->select()
		->get();
			
		foreach ($moduledata['estados'] as $estado){
			$estados[$estado->id] = $estado->state;
		}
		$moduledata['estados']=$estados;		
		
		//Proceso de validación
		$hoy = date('Y-m-j');
		$fecha = strtotime('-8 year',strtotime($hoy));
		$fecha = date('Y-m-j',$fecha);
		
		$messages = [
			'required' => 'El campo :attribute es requerido.',
			'size' => 'La :attribute deberia ser mayor a :size.',
			'min' => 'La :attribute deberia tener almenos :min. caracteres',
			'max' => 'La :attribute no debe tener maximo :max. caracteres',
			'numeric' => 'El :attribute  debe ser un número',
			'before' => "El :attribute: no corresponde a un mayor de edad $fecha",
			'date' => 'El :attribute  No es una fecha valida',
		];
		
		$rules = array(				
			//'names'=> 'required',
			//'surnames'=> 'required',
			'identification'=> 'required|numeric',
			//'type_id'=>	'required',
			//'email'=> 'required|min:4|max:60',
			//'adress'=> 'required',
			'state'=> 'required',
			'city'=> 'required',
			//'birthplace'=> 'required',
			//'neighborhood'=> 'required',
			'movil_number'=> 'numeric',
			'fix_number'=> 'numeric',			
			//'birthdate'=> "date|before:$fecha",	
			'pay_interval'=> "date",
			'date_suscription'=> "date",
			'date_expiration'=> "date",
			//'waytopay'=> 'required',			
			'payment'=> 'numeric',	
			'adviser'=> 'required',					
		);
		
		$validator = Validator::make($request->input(), $rules, $messages);
		if ($validator->fails()) {			
			return Redirect::back()->withErrors($validator)->withInput()->with('modulo',$moduledata);
		}else{			
			$user = new User();			
			$userprofile = new UserProfile();
			$suscription = new Suscription();
			$payment = new Payment();
				
			$user->name = $request->input()['identification'];
			$user->ip = $request->server()['REMOTE_ADDR'];
			$user->email = $request->input()['identification'].'@yopmail.com';
			if(!empty($request->input()['email']))$user->email = $request->input()['email'];
			$user->password = '0000';
			$user->rol_id = 3;
			$user->login = 0;
			
			//consultamos el departamento
			$department = \DB::table('clu_department')
			->where('id',$request->input()['state'])
			->get()[0]->department;
			
			//consultamos la ciudad
			$city = \DB::table('clu_city')
			->where('id',$request->input()['city'])
			->get()[0]->city;	
			
			$code =  env('CODE_SUSCRIPTION',10000);
			
			//preparacion de datos
			//ciudades
			
			$citys = \DB::table('clu_city')->get();
			foreach ($citys as $ciudad){
				$ciudades[$ciudad->id] = $ciudad->city;
			}
			$moduledata['ciudades']=$ciudades;				
			
			if($request->input()['suscription_id']){
				
				if($request->input()['renovar'] == 'TRUE'){

					/**PIMERO EDITAMOS LA SUSCRIPCIÓN**/

					//usuario
					try {
						/*
						 $user = User::find($request->input()['user_id']);
						$user->name = $request->input()['identification'];					
						$user->email = $request->input()['email'];					
						$user->save();
						 */
						$userAffectedRows = User::where('id', $request->input()['user_id'])->update(array('ip' => $user->ip,'name' => $user->name,'email' => $user->email,'rol_id' => $user->rol_id));					
					}catch (\Illuminate\Database\QueryException $e){					
						return Redirect::to('suscripcion/agregar')->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
					}
					//perfil_usuario
					
					//crear su perfil
					$userprofile ->identificacion =  $request->input()['identification'];
					$userprofile ->type_id =  $request->input()['type_id'];
					$userprofile ->civil_status =  $request->input()['civil_status_suscriptor'];
					$userprofile ->names =  $request->input()['names'];
					$userprofile ->surnames =  $request->input()['surnames'];
					$userprofile ->birthdate =  $request->input()['birthdate'];
					$userprofile ->birthplace =  $request->input()['birthplace'];
					$userprofile ->sex =  $request->input()['sex'];
					$userprofile ->adress =  $request->input()['adress'];
					$userprofile ->state =  $department;
					$userprofile ->city =  $city;
					$userprofile ->neighborhood =  $request->input()['neighborhood'];
					$userprofile ->home =  $request->input()['home'];
					$userprofile ->movil_number =  $request->input()['movil_number'];
					$userprofile ->fix_number =  $request->input()['fix_number'];
					$userprofile ->profession =  $request->input()['profession'];
					$userprofile ->paymentadress =  $request->input()['paymentadress'];
					$userprofile ->reference =  $request->input()['reference'];
					$userprofile ->reference_phone =  $request->input()['reference_phone'];
					$userprofile ->template =  'default';
					$userprofile ->location =  57;
					$userprofile ->avatar =  'default.png';
					$userprofile ->user_id =  $request->input()['user_id'];
						
					try {					
						$userProfileAffectedRows = UserProfile::where('user_id', $request->input()['user_id'])->update(array(
						'identificacion' => $userprofile->identificacion,
						'type_id' => $userprofile->type_id,
						'civil_status' => $userprofile->civil_status,
						'names' => $userprofile->names,
						'surnames' => $userprofile->surnames,
						'birthdate' => $userprofile->birthdate,
						'birthplace' => $userprofile->birthplace,
						'sex' => $userprofile->sex,
						'adress' => $userprofile->adress,
						'state' => $userprofile->state,
						'city' => $userprofile->city,
						'neighborhood' => $userprofile->neighborhood,
						'home' => $userprofile->home,					
						'movil_number' => $userprofile->movil_number,
						'fix_number' => $userprofile->fix_number,					
						'profession' => $userprofile->profession,
						'paymentadress' => $userprofile->paymentadress,
						'reference' => $userprofile->reference,
						'reference_phone' => $userprofile->reference_phone,					
						'avatar' => $userprofile->avatar));
					}catch (\Illuminate\Database\QueryException $e) {					
						return Redirect::to('suscripcion/agregar')->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
					}
					
					//ACTUALIZACION DE SUSCRIPCIÓN
					/*
					if(!empty(\DB::table('clu_suscription')->max('code'))){
						Session::flash('_old_input.code', \DB::table('clu_suscription')->max('code') + 1);
					}
					*/
					
					//verificamos que el codigo no este en base
					//primero consultamos la suscripcion					
					
						$old_suscription = \DB::table('clu_suscription')->where('id', $request->input()['suscription_id'])->get()[0];
						if($old_suscription->code != $request->input()['code']){					
							if(count(\DB::table('clu_suscription')->where('code', $request->input()['code'])->get())){
								//existe el nuevo codigo
								return Redirect::to('suscripcion/agregar')->with('error', 'N° Contrato invalido, ya existe otra suscripción con codigo: '.$request->input()['code'])->withInput()->with('modulo',$moduledata);
							}
						}				
						
						$suscription->code = $request->input()['code'];				
						$suscription->date_suscription = date("Y-m-d H:i:s");//OJO CADA QUE SE ACTUALIZA SE ASIGNA NUEVA FECHA DE HOY
						
						if(!empty($request->input()['date_suscription'])){ $suscription->date_suscription = $request->input()['date_suscription'];}
						$suscription->date_expiration = date ( 'Y-m-j' , strtotime ( '+1 year' , strtotime ( date('Y-m-j'))));
						if(!empty($request->input()['date_expiration'])){ $suscription->date_expiration = $request->input()['date_expiration'];}					
					
						//si no hay renovación entonces editamos la suscripción
						$suscription->price = $request->input()['price'];
						$suscription->waytopay = $request->input()['waytopay'];				
						
						$suscription->pay_interval = date ( 'Y-m-j' , strtotime ( '+1 month' , strtotime ( date('Y-m-j'))));
						if(!empty($request->input()['pay_interval'])){ $suscription->pay_interval = $request->input()['pay_interval'];}
										
						//$suscription->reason = $request->input()['reason'];
						$suscription->observation = $request->input()['observation'];
						$suscription->reason = $request->input()['provisional'];
						$suscription->state_id = $request->input()['state_id'];
						//vamos por el asesor
						$array = explode(" ",$request->input()['adviser']);
						$identification = end($array);
						$id_adviser = UserProfile::select('user_id')
						->where('identificacion','=',$identification)
						->get()->toarray()[0]['user_id'];				
						$suscription->adviser_id= $id_adviser;
						
						try {
							$suscriptionAffectedRows = Suscription::where('id', $request->input()['suscription_id'])->update(array(
							'code' => $suscription->code,
							'date_suscription' => $suscription->date_suscription,
							'date_expiration' => $suscription->date_expiration,
							'price' => $suscription->price,
							'waytopay' => $suscription->waytopay,
							'pay_interval' => $suscription->pay_interval,
							'reason' => $suscription->reason,
							'observation' => $suscription->observation,
							'adviser_id' => $suscription->adviser_id,
							'state_id' => $suscription->state_id));
						}catch (\Illuminate\Database\QueryException $e) {
							return Redirect::to('suscripcion/agregar')->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
						}					
					
						//actualización de abonos
						$ids_pago = Array();
						$fechas_pagos = Array();
						$pagos = Array();
						$recibos = Array();
						
						foreach($request->input() as $key=>$value){
							if(strpos($key,'fecha_pago_') !== false){						
								$array=explode('_',$key);
								$id_abono = end($array);
								$fechas_pagos[$id_abono] = $value;
							}
							if(strpos($key,'pago_abono_') !== false){
								$array=explode('_',$key);
								$id_abono = end($array);
								$pagos[$id_abono] = $value;
							}

							if(strpos($key,'n_receipt_') !== false){
								$array=explode('_',$key);
								$id_abono = end($array);
								$recibos[$id_abono] = $value;
							}
						}				
						//abonos				
						$i=0;
						//borrado de todos los abonos
						Payment::where('suscription_id', (int) $request->input()['suscription_id'])->delete();
						//creación de todos los abonos
						foreach($pagos as $key=>$value){
							//if(!empty($fechas_pagos[$key]) && !empty($value)){
								try{						
									$payment = new Payment();
									$payment->date_payment = $fechas_pagos[$key];
									$payment->payment = $value;
									$payment->n_receipt = $recibos[$key];
									$payment->suscription_id = $request->input()['suscription_id'];
									$payment->save();
									/*
									 $paymentAffectedRows = Payment::where('id',$key)->update(array(
									 'date_payment' => $fechas_pagos[$key],
									 'payment' => $value));
									 */
								}catch (\Illuminate\Database\QueryException $e) {
									return Redirect::to('suscripcion/agregar')->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
								}
								
								//datos para interfaz grafica de abonos
								$moduledata['pagos'][$i]['id']=$key;
								$moduledata['pagos'][$i]['payment']=$value;
								$moduledata['pagos'][$i]['date_payment']=$fechas_pagos[$key];
								$moduledata['pagos'][$i]['n_receipt']=$recibos[$key];
								$i++;							
							//}
						}

						/**Actualizar Beneficiarios**/					
						//beneficiarios por suscripción
						$array = Array();
						foreach($request->input() as $key=>$value){
							if(strpos($key,'bne_') !== false){
								$vector=explode('_',$key);
								$n=count($vector);
								$id_bne = end($vector);
								
								$array[$vector[$n-2]][$id_bne][$vector[1]] = strtoupper($value);
							}
						}
						
						foreach($array as $key=>$vector){
							$id_cnt = $key;
							$ids_cnt = array();
							$exist_cnt =License::where('clu_license.id', $key)->where('clu_license.suscription_id', $request->input()['suscription_id'])
							//->where('clu_license.type', 'suscription_add')					
							->get()->toArray();				
							//creación de carnets
							
							if(empty($exist_cnt) || in_array($id_cnt,$ids_cnt)){
								//el segundo argumento del if es ya que puede pasar que el recien creado coincida en id con $id_cnt, para carnet nuevos
								//hay que crear un nuevo carnet, este es un nuevo beneficiary
								$cnt = new License();
								$cnt->type = 'suscription_add';
								$cnt->price = env('PRICE_LICENSE',5000);
								$cnt->date = date("Y-m-d H:i:s");
								$cnt->suscription_id = $request->input()['suscription_id'];
								try {
									$cnt->save();
								}catch (\Illuminate\Database\QueryException $e) {
									//eliminamos el usuario
									Suscription::destroy($request->input()['suscription_id']);
									User::destroy($request->input()['user_id']);
									return Redirect::back()->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
								}
								$id_cnt = $cnt->id;
								$ids_cnt[] = $cnt->id;
							}
							
							foreach($vector as $value){
								//alguno es no nulo
								if(!empty($value['names']) && !empty($value['surnames'])){							
									//verificamos la existencia de el carnet							
									if(!empty($value['beneficiaryid'])){
										//se pretende actualizar el beneficiario
										$benficiaryAffectedRows = Beneficiary::where('id',$value['beneficiaryid'])->update(array(
										'type_id' => $value['type'],
										'identification' => $value['identification'],
										'names' => $value['names'],
										'surnames' => $value['surnames'],
										'relationship' => $value['relationship'],
										'movil_number' => $value['movil'],
										'civil_status' => $value['civil'],
										'birthdate' => $value['birthdate'],
										'adress' => $value['adress'],
										'city' => $value['city'],
										'email' => $value['email']
										//'more' => $value['more']
										));
									}else{
										//nuevo suscriptor con su carnet
										$bne = new Beneficiary();
										$bne->type_id = $value['type'];
										$bne->identification = $value['identification'];
										$bne->names = $value['names'];
										$bne->surnames = $value['surnames'];
										$bne->relationship = $value['relationship'];
										$bne->movil_number = $value['movil'];
										$bne->state = 'Pago por suscripción';
										$bne->alert = '#dff0d8';
										$bne->civil_status = $value['civil'];
										$bne->birthdate = $value['birthdate'];
										$bne->adress = $value['adress'];
										$bne->city = $value['city'];
										$bne->email = $value['email'];
										//$bne->more = $value['more'];
										$bne->license_id = $id_cnt;
										try {
											$bne->save();
										}catch (\Illuminate\Database\QueryException $e) {
											//eliminamos el usuario
											Suscription::destroy($request->input()['suscription_id']);
											User::destroy($request->input()['user_id']);
											return Redirect::back()->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
										}	
									}
								
								}else{
									if(!empty($value['beneficiaryid'])){
										//se pretende borrar el beneficiario
										Beneficiary::where('id', (int)$value['beneficiaryid'])->delete();
									}
								}
							}						
							
						}
						
						//actualizar beneficiarios adicionales
						$array = Array();
						foreach($request->input() as $key=>$value){
							if(strpos($key,'bneadd_') !== false){
								$vector=explode('_',$key);
								$n=count($vector);
								$id_bne = end($vector);
								$array[$vector[$n-2]][$id_bne][$vector[1]] = strtoupper($value);
							}
						}
						//dd($array);
						foreach($array as $vector){
							foreach($vector as $value){
								if(!empty($value['names']) && !empty($value['surnames'])){
									if(!empty($value['beneficiaryid'])){
										//se pretende actualizar el beneficiario
										$benficiaryAffectedRows = Beneficiary::where('id',$value['beneficiaryid'])->update(array(
										'type_id' => $value['type'],
										'identification' => $value['identification'],
										'names' => $value['names'],
										'surnames' => $value['surnames'],
										'relationship' => $value['relationship'],
										'movil_number' => $value['movil'],
										'civil_status' => $value['civil'],
										'birthdate' => $value['birthdate'],
										'adress' => $value['adress'],
										'city' => $value['city'],
										'email' => $value['email']
										//'more' => $value['more']
										));
									}else{
										//se pretende guardar
										
										$cnt = new License();
										$cnt->type = 'beneficiary_add';
										//$cnt->price = env('PRICE_LICENSE',5000);
										$cnt->price = 0;//el precio del carnet va incluido
										$cnt->date = date("Y-m-d H:i:s");
										$cnt->suscription_id = $request->input()['suscription_id'];
										try {
											$cnt->save();
										}catch (\Illuminate\Database\QueryException $e) {
											//eliminamos el usuario
											Suscription::destroy($suscription->id);
											User::destroy($user->id);
											return Redirect::back()->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
										}
											
										$bne = new Beneficiary();
										$bne->type_id = $value['type'];
										$bne->identification = $value['identification'];
										$bne->names = $value['names'];
										$bne->surnames = $value['surnames'];
										$bne->relationship = $value['relationship'];
										$bne->movil_number = $value['movil'];
										$bne->price = env('PRICE_BENEFICIARY',20000);
										$bne->state = 'Pago pendiente';
										$bne->alert = '#dff0d8';
										$bne->civil_status = $value['civil'];
										$bne->birthdate = $value['birthdate'];
										$bne->adress = $value['adress'];
										$bne->city = $value['city'];
										$bne->email = $value['email'];
										//$bne->more = $value['more'];
										$bne->license_id = $cnt->id;
										try {
											$bne->save();
										}catch (\Illuminate\Database\QueryException $e) {
											//eliminamos el usuario
											Suscription::destroy($request->input()['suscription_id']);//con destruir la suscripción se destruyen sus carnets
											User::destroy($request->input()['user_id']);
											return Redirect::back()->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
										}
										
									}
								}else{
									if(!empty($value['beneficiaryid'])){
										//se pretende borrar el beneficiario
										Beneficiary::where('id', (int)$value['beneficiaryid'])->delete();
										License::where('id', $key)->delete();
									}
								}
								
							}
						}

					
					
					//limpiar carnet en blanco
					$cnts =
					License::
					where('clu_license.suscription_id', $request->input()['suscription_id'])
					->get()
					->toArray();
									
					$bandera_cnt = true;//para permitir solo un carnet con cero beneficiarios				
					foreach( $cnts as $cnt){
						
						$total_bnes = \DB::table('clu_beneficiary')
						->select(\DB::raw('count(*) as total'))
						->where('license_id',$cnt['id'])
						->get()[0]->total;
						
						if(!$total_bnes){
							//el total es cero
							if($bandera_cnt){
								$bandera_cnt = false;
							}else{							
								License::where('id', $cnt['id'])->delete();
							}
						}					
					}				

					/**fIN DE ACTUALIZACIÓN DE SUSCRIPCION**/


					//creamos el abono y abonamos el saldo en mora - cambiamos el estado de la suscripciòn
					//para la suscripcion antes de renovar, castigo de cartera
					if(intval($request->input()['mora']) > 0){

						$payment_obj = new Payment();					
						$payment_obj->date_payment = date("Y-m-d H:i:s");//Fecha  de abono de suscripción, HOY
						$payment_obj->payment =$request->input()['mora'];
						$payment_obj->n_receipt = 'SR'.date("YmdHis");//no puede ser NUll
						$payment_obj->suscription_id = $request->input()['suscription_id'];

						//guardamos el abono
						try {
							//el abono debe ser menor o igual a la mora						
							$payment_obj->save();						
					
						}catch (\Illuminate\Database\QueryException $e) {
							Session::flash('error', 'El abono no ha sido agregado en la suscripción anterior.');
							return Redirect::to('suscripcion/listar');	
						}

					}				
					
					//cambiamos el estado de la suscripcion
					$SuscripctionAffectedRows = Suscription::where('id', $request->input()['suscription_id'])->update(array('state_id' => 6));

					//guardamos la suscripcion castigada en una variable
					$old_suscriptcion=Suscription::
					where('clu_suscription.id', $request->input()['suscription_id'])
					->get()
					->toArray();					
					
					//Creamos la nueva suscripciòn y agregamos el saldo en mora al precio
					//consultamos el amigo y el asesor
					try {
						$suscription_renovation = new Suscription();
						//$suscription_renovation->code = $old_suscriptcion[0]['code'];
						$suscription_renovation->code = $request->input()['code'];
						$suscription_renovation->date_suscription = date("Y-m-d H:i:s");
						
						//calculo de proxima fecha de expiración
						$fecha_expiracion = date_create($old_suscriptcion[0]['date_expiration']);
						$hoy = new DateTime();
						if($hoy > $fecha_expiracion) { $fecha_expiracion = $hoy;}
						$fecha_expiracion = $fecha_expiracion->format('Y-m-j');
						
						$suscription_renovation->date_expiration = date ( 'Y-m-j' , strtotime ( '+1 year' , strtotime ( $fecha_expiracion)));
						$suscription_renovation->price = env('PRICE_SUSCRIPTION',135000) + $request->input()['mora'];
						$suscription_renovation->waytopay = $old_suscriptcion[0]['waytopay'];
						$suscription_renovation->reason = $request->input()['provisional'];
						$suscription_renovation->pay_interval = date ( 'Y-m-j' , strtotime ( '+1 month' , strtotime (date('Y-m-j'))));
						//$suscription_renovation->adviser_id = $old_suscriptcion[0]['adviser_id'];
						//vamos por el asesor
						$array = explode(" ",$request->input()['adviser']);
						$identification = end($array);
						$id_adviser = UserProfile::select('user_id')
						->where('identificacion','=',$identification)
						->get()->toarray()[0]['user_id'];				
						$suscription_renovation->adviser_id= $id_adviser;
						$suscription_renovation->friend_id = $old_suscriptcion[0]['friend_id'];
						$suscription_renovation->state_id= 2;
						
						$suscription_renovation->save();
					}catch (\Illuminate\Database\QueryException $e) {
						Session::flash('error', 'La renovación no ha sido efectuada. ');
						return Redirect::to('suscripcion/listar');	
					}

					//actualizamos los datos de usuario
					//crear su perfil
					$userprofile ->identificacion =  $request->input()['identification'];
					$userprofile ->type_id =  $request->input()['type_id'];
					$userprofile ->civil_status =  $request->input()['civil_status_suscriptor'];
					$userprofile ->names =  $request->input()['names'];
					$userprofile ->surnames =  $request->input()['surnames'];
					$userprofile ->birthdate =  $request->input()['birthdate'];
					$userprofile ->birthplace =  $request->input()['birthplace'];
					$userprofile ->sex =  $request->input()['sex'];
					$userprofile ->adress =  $request->input()['adress'];
					$userprofile ->state =  $department;
					$userprofile ->city =  $city;
					$userprofile ->neighborhood =  $request->input()['neighborhood'];
					$userprofile ->home =  $request->input()['home'];
					$userprofile ->movil_number =  $request->input()['movil_number'];
					$userprofile ->fix_number =  $request->input()['fix_number'];
					$userprofile ->profession =  $request->input()['profession'];
					$userprofile ->paymentadress =  $request->input()['paymentadress'];
					$userprofile ->reference =  $request->input()['reference'];
					$userprofile ->reference_phone =  $request->input()['reference_phone'];
					$userprofile ->template =  'default';
					$userprofile ->location =  57;
					$userprofile ->avatar =  'default.png';
					$userprofile ->user_id =  $request->input()['user_id'];//el usuario sigue siendo el mismo.
						
					try {					
						$userProfileAffectedRows = UserProfile::where('user_id', $request->input()['user_id'])->update(array(
						'identificacion' => $userprofile->identificacion,
						'type_id' => $userprofile->type_id,
						'civil_status' => $userprofile->civil_status,
						'names' => $userprofile->names,
						'surnames' => $userprofile->surnames,
						'birthdate' => $userprofile->birthdate,
						'birthplace' => $userprofile->birthplace,
						'sex' => $userprofile->sex,
						'adress' => $userprofile->adress,
						'state' => $userprofile->state,
						'city' => $userprofile->city,
						'neighborhood' => $userprofile->neighborhood,
						'home' => $userprofile->home,					
						'movil_number' => $userprofile->movil_number,
						'fix_number' => $userprofile->fix_number,					
						'profession' => $userprofile->profession,
						'paymentadress' => $userprofile->paymentadress,
						'reference' => $userprofile->reference,
						'reference_phone' => $userprofile->reference_phone,					
						'avatar' => $userprofile->avatar));
					}catch (\Illuminate\Database\QueryException $e) {					
						return Redirect::to('suscripcion/agregar')->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
					}

					//no se crea el abono, no hay abono, 100% seguro de que no hay

					//agregamos todos los carnet y los beneficiarios de los mismos y los beneficiarios adicionales
					//carnets
					$c_rel = array();//relacion de carnets
					$cnts =	License::where('clu_license.suscription_id',$old_suscriptcion[0]['id'])->get()->toArray();
					foreach($cnts as $cnt){
						try {
							$cnt_renovation = new License();
							$cnt_renovation->type = $cnt['type'];
							$cnt_renovation->price = $cnt['price'];
							$cnt_renovation->date = $cnt['date'];	
							$cnt_renovation->suscription_id = $suscription_renovation->id;
							$cnt_renovation->save();
							$c_rel[$cnt['id']] = $cnt_renovation->id;					 
						}catch (\Illuminate\Database\QueryException $e) {
							Suscription::destroy($suscription_renovation->id);
							Session::flash('error', 'La renovación no ha sido efectuada. ');
							return Redirect::to('suscripcion/listar');
						}
					
					}
					
					$bnes =
					Beneficiary::
					where(function ($query) use ($cnts){
						foreach($cnts as $key => $value){
							$query->orwhere('clu_beneficiary.license_id', $value['id']);
						}
					})
					->orderBy('license_id', 'asc')
					->get()
					->toArray();
					
					//beneficiarios
					foreach($bnes as $bne){
						try {
							$bne_renovation = new Beneficiary();
							$bne_renovation->type_id = $bne['type_id'];
							$bne_renovation->identification = $bne['identification'];
							$bne_renovation->names = $bne['names'];
							$bne_renovation->surnames = $bne['surnames'];
							$bne_renovation->relationship = $bne['relationship'];
							$bne_renovation->movil_number = $bne['movil_number'];
							$bne_renovation->state = $bne['state'];
							$bne_renovation->alert = $bne['alert'];
							$bne_renovation->price = $bne['price'];
							$bne_renovation->birthdate = $bne['birthdate'];
							$bne_renovation->adress = $bne['adress'];
							$bne_renovation->city = $bne['city'];
							$bne_renovation->email = $bne['email'];
							$bne_renovation->license_id = $c_rel[$bne['license_id']];
							$bne_renovation->save();					
						}catch (\Illuminate\Database\QueryException $e) {
							Suscription::destroy($suscription_renovation->id);
							Session::flash('error', 'La renovación no ha sido efectuada. ');
							return Redirect::to('suscripcion/listar');
						}
					}


					$moduledata['fillable'] = ['N° Contrato','Suscriptor','Municipio','Asesor','Saldo','Abonos','Estado','Próximo abono','Fecha Vencimiento'];
					if(Session::get('opaplus.usuario.rol_id') == 4){
						$moduledata['fillable'] = ['N° Contrato','suscriptor','Identificación','Fecha Vencimiento'];
					}
					
					//recuperamos las variables del controlador anterior ante el echo de una actualización de pagina
					$url = explode("/", Session::get('_previous.url'));
					
					//estas opciones se usaran para pintar las opciones adecuadamente con respecto al modulo
					/*
					$moduledata['modulo'] = $url[count($url)-5];
					$moduledata['id_app'] = $url[count($url)-3];
					$moduledata['categoria'] = $url[count($url)-2];
					$moduledata['id_mod'] = $url[count($url)-1];
					*/
					$moduledata['modulo'] = 'suscripcion';
					$moduledata['id_app'] = '2';
					$moduledata['categoria'] = 'Componentes';
					$moduledata['id_mod'] = '7';
					Session::flash('modulo', $moduledata);
					Session::flash('message', 'Suscripción se ha renovado exitosamente, codigo:'.$request->input()['code']);
					return Redirect::to('suscripcion/listar');
					
					//return Redirect::to('suscripcion/agrega')->with('message', 'Suscripción se ha renovado exitosamente, codigo:'.$request->input()['code'])->with('modulo',$moduledata);
				}
				
				if($request->input()['edit'] == 'TRUE'){


					/***SE PRETENDE ACTUALIZAR LA SUSCRIPCION***/
					//preparación de datos				
								
					//estado
					$moduledata['estados']=\DB::table('clu_state')
					->select()
					->get();
						
					foreach ($moduledata['estados'] as $estado){
						$estados[$estado->id] = $estado->state;
					}
					$moduledata['estados']=$estados;
					
					//usuario
					try {
						/*
						 $user = User::find($request->input()['user_id']);
						$user->name = $request->input()['identification'];					
						$user->email = $request->input()['email'];					
						$user->save();
						 */
						$userAffectedRows = User::where('id', $request->input()['user_id'])->update(array('ip' => $user->ip,'name' => $user->name,'email' => $user->email,'rol_id' => $user->rol_id));					
					}catch (\Illuminate\Database\QueryException $e){					
						return Redirect::to('suscripcion/agregar')->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
					}
					//perfil_usuario
					
					//crear su perfil
					$userprofile ->identificacion =  $request->input()['identification'];
					$userprofile ->type_id =  $request->input()['type_id'];
					$userprofile ->civil_status =  $request->input()['civil_status_suscriptor'];
					$userprofile ->names =  $request->input()['names'];
					$userprofile ->surnames =  $request->input()['surnames'];
					$userprofile ->birthdate =  $request->input()['birthdate'];
					$userprofile ->birthplace =  $request->input()['birthplace'];
					$userprofile ->sex =  $request->input()['sex'];
					$userprofile ->adress =  $request->input()['adress'];
					$userprofile ->state =  $department;
					$userprofile ->city =  $city;
					$userprofile ->neighborhood =  $request->input()['neighborhood'];
					$userprofile ->home =  $request->input()['home'];
					$userprofile ->movil_number =  $request->input()['movil_number'];
					$userprofile ->fix_number =  $request->input()['fix_number'];
					$userprofile ->profession =  $request->input()['profession'];
					$userprofile ->paymentadress =  $request->input()['paymentadress'];
					$userprofile ->reference =  $request->input()['reference'];
					$userprofile ->reference_phone =  $request->input()['reference_phone'];
					$userprofile ->template =  'default';
					$userprofile ->location =  57;
					$userprofile ->avatar =  'default.png';
					$userprofile ->user_id =  $request->input()['user_id'];
						
					try {					
						$userProfileAffectedRows = UserProfile::where('user_id', $request->input()['user_id'])->update(array(
						'identificacion' => $userprofile->identificacion,
						'type_id' => $userprofile->type_id,
						'civil_status' => $userprofile->civil_status,
						'names' => $userprofile->names,
						'surnames' => $userprofile->surnames,
						'birthdate' => $userprofile->birthdate,
						'birthplace' => $userprofile->birthplace,
						'sex' => $userprofile->sex,
						'adress' => $userprofile->adress,
						'state' => $userprofile->state,
						'city' => $userprofile->city,
						'neighborhood' => $userprofile->neighborhood,
						'home' => $userprofile->home,					
						'movil_number' => $userprofile->movil_number,
						'fix_number' => $userprofile->fix_number,					
						'profession' => $userprofile->profession,
						'paymentadress' => $userprofile->paymentadress,
						'reference' => $userprofile->reference,
						'reference_phone' => $userprofile->reference_phone,					
						'avatar' => $userprofile->avatar));
					}catch (\Illuminate\Database\QueryException $e) {					
						return Redirect::to('suscripcion/agregar')->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
					}
					
					//ACTUALIZACION DE SUSCRIPCIÓN
					/*
					if(!empty(\DB::table('clu_suscription')->max('code'))){
						Session::flash('_old_input.code', \DB::table('clu_suscription')->max('code') + 1);
					}
					*/
					
					//verificamos que el codigo no este en base
					//primero consultamos la suscripcion					
					if($request->input()['renovar'] == ''){	
						$old_suscription = \DB::table('clu_suscription')->where('id', $request->input()['suscription_id'])->get()[0];
						if($old_suscription->code != $request->input()['code']){					
							if(count(\DB::table('clu_suscription')->where('code', $request->input()['code'])->get())){
								//existe el nuevo codigo
								return Redirect::to('suscripcion/agregar')->with('error', 'N° Contrato invalido, ya existe otra suscripción con codigo: '.$request->input()['code'])->withInput()->with('modulo',$moduledata);
							}
						}				
						
						$suscription->code = $request->input()['code'];				
						$suscription->date_suscription = date("Y-m-d H:i:s");//OJO CADA QUE SE ACTUALIZA SE ASIGNA NUEVA FECHA DE HOY
						
						if(!empty($request->input()['date_suscription'])){ $suscription->date_suscription = $request->input()['date_suscription'];}
						$suscription->date_expiration = date ( 'Y-m-j' , strtotime ( '+1 year' , strtotime ( date('Y-m-j'))));
						if(!empty($request->input()['date_expiration'])){ $suscription->date_expiration = $request->input()['date_expiration'];}					
					
						//si no hay renovación entonces editamos la suscripción
						$suscription->price = $request->input()['price'];
						$suscription->waytopay = $request->input()['waytopay'];				
						
						$suscription->pay_interval = date ( 'Y-m-j' , strtotime ( '+1 month' , strtotime ( date('Y-m-j'))));
						if(!empty($request->input()['pay_interval'])){ $suscription->pay_interval = $request->input()['pay_interval'];}
										
						//$suscription->reason = $request->input()['reason'];
						$suscription->observation = $request->input()['observation'];
						$suscription->reason = $request->input()['provisional'];
						$suscription->state_id = $request->input()['state_id'];
						//vamos por el asesor
						$array = explode(" ",$request->input()['adviser']);
						$identification = end($array);
						$id_adviser = UserProfile::select('user_id')
						->where('identificacion','=',$identification)
						->get()->toarray()[0]['user_id'];				
						$suscription->adviser_id= $id_adviser;
						
						try {
							$suscriptionAffectedRows = Suscription::where('id', $request->input()['suscription_id'])->update(array(
							'code' => $suscription->code,
							'date_suscription' => $suscription->date_suscription,
							'date_expiration' => $suscription->date_expiration,
							'price' => $suscription->price,
							'waytopay' => $suscription->waytopay,
							'pay_interval' => $suscription->pay_interval,
							'reason' => $suscription->reason,
							'observation' => $suscription->observation,
							'adviser_id' => $suscription->adviser_id,
							'state_id' => $suscription->state_id));
						}catch (\Illuminate\Database\QueryException $e) {
							return Redirect::to('suscripcion/agregar')->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
						}					
					
						//actualización de abonos
						$ids_pago = Array();
						$fechas_pagos = Array();
						$pagos = Array();
						$recibos = Array();
						
						foreach($request->input() as $key=>$value){
							if(strpos($key,'fecha_pago_') !== false){						
								$array=explode('_',$key);
								$id_abono = end($array);
								$fechas_pagos[$id_abono] = $value;
							}
							if(strpos($key,'pago_abono_') !== false){
								$array=explode('_',$key);
								$id_abono = end($array);
								$pagos[$id_abono] = $value;
							}

							if(strpos($key,'n_receipt_') !== false){
								$array=explode('_',$key);
								$id_abono = end($array);
								$recibos[$id_abono] = $value;
							}
						}				
						//abonos				
						$i=0;
						//borrado de todos los abonos
						Payment::where('suscription_id', (int) $request->input()['suscription_id'])->delete();
						//creación de todos los abonos
						foreach($pagos as $key=>$value){
							//if(!empty($fechas_pagos[$key]) && !empty($value)){
								try{						
									$payment = new Payment();
									$payment->date_payment = $fechas_pagos[$key];
									$payment->payment = $value;
									$payment->n_receipt = $recibos[$key];
									$payment->suscription_id = $request->input()['suscription_id'];
									$payment->save();
									/*
									 $paymentAffectedRows = Payment::where('id',$key)->update(array(
									 'date_payment' => $fechas_pagos[$key],
									 'payment' => $value));
									 */
								}catch (\Illuminate\Database\QueryException $e) {
									return Redirect::to('suscripcion/agregar')->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
								}
								
								//datos para interfaz grafica de abonos
								$moduledata['pagos'][$i]['id']=$key;
								$moduledata['pagos'][$i]['payment']=$value;
								$moduledata['pagos'][$i]['date_payment']=$fechas_pagos[$key];
								$moduledata['pagos'][$i]['n_receipt']=$recibos[$key];
								$i++;							
							//}
						}

						/**Actualizar Beneficiarios**/					
						//beneficiarios por suscripción
						$array = Array();
						foreach($request->input() as $key=>$value){
							if(strpos($key,'bne_') !== false){
								$vector=explode('_',$key);
								$n=count($vector);
								$id_bne = end($vector);
								
								$array[$vector[$n-2]][$id_bne][$vector[1]] = strtoupper($value);
							}
						}
						
						foreach($array as $key=>$vector){
							$id_cnt = $key;
							$ids_cnt = array();
							$exist_cnt =License::where('clu_license.id', $key)->where('clu_license.suscription_id', $request->input()['suscription_id'])
							//->where('clu_license.type', 'suscription_add')					
							->get()->toArray();				
							//creación de carnets
							
							if(empty($exist_cnt) || in_array($id_cnt,$ids_cnt)){
								//el segundo argumento del if es ya que puede pasar que el recien creado coincida en id con $id_cnt, para carnet nuevos
								//hay que crear un nuevo carnet, este es un nuevo beneficiary
								$cnt = new License();
								$cnt->type = 'suscription_add';
								$cnt->price = env('PRICE_LICENSE',5000);
								$cnt->date = date("Y-m-d H:i:s");
								$cnt->suscription_id = $request->input()['suscription_id'];
								try {
									$cnt->save();
								}catch (\Illuminate\Database\QueryException $e) {
									//eliminamos el usuario
									Suscription::destroy($request->input()['suscription_id']);
									User::destroy($request->input()['user_id']);
									return Redirect::back()->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
								}
								$id_cnt = $cnt->id;
								$ids_cnt[] = $cnt->id;
							}
							
							foreach($vector as $value){
								//alguno es no nulo
								if(!empty($value['names']) && !empty($value['surnames'])){							
									//verificamos la existencia de el carnet							
									if(!empty($value['beneficiaryid'])){
										//se pretende actualizar el beneficiario
										$benficiaryAffectedRows = Beneficiary::where('id',$value['beneficiaryid'])->update(array(
										'type_id' => $value['type'],
										'identification' => $value['identification'],
										'names' => $value['names'],
										'surnames' => $value['surnames'],
										'relationship' => $value['relationship'],
										'movil_number' => $value['movil'],
										'civil_status' => $value['civil'],
										'birthdate' => $value['birthdate'],
										'adress' => $value['adress'],
										'city' => $value['city'],
										'email' => $value['email']
										//'more' => $value['more']
										));
									}else{
										//nuevo suscriptor con su carnet
										$bne = new Beneficiary();
										$bne->type_id = $value['type'];
										$bne->identification = $value['identification'];
										$bne->names = $value['names'];
										$bne->surnames = $value['surnames'];
										$bne->relationship = $value['relationship'];
										$bne->movil_number = $value['movil'];
										$bne->state = 'Pago por suscripción';
										$bne->alert = '#dff0d8';
										$bne->civil_status = $value['civil'];
										$bne->birthdate = $value['birthdate'];
										$bne->adress = $value['adress'];
										$bne->city = $value['city'];
										$bne->email = $value['email'];
										//$bne->more = $value['more'];
										$bne->license_id = $id_cnt;
										try {
											$bne->save();
										}catch (\Illuminate\Database\QueryException $e) {
											//eliminamos el usuario
											Suscription::destroy($request->input()['suscription_id']);
											User::destroy($request->input()['user_id']);
											return Redirect::back()->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
										}	
									}
								
								}else{
									if(!empty($value['beneficiaryid'])){
										//se pretende borrar el beneficiario
										Beneficiary::where('id', (int)$value['beneficiaryid'])->delete();
									}
								}
							}						
							
						}
						
						//actualizar beneficiarios adicionales
						$array = Array();
						foreach($request->input() as $key=>$value){
							if(strpos($key,'bneadd_') !== false){
								$vector=explode('_',$key);
								$n=count($vector);
								$id_bne = end($vector);
								$array[$vector[$n-2]][$id_bne][$vector[1]] = strtoupper($value);
							}
						}
						//dd($array);
						foreach($array as $vector){
							foreach($vector as $value){
								if(!empty($value['names']) && !empty($value['surnames'])){
									if(!empty($value['beneficiaryid'])){
										//se pretende actualizar el beneficiario
										$benficiaryAffectedRows = Beneficiary::where('id',$value['beneficiaryid'])->update(array(
										'type_id' => $value['type'],
										'identification' => $value['identification'],
										'names' => $value['names'],
										'surnames' => $value['surnames'],
										'relationship' => $value['relationship'],
										'movil_number' => $value['movil'],
										'civil_status' => $value['civil'],
										'birthdate' => $value['birthdate'],
										'adress' => $value['adress'],
										'city' => $value['city'],
										'email' => $value['email']
										//'more' => $value['more']
										));
									}else{
										//se pretende guardar
										
										$cnt = new License();
										$cnt->type = 'beneficiary_add';
										//$cnt->price = env('PRICE_LICENSE',5000);
										$cnt->price = 0;//el precio del carnet va incluido
										$cnt->date = date("Y-m-d H:i:s");
										$cnt->suscription_id = $request->input()['suscription_id'];
										try {
											$cnt->save();
										}catch (\Illuminate\Database\QueryException $e) {
											//eliminamos el usuario
											Suscription::destroy($suscription->id);
											User::destroy($user->id);
											return Redirect::back()->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
										}
											
										$bne = new Beneficiary();
										$bne->type_id = $value['type'];
										$bne->identification = $value['identification'];
										$bne->names = $value['names'];
										$bne->surnames = $value['surnames'];
										$bne->relationship = $value['relationship'];
										$bne->movil_number = $value['movil'];
										$bne->price = env('PRICE_BENEFICIARY',20000);
										$bne->state = 'Pago pendiente';
										$bne->alert = '#dff0d8';
										$bne->civil_status = $value['civil'];
										$bne->birthdate = $value['birthdate'];
										$bne->adress = $value['adress'];
										$bne->city = $value['city'];
										$bne->email = $value['email'];
										//$bne->more = $value['more'];
										$bne->license_id = $cnt->id;
										try {
											$bne->save();
										}catch (\Illuminate\Database\QueryException $e) {
											//eliminamos el usuario
											Suscription::destroy($request->input()['suscription_id']);//con destruir la suscripción se destruyen sus carnets
											User::destroy($request->input()['user_id']);
											return Redirect::back()->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
										}
										
									}
								}else{
									if(!empty($value['beneficiaryid'])){
										//se pretende borrar el beneficiario
										Beneficiary::where('id', (int)$value['beneficiaryid'])->delete();
										License::where('id', $key)->delete();
									}
								}
								
							}
						}

					}
					
					//limpiar carnet en blanco
					$cnts =
					License::
					where('clu_license.suscription_id', $request->input()['suscription_id'])
					->get()
					->toArray();
									
					$bandera_cnt = true;//para permitir solo un carnet con cero beneficiarios				
					foreach( $cnts as $cnt){
						
						$total_bnes = \DB::table('clu_beneficiary')
						->select(\DB::raw('count(*) as total'))
						->where('license_id',$cnt['id'])
						->get()[0]->total;
						
						if(!$total_bnes){
							//el total es cero
							if($bandera_cnt){
								$bandera_cnt = false;
							}else{							
								License::where('id', $cnt['id'])->delete();
							}
						}					
					}				
					
					//datos para interfaz grafica										
					$cnts =
					License::
					where('clu_license.suscription_id', $request->input()['suscription_id'])
					->get()
					->toArray();
					
					$moduledata['cnts'] = $cnts;
					
					$moduledata['bnes'] =
					Beneficiary::
					where(function ($query) use ($cnts){
						foreach($cnts as $key => $value){
							$query->orwhere('clu_beneficiary.license_id', $value['id']);
						}
					})
					->orderBy('license_id', 'asc')
					->get()
					->toArray();
					
					
					//consultamos el departamento
					$department_id = \DB::table('clu_department')
					->where('department',$userprofile->state)
					->get()[0]->id;
					
					//consultamos el ciudad
					$city_id = \DB::table('clu_city')
					->where('city',$userprofile->city)
					->get()[0]->id;
					
					
					Session::flash('_old_input.user_id',  $request->input()['user_id']);				
					Session::flash('_old_input.name',  $user->name);
					Session::flash('_old_input.email',  $user->email);
					
					Session::flash('_old_input.identification',  $userprofile->identificacion);
					Session::flash('_old_input.type_id',  $userprofile->type_id);	
					Session::flash('_old_input.civil_status_suscriptor', $userprofile->civil_status);			
					Session::flash('_old_input.names',  $userprofile->names);				
					Session::flash('_old_input.surnames',  $userprofile->surnames);
					Session::flash('_old_input.birthdate',  $userprofile->birthdate);
					Session::flash('_old_input.birthplace',  $userprofile->birthplace);
					Session::flash('_old_input.sex',  $userprofile->sex);
					Session::flash('_old_input.adress',  $userprofile->adress);
					Session::flash('_old_input.state',  $department_id);
					Session::flash('_old_input.city',  $city_id);
					Session::flash('_old_input.neighborhood',  $userprofile->neighborhood);
					Session::flash('_old_input.home',  $userprofile->home);
					Session::flash('_old_input.movil_number',  $userprofile->movil_number);
					Session::flash('_old_input.fix_number',  $userprofile->fix_number);
					Session::flash('_old_input.paymentadress',  $userprofile->paymentadress);
					Session::flash('_old_input.profession',  $userprofile->profession);
					Session::flash('_old_input.reference',  $userprofile->reference);
					Session::flash('_old_input.reference_phone',  $userprofile->reference_phone);

					Session::flash('_old_input.suscription_id', $request->input()['suscription_id']);
					Session::flash('_old_input.code',  $suscription->code);
					Session::flash('_old_input.waytopay',  $suscription->waytopay);
					Session::flash('_old_input.date_suscription', $suscription->date_suscription);
					Session::flash('_old_input.date_expiration', $suscription->date_expiration);
					Session::flash('_old_input.price', $suscription->price);
					Session::flash('_old_input.pay_interval',  $suscription->pay_interval);				
					Session::flash('_old_input.reason',  $suscription->reason);
					Session::flash('_old_input.observation',  $suscription->observation);
					Session::flash('_old_input.provisional',  $suscription->reason);
					Session::flash('_old_input.adviser',  $request->input()['adviser']);
					Session::flash('_old_input.state_id',  $suscription->state_id);
					
					Session::flash('_old_input.modulo_id', $request->input()['mod_id']);
					Session::flash('_old_input.edit', 'true');
					Session::flash('titulo', 'Editar');							
					
					return Redirect::to('suscripcion/agregar')->with('message', 'Suscripción editada exitosamente, codigo:'.$request->input()['code'])->with('modulo',$moduledata);
				}

				//para preparar los datos para interfaz grafica
				$suscription_id = $request->input()['suscription_id'];
				
			}else{
				
				/** NUEVA SUSCRIPCION ***/
				try {	
				//creación de usuario
				$user->save();
				//relacionar aplicacion
				$user_app = new AppUser();
				$user_app->app_id = 2;
				$user_app->user_id = $user->id;
				$user_app->save();
				}catch (\Illuminate\Database\QueryException $e) {					
					return Redirect::back()->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
				}
				
				//crear su perfil
				$userprofile ->identificacion =  $request->input()['identification'];
				$userprofile ->type_id =  $request->input()['type_id'];
				$userprofile ->civil_status =  $request->input()['civil_status_suscriptor'];
				$userprofile ->names =  $request->input()['names'];
				$userprofile ->surnames =  $request->input()['surnames'];
				$userprofile ->birthdate =  $request->input()['birthdate'];
				$userprofile ->birthplace =  $request->input()['birthplace'];
				$userprofile ->sex =  $request->input()['sex'];
				$userprofile ->adress =  $request->input()['adress'];
				$userprofile ->state =  $department;
				$userprofile ->city =  $city;
				$userprofile ->neighborhood =  $request->input()['neighborhood'];
				$userprofile ->home =  $request->input()['home'];				
				$userprofile ->movil_number =  $request->input()['movil_number'];
				$userprofile ->fix_number =  $request->input()['fix_number'];
				$userprofile ->profession =  $request->input()['profession'];
				$userprofile ->paymentadress =  $request->input()['paymentadress'];
				$userprofile ->reference =  $request->input()['reference'];
				$userprofile ->reference_phone =  $request->input()['reference_phone'];
				$userprofile ->template =  'default';
				$userprofile ->location =  57;
				$userprofile ->avatar =  'default.png';
				$userprofile ->user_id =  $user->id;
				try {
					$userprofile->save();
				}catch (\Illuminate\Database\QueryException $e) {
					//eliminamos el usuario					
					User::destroy($user->id);						
					return Redirect::back()->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);					
				}
					
				/**crear la suscripcion**/
				
				//verificación de unicidad del N° de contrato.
				if(count(\DB::table('clu_suscription')->where('code', $request->input()['code'])->get())){
					//existe el codigo
					User::destroy($user->id);
					return Redirect::to('suscripcion/agregar')->with('error', 'N° Contrato invalido, ya existe otra suscripción con codigo: '.$request->input()['code'])->withInput()->with('modulo',$moduledata);
				}
				
				//Asignacion de codigo
				if(!empty($request->input()['code'])){
					//input fue asignado OK
					$code = $request->input()['code'];
				}else{
					//input NO fue asignado
					if(!empty(\DB::table('clu_suscription')->max('code'))){
						//ya hay otras suscripciones
						$code = \DB::table('clu_suscription')->max('code') + 1;
					}
				}				
				$suscription->code = $code;			
				
				$suscription->date_suscription = date("Y-m-d H:i:s");
				if(!empty($request->input()['date_suscription'])){ $suscription->date_suscription = $request->input()['date_suscription'];}
				$suscription->date_expiration = date ( 'Y-m-j' , strtotime ( '+1 year' , strtotime ( date('Y-m-j'))));
				if(!empty($request->input()['date_expiration'])){ $suscription->date_expiration = $request->input()['date_expiration'];}
				$suscription->price = env('PRICE_SUSCRIPTION',135000);
				$suscription->waytopay = $request->input()['waytopay'];
				$suscription->pay_interval = date ( 'Y-m-j' , strtotime ( '+2 month' , strtotime ( date('Y-m-j'))));
				if(!empty($request->input()['pay_interval'])){ $suscription->pay_interval = $request->input()['pay_interval'];}
				//$suscription->fee = $request->input()['fee'];//Las cuotas de pago ya no se estan usando
				//$suscription->reason = $request->input()['reason'];//La razon de suscripcion ya no se usa más
				$suscription->observation = $request->input()['observation'];
				$suscription->reason = $request->input()['provisional'];				
				//vamos por el asesor
				$array = explode(" ",$request->input()['adviser']);
				$identification = end($array);
				$id_user_profile = UserProfile::select('user_id')
				->where('identificacion','=',$identification)
				->get()->toarray()[0]['user_id'];			
				
				$suscription->adviser_id= $id_user_profile;
				$suscription->friend_id= $user->id;
				//estado inicial en pago pendiente
				$suscription->state_id= 2;
				if($request->input()['payment'] == $suscription->price){
					//se pago el total de la suscripcion en el primer pago
					$suscription->state_id= 1;
				}
				
				try {
					$suscription->save();
				}catch (\Illuminate\Database\QueryException $e) {
					//eliminamos el usuario					
					User::destroy($user->id);
					return Redirect::back()->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
				}
				$suscription_id = $suscription->id;		
				
				//array de carnets
				$array = Array();
				foreach($request->input() as $key=>$value){
					if(strpos($key,'bne_') !== false){
						$vector=explode('_',$key);
						$n=count($vector);
						$id_bne = end($vector);
						$array[$vector[$n-2]][$id_bne][$vector[1]] = strtoupper($value);
					}
				}
				$carnets = count($array);
				
				$array = Array();
				foreach($request->input() as $key=>$value){
					if(strpos($key,'bneadd_') !== false){
						$vector=explode('_',$key);
						$n=count($vector);
						$id_bne = end($vector);
						$array[$vector[$n-2]][$id_bne][$vector[1]] = strtoupper($value);
					}
				}
				$addbenes = count($array);

				/**crear el abono**/				
				$payment->date_payment = date("Y-m-d H:i:s");
				$payment->payment = $request->input()['payment'];
				$payment->n_receipt = $request->input()['n_receipt'];
				$payment->suscription_id = $suscription->id;
				
				$bandera_abono = false;
				if($payment->payment > 0){
					//hay pago inicial
					try {
						//el primer pago debe ser menor o igual al precio
						if($payment->payment <= ($suscription->price + (($carnets-1)*env('PRICE_LICENSE',5000)) + ($addbenes*env('PRICE_BENEFICIARY',20000)) )){
							$payment->save();
						}else{
							//El abono es mayor al precio de la suscripciòn suscripciòn
							$bandera_abono = true;
							/*
							Suscription::destroy($suscription->id);
							User::destroy($user->id);
							return Redirect::back()->with('error', 'El pago inicial es mayor al precio de la suscripción y sus componentes')->withInput()->with('modulo',$moduledata);
							*/
						}
						
					}catch (\Illuminate\Database\QueryException $e) {
						//eliminamos el usuario
						Suscription::destroy($suscription->id);
						User::destroy($user->id);
						return Redirect::back()->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
					}
				}	
				
				/**creacion de beneficiarios y sus carnets**/				
				//beneficiarios por suscripción
				$array = Array();
				foreach($request->input() as $key=>$value){
					if(strpos($key,'bne_') !== false){
						$vector=explode('_',$key);
						$n=count($vector);
						$id_bne = end($vector);
						$array[$vector[$n-2]][$id_bne][$vector[1]] = strtoupper($value);
					}
				}
				
				$i=0;
				foreach($array as $key => $vector){					
					$cnt = new License();					
					if($i != 0){
						$cnt->type = 'suscription_add';						
						$cnt->price = env('PRICE_LICENSE',5000);
						$cnt->date = date("Y-m-d H:i:s");
						$cnt->suscription_id = $suscription->id;
						try {
							$cnt->save();
						}catch (\Illuminate\Database\QueryException $e) {
							//eliminamos el usuario
							Suscription::destroy($suscription->id);
							User::destroy($user->id);
							return Redirect::back()->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
						}
						$i++;
					}else{
						//solo cuando $i=0, el carnet se  esta creado
						$cnt->type = 'suscription';
						$cnt->price = 0;
						$cnt->date = date("Y-m-d H:i:s");
						$cnt->suscription_id = $suscription->id;
						try {
							$cnt->save();
						}catch (\Illuminate\Database\QueryException $e) {
							//eliminamos el usuario
							Suscription::destroy($suscription->id);
							User::destroy($user->id);
							return Redirect::back()->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
						}
						$i++;
						
					}
					
					foreach($vector as $value){						
						//alguno es no nulo
						if(!empty($value['names']) && !empty($value['surnames'])){
							//solo se crean carnets que posean beneficiarios					
							$bne = new Beneficiary();
							$bne->type_id = $value['type'];
							$bne->identification = $value['identification'];
							$bne->names = $value['names'];
							$bne->surnames = $value['surnames'];
							$bne->relationship = $value['relationship'];
							$bne->movil_number = $value['movil'];
							$bne->state = 'Pago por suscripción';
							$bne->alert = '#dff0d8';
							$bne->civil_status = $value['civil'];
							//$bne->more = $value['more'];
							$bne->license_id = $cnt->id;
							try {
								$bne->save();
							}catch (\Illuminate\Database\QueryException $e) {
								//eliminamos el usuario
								Suscription::destroy($suscription->id);//con destruir la suscripción se destruyen sus carnets
								User::destroy($user->id);
								return Redirect::back()->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
							}
						}
					}					
				}

				//beneficiarios adicionales, cada uno tiene un carnet
				$array = Array();
				foreach($request->input() as $key=>$value){
					if(strpos($key,'bneadd_') !== false){
						$vector=explode('_',$key);
						$n=count($vector);
						$id_bne = end($vector);
						$array[$vector[$n-2]][$id_bne][$vector[1]] = strtoupper($value);
					}
				}
								
				foreach($array as $vector){									
					foreach($vector as $value){
						//un carnet por cada beneficiario adicional
						if(!empty($value['names']) && !empty($value['surnames'])){
							
							$cnt = new License();
							$cnt->type = 'beneficiary_add';
							//$cnt->price = env('PRICE_LICENSE',5000);
							$cnt->price = 0;//el precio del carnet va incluido
							$cnt->date = date("Y-m-d H:i:s");
							$cnt->suscription_id = $suscription->id;
							try {
								$cnt->save();
							}catch (\Illuminate\Database\QueryException $e) {
								//eliminamos el usuario
								Suscription::destroy($suscription->id);
								User::destroy($user->id);
								return Redirect::back()->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
							}
							
							$bne = new Beneficiary();
							$bne->type_id = $value['type'];
							$bne->identification = $value['identification'];
							$bne->names = $value['names'];
							$bne->surnames = $value['surnames'];
							$bne->relationship = $value['relationship'];
							$bne->movil_number = $value['movil'];
							$bne->price = env('PRICE_BENEFICIARY',20000);
							$bne->state = 'Pago pendiente';
							$bne->alert = '#dff0d8';
							$bne->civil_status = $value['civil'];
							//$bne->more = $value['more'];
							$bne->license_id = $cnt->id;
							try {
								$bne->save();
							}catch (\Illuminate\Database\QueryException $e) {
								//eliminamos el usuario
								Suscription::destroy($suscription->id);//con destruir la suscripción se destruyen sus carnets
								User::destroy($user->id);
								return Redirect::back()->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
							}
						}
					}
				}
				
				//limpiar carnet en blanco
				$cnts =
				License::
				where('clu_license.suscription_id', $suscription_id)
				->get()
				->toArray();
				
				$bandera_cnt = true;//para permitir solo un carnet con cero beneficiarios
				foreach( $cnts as $cnt){
						
					$total_bnes = \DB::table('clu_beneficiary')
					->select(\DB::raw('count(*) as total'))
					->where('license_id',$cnt['id'])
					->get()[0]->total;
						
					if(!$total_bnes){
						//el total es cero
						if($bandera_cnt){
							$bandera_cnt = false;
						}else{
							License::where('id', $cnt['id'])->delete();
						}
					}
						
				}
				
				//datos para interfaz grafica
				//beneficiarios				
				$cnts =
				License::
				where('clu_license.suscription_id', $suscription_id)
				->get()
				->toArray();
				
				$moduledata['cnts'] = $cnts;
				
				$moduledata['bnes'] =
				Beneficiary::
				where(function ($query) use ($cnts){
					foreach($cnts as $key => $value){
						$query->orwhere('clu_beneficiary.license_id', $value['id']);
					}
				})
				->orderBy('license_id', 'asc')
				->get()
				->toArray();

				Session::flash('_old_input.code', env('CODE_SUSCRIPTION',10000));
				if(!empty(\DB::table('clu_suscription')->max('code'))){
					Session::flash('_old_input.code', \DB::table('clu_suscription')->max('code') + 1);
				}
				
				if(!$bandera_abono){
					return Redirect::to('suscripcion/agregar')->with('message', 'La Suscripción se agrego exitosamente con el código: '.$code)->with('modulo',$moduledata);
				}

				return Redirect::to('suscripcion/agregar')->with('message', 'La Suscripción se agrego exitosamente con el código: '.$code.' Sin embargo, el abono inicial, es mañor al precio de suscripciòn')->with('modulo',$moduledata);
			}
		}
	}

	public function getActualizar($id_app=null,$categoria=null,$id_mod=null,$id=null){
		if(is_null($id_mod)) return Redirect::to('/')->with('error', 'Este modulo no se puede alcanzar por url, solo es valido desde las opciones del menú');
		
		//preparación de datos
		$adviser = \DB::table('seg_user')
		->join('seg_rol', 'seg_user.rol_id', '=', 'seg_rol.id')
		->join('seg_user_profile','seg_user.id','=','seg_user_profile.user_id')
		->where('rol_id', '=', 4)
		->orwhere('rol_id', '=', 9)
		->where('seg_user.active', '=', 1)
		->get();
			
		foreach ($adviser as $ad){			
			$advisers[$ad->user_id] = $ad->names.' '.$ad->surnames.' ['.$ad->code_adviser.'] '.$ad->identificacion;
		}		
		$moduledata['asesores']=$advisers;		
		
		$departments = \DB::table('clu_department')->get();
		foreach ($departments as $department){
			$departamentos[$department->id] = $department->department;
		}
		$moduledata['departamentos']=$departamentos;
		
		$citys = \DB::table('clu_city')->get();
		foreach ($citys as $city){
			$ciudades[$city->id] = $city->city;
		}
		$moduledata['ciudades']=$ciudades;

		foreach ($citys as $city){
			$ciudades[$city->city] = $city->city;
		}
		$moduledata['ciudades2']=$ciudades;
		
		$suscripcion =
		Suscription::
		select('clu_suscription.*','ufr.*','fr.*','fr.state as department','ad.names as names_ad','ad.surnames as surnames_ad','ad.identificacion as identificacion_ad','clu_state.state')
		->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
		->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
		->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
		->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
		->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
		->where('clu_suscription.id', $id)
		->get()
		->toArray();
		
		//consultamos el departamento
		$department_id = \DB::table('clu_department')
		->where('department',$suscripcion[0]['department'])
		->get()[0]->id;
		
		//consultamos el ciudad
		$city_id = \DB::table('clu_city')
		->where('city',$suscripcion[0]['city'])
		->get();
		
		if(empty($city_id)){
			$city_id = 180;	
		}else{
			$city_id = $city_id[0]->id; 	
		}		
		
		//abonos
		//consultar los pagos de la suscripcción
		$moduledata['pagos'] =
		Payment::
		where('clu_payment.suscription_id', $id)
		->get()
		->toArray();
		
		//beneficiarios		
		$cnts =
		License::
		where('clu_license.suscription_id', $id)
		->get()
		->toArray();

		$moduledata['cnts'] = $cnts;
		
		$moduledata['bnes'] =
		Beneficiary::
		where(function ($query) use ($cnts){
			foreach($cnts as $key => $value){
				$query->orwhere('clu_beneficiary.license_id', $value['id']);
			}
		})
		->orderBy('license_id', 'asc')
		->get()
		->toArray();
		
		//estado
		$moduledata['estados']=\DB::table('clu_state')
		->select()
		->get();
			
		foreach ($moduledata['estados'] as $estado){
			$estados[$estado->id] = $estado->state;
		}
		$moduledata['estados']=$estados;
		
		Session::flash('_old_input.nb', 1);
		if(!empty(\DB::table('clu_beneficiary')->max('id'))){
			Session::flash('_old_input.nb', \DB::table('clu_beneficiary')->max('id') + 1);
		}
		Session::flash('_old_input.np', 1);
		if(!empty(\DB::table('clu_payment')->max('id'))){
			Session::flash('_old_input.np', \DB::table('clu_payment')->max('id') + 1);
		}
		Session::flash('_old_input.user_id',  $suscripcion[0]['user_id']);
		Session::flash('_old_input.name',  $suscripcion[0]['name']);
		Session::flash('_old_input.names',  $suscripcion[0]['names']);
		Session::flash('_old_input.surnames',  $suscripcion[0]['surnames']);
		Session::flash('_old_input.identification',  $suscripcion[0]['identificacion']);
		Session::flash('_old_input.type_id',  $suscripcion[0]['type_id']);
		Session::flash('_old_input.civil_status_suscriptor',  $suscripcion[0]['civil_status']);
		Session::flash('_old_input.email',  $suscripcion[0]['email']);
		Session::flash('_old_input.sex',  $suscripcion[0]['sex']);
		Session::flash('_old_input.birthdate',  $suscripcion[0]['birthdate']);
		Session::flash('_old_input.birthplace',  $suscripcion[0]['birthplace']);
		Session::flash('_old_input.adress',  $suscripcion[0]['adress']);		
		Session::flash('_old_input.state',  $department_id);
		Session::flash('_old_input.city',  $city_id);
		Session::flash('_old_input.neighborhood',  $suscripcion[0]['neighborhood']);
		Session::flash('_old_input.home',  $suscripcion[0]['home']);
		Session::flash('_old_input.movil_number',  $suscripcion[0]['movil_number']);
		Session::flash('_old_input.fix_number',  $suscripcion[0]['fix_number']);
		Session::flash('_old_input.date_suscription',  $suscripcion[0]['date_suscription']);
		Session::flash('_old_input.date_expiration',  $suscripcion[0]['date_expiration']);
		Session::flash('_old_input.paymentadress',  $suscripcion[0]['paymentadress']);
		Session::flash('_old_input.profession',  $suscripcion[0]['profession']);
		Session::flash('_old_input.reference',  $suscripcion[0]['reference']);
		Session::flash('_old_input.reference_phone',  $suscripcion[0]['reference_phone']);
		Session::flash('_old_input.code',  $suscripcion[0]['code']);
		Session::flash('_old_input.waytopay',  $suscripcion[0]['waytopay']);
		Session::flash('_old_input.price', $suscripcion[0]['price']);
		Session::flash('_old_input.pay_interval',  $suscripcion[0]['pay_interval']);
		Session::flash('_old_input.fee',  $suscripcion[0]['fee']);
		Session::flash('_old_input.provisional',  $suscripcion[0]['reason']);
		Session::flash('_old_input.state_id',  $suscripcion[0]['state_id']);
		Session::flash('_old_input.observation',  $suscripcion[0]['observation']);
		Session::flash('_old_input.adviser',  $suscripcion[0]['names_ad'].' '.$suscripcion[0]['surnames_ad'].' '.$suscripcion[0]['identificacion_ad']);		
		
		Session::flash('_old_input.suscription_id', $id);
		Session::flash('_old_input.modulo_id', $id_mod);
		Session::flash('_old_input.edit', 'true');
		Session::flash('titulo', 'Editar');
		
		return Redirect::to('suscripcion/agregar')->with('modulo',$moduledata);		
	}
	
	public function postBuscar(Request $request){
	
		$url = explode("/", Session::get('_previous.url'));
		$moduledata['fillable'] = ['N° Contrato','Suscriptor','Municipio','Asesor','Saldo','Abonos','Estado','Próximo abono','Fecha Vencimiento'];
		if(Session::get('opaplus.usuario.rol_id') == 4){
			$moduledata['fillable'] = ['N° Contrato','Suscriptor','Identificación','Fecha Vencimiento'];
		}
		//estas opciones se usaran para pintar las opciones adecuadamente con respecto al modulo
		$moduledata['modulo'] = $url[count($url)-4];
		$moduledata['id_app'] = $url[count($url)-2];
		$moduledata['categoria'] = $url[count($url)-1];
		$moduledata['id_mod'] = $url[count($url)-5];
			
		Session::flash('modulo', $moduledata);
		Session::flash('filtro', $request->input()['names']);
		return view('club.suscripcion.listar');
	}
	
	public function getBuscar($name = null){
	
		$url = explode("/", Session::get('_previous.url'));
		$moduledata['fillable'] = ['N° Contrato','Suscriptor','Municipio','Asesor','Saldo','Abonos','Estado','Próximo abono','Fecha Vencimiento'];
		if(Session::get('opaplus.usuario.rol_id') == 4){
			$moduledata['fillable'] = ['N° Contrato','Suscriptor','Identificación','Fecha Vencimiento'];
		}
		//estas opciones se usaran para pintar las opciones adecuadamente con respecto al modulo
		$moduledata['modulo'] = 'suscripcion';
		$moduledata['id_app'] = '2';
		$moduledata['categoria'] = 'Componentes';
		$moduledata['id_mod'] = '7';
			
		Session::flash('modulo', $moduledata);
		Session::flash('filtro', $name);
		return view('club.suscripcion.listar');
	}
	
	//para consultar los abonos
	public function postAbonar(Request $request){
	
		//consultar los pagos de la suscripción
		$pago =
		Payment::		
		where('clu_payment.suscription_id', $request->input()['id'])		
		->get()
		->toArray();
		
		$array['pagos'] = $pago;
		
		//consultamos los beneficiarios
		//primero los carnets
		$cnts=
		License::
		where('clu_license.suscription_id', $request->input()['id'])
		->get()
		->toArray();
		
		$bnes =
		Beneficiary::
		where(function ($query) use ($cnts){
			foreach($cnts as $key => $value){
				$query->orwhere('clu_beneficiary.license_id', $value['id']);
			}
		})
		->orderBy('license_id', 'asc')
		->get()
		->toArray();
		
		$array['bnes'] = $bnes;
		
		if(count($pago) || count($bnes)){
			return response()->json(['respuesta'=>true,'data'=>$array]);
		}
		
		return response()->json(['respuesta'=>true,'data'=>null]);
	}
	
	//no esta en uso
	/*
	public function postAbonarsave(Request $request){
		
		$payment = new Payment();
		//Fecha  de abono de suscripción
		$payment->date_payment = date("Y-m-d H:i:s");
		if(!empty($request->input()['date_payment'])){
			$payment->date_payment =$request->input()['date_payment'];
		}
		
		$payment->payment = $request->input()['payment'];
		$payment->suscription_id = $request->input()['suscription_id'];
		
		if($payment->payment > 0){
			//hay abono
			try {
				//el abono debe ser menor o igual a la mora
				if($payment->payment <= $request->input()['mora']){
					$payment->save();
					return response()->json(['respuesta'=>true,'data'=>true, 'code'=> $request->input()['code']]);
				}else{					
					return response()->json(['respuesta'=>true,'data'=>null, 'code'=> $request->input()['code']]);
				}
				
			}catch (\Illuminate\Database\QueryException $e) {				
				return response()->json(['respuesta'=>true,'data'=>null, 'code'=> $request->input()['code']]);
			}
		}
		
		return response()->json(['respuesta'=>true,'data'=>null, 'code'=> $request->input()['code']]);
	}
	*/
	
	public function getAbonarsave($payment = null, $date_payment = null,$suscription_id = null,$mora = null,$pay_interval = null){
		
		$array = explode("_",$suscription_id);
		$suscription_id = $array[0];
		$n_receipt = $array[1];
		
		//las siguientes dos lineas solo son utiles cuando se refresca la pagina, ya que al refrescar no se pasa por el controlador
		$moduledata['fillable'] = ['N° Contrato','Suscriptor','Municipio','Asesor','Saldo','Abonos','Estado','Próximo abono','Fecha Vencimiento'];
		if(Session::get('opaplus.usuario.rol_id') == 4){
			$moduledata['fillable'] = ['N° Contrato','Suscriptor','Identificación','Fecha Vencimiento'];
		}
		//recuperamos las variables del controlador anterior ante el echo de una actualización de pagina
		$url = explode("/", Session::get('_previous.url'));
		
		//estas opciones se usaran para pintar las opciones adecuadamente con respecto al modulo
		$moduledata['modulo'] = 'suscripcion';
		$moduledata['id_app'] = 2;
		$moduledata['categoria'] = 'Componentes';
		$moduledata['id_mod'] = 7;
			
		Session::flash('modulo', $moduledata);	
		
		$payment_obj = new Payment();
		//Fecha  de abono de suscripción
		$payment_obj->date_payment = date("Y-m-d H:i:s");
		if(!empty($date_payment)){
			$payment_obj->date_payment = $date_payment;
		}
		
		$payment_obj->payment =$payment;
		$payment_obj->n_receipt =$n_receipt;
		$payment_obj->suscription_id = $suscription_id;
		
		if($payment_obj->payment > 0){
			//hay abono
			try {
				//el abono debe ser menor o igual a la mora
				if($payment_obj->payment <= $mora){
					$payment_obj->save();								
				}else{
					Session::flash('error', 'El abono no ha sido agregado en la suscripción, pago es mayor a la mora. ');			
					return Redirect::to('suscripcion/listar');			
				}
		
			}catch (\Illuminate\Database\QueryException $e) {
				Session::flash('error', 'El abono no ha sido agregado en la suscripción. ');
				return Redirect::to('suscripcion/listar');	
			}
		}
		
		$suscription_obj = new Suscription();
		$suscription_obj->pay_interval = date ( 'Y-m-d' , strtotime ( '+1 month' , strtotime ( date('Y-m-d'))));
		
		if(!empty($pay_interval)){
			$suscription_obj->pay_interval = $pay_interval;
		}
		
		try {
			$suscriptionAffectedRows = Suscription::where('id', $suscription_id)->update(array(			
				'pay_interval' => $suscription_obj->pay_interval)
			);
			Session::flash('message', 'El abono ha sido agregado en la suscripción. ');
			return Redirect::to('suscripcion/listar');
		}catch (\Illuminate\Database\QueryException $e) {
			Session::flash('error', 'El abono no ha sido agregado en la suscripción. ');
			return Redirect::to('suscripcion/listar');	
		}		
	}
	
	public function getRenovar($suscription_id = null){
		
		//estas opciones se usaran para pintar las opciones adecuadamente con respecto al modulo
		$moduledata['modulo'] = 'suscripcion';
		$moduledata['id_app'] = 2;
		$moduledata['categoria'] = 'Componentes';
		$moduledata['id_mod'] = 7;
		
		$moduledata['fillable'] = ['N° Contrato','Suscriptor','Municipio','Asesor','Saldo','Abonos','Estado','Próximo abono','Fecha Vencimiento'];
		if(Session::get('opaplus.usuario.rol_id') == 4){
			$moduledata['fillable'] = ['N° Contrato','suscriptor','Identificación','Fecha Vencimiento'];
		}
			
		Session::flash('modulo', $moduledata);
		
		//Verificamos que el estado sea diferente de 2 0 3
		//consultamos la suscripción $request->input()['id'];
		$suscription = Suscription::where('id', $suscription_id)->get()[0];
		if($suscription->state_id == 1 || $suscription->state_id == 4){
			//verificar que no tenga cuentas por pagar
			
			
			//creamos una nueva suscripción con los mismos datos de la suscripción actual
			try {
				$suscription_renovation = new Suscription();
				$suscription_renovation->code = $suscription->code;
				$suscription_renovation->date_suscription = date("Y-m-d H:i:s");
				
				//calculo de proxima fecha de expiración
				$fecha_expiracion = date_create($suscription->date_expiration);
				$hoy = new DateTime();
				if($hoy > $fecha_expiracion) { $fecha_expiracion = $hoy;}
				$fecha_expiracion = $fecha_expiracion->format('Y-m-j');
				
				$suscription_renovation->date_expiration = date ( 'Y-m-j' , strtotime ( '+1 year' , strtotime ( $fecha_expiracion)));
				$suscription_renovation->price = env('PRICE_SUSCRIPTION',135000);
				$suscription_renovation->waytopay = $suscription->waytopay;
				$suscription_renovation->pay_interval = date ( 'Y-m-j' , strtotime ( '+1 month' , strtotime (date('Y-m-j'))));
				$suscription_renovation->adviser_id = $suscription->adviser_id;
				$suscription_renovation->friend_id = $suscription->friend_id;
				$suscription_renovation->state_id= 2;
				$suscription_renovation->save();
			}catch (\Illuminate\Database\QueryException $e) {
				Session::flash('error', 'La renovación no ha sido efectuada. ');
				return Redirect::to('suscripcion/listar');	
			}
			//cambiar estado a renovado
			$SuscripctionAffectedRows = Suscription::where('id', $suscription_id)->update(array('state_id' => 6));
			
			Session::flash('message', 'La renovación ha sido efectuada');
						
			//agregamos todos los carnet y los beneficiarios de los mismos y los beneficiarios adicionales
			//carnets
			$c_rel = array();//relacion de carnets
			$cnts =	License::where('clu_license.suscription_id',$suscription_id)->get()->toArray();
			foreach($cnts as $cnt){
				try {
					$cnt_renovation = new License();
					$cnt_renovation->type = $cnt['type'];
					$cnt_renovation->price = $cnt['price'];
					$cnt_renovation->date = $cnt['date'];	
					$cnt_renovation->suscription_id = $suscription_renovation->id;
					$cnt_renovation->save();
					$c_rel[$cnt['id']] = $cnt_renovation->id;					 
				}catch (\Illuminate\Database\QueryException $e) {
					Suscription::destroy($suscription_renovation->id);
					Session::flash('error', 'La renovación no ha sido efectuada. ');
					return Redirect::to('suscripcion/listar');
				}
			
			}
			
			$bnes =
			Beneficiary::
			where(function ($query) use ($cnts){
				foreach($cnts as $key => $value){
					$query->orwhere('clu_beneficiary.license_id', $value['id']);
				}
			})
			->orderBy('license_id', 'asc')
			->get()
			->toArray();
			
			//beneficiarios
			foreach($bnes as $bne){
				try {
					$bne_renovation = new Beneficiary();
					$bne_renovation->type_id = $bne['type_id'];
					$bne_renovation->identification = $bne['identification'];
					$bne_renovation->names = $bne['names'];
					$bne_renovation->surnames = $bne['surnames'];
					$bne_renovation->relationship = $bne['relationship'];
					$bne_renovation->movil_number = $bne['movil_number'];
					$bne_renovation->state = $bne['state'];
					$bne_renovation->alert = $bne['alert'];
					$bne_renovation->price = $bne['price'];
					$bne_renovation->license_id = $c_rel[$bne['license_id']];
					$bne_renovation->save();					
				}catch (\Illuminate\Database\QueryException $e) {
					Suscription::destroy($suscription_renovation->id);
					Session::flash('error', 'La renovación no ha sido efectuada. ');
					return Redirect::to('suscripcion/listar');
				}
			}
			
			//bneficiarios adicionales
			
			return Redirect::to('suscripcion/listar');
		}

		Session::flash('error', 'La renovación no ha sido efectuada. ');
		return Redirect::to('suscripcion/listar');
	}

	/*
	//renovacion de carnet con modal para editar
	public function postRenovarsuscripcion(Request $request){

		//consulta de datos de suscripcion
		$suscripcion =		
		\DB::table('clu_suscription')
		->select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone','ad.names as names_ad','ad.identificacion as identificacion_ad','ad.code_adviser as code_adviser_ad','clu_state.state','clu_state.alert')
		->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
		->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
		->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
		->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
		->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
		->where('clu_suscription.id', $request->input()['id'])
		->get();

		//advertencia de renovación por renovar con saldo pendiente

		$array['suscripcion'] = $suscripcion;

		if(count($suscripcion)){
			return response()->json(['respuesta'=>true,'data'=>$array]);
		}
		return response()->json(['respuesta'=>true,'data'=>null]);
	}
	*/

	//renovar con actualizaciòn
	public function getRenovarsuscripcion($suscription_id = null,$suscription_mora = null){			

		//preparación de datos
		$adviser = \DB::table('seg_user')
		->join('seg_rol', 'seg_user.rol_id', '=', 'seg_rol.id')
		->join('seg_user_profile','seg_user.id','=','seg_user_profile.user_id')
		->where('rol_id', '=', 4)
		->orwhere('rol_id', '=', 9)
		->where('seg_user.active', '=', 1)
		->get();

		//miramos su tiene saldo en mora			
		foreach ($adviser as $ad){			
			$advisers[$ad->user_id] = $ad->names.' '.$ad->surnames.' ['.$ad->code_adviser.'] '.$ad->identificacion;
		}		
		$moduledata['asesores']=$advisers;		
		
		$departments = \DB::table('clu_department')->get();
		foreach ($departments as $department){
			$departamentos[$department->id] = $department->department;
		}
		$moduledata['departamentos']=$departamentos;
		
		$citys = \DB::table('clu_city')->get();
		foreach ($citys as $city){
			$ciudades[$city->id] = $city->city;
		}
		$moduledata['ciudades']=$ciudades;

		foreach ($citys as $city){
			$ciudades[$city->city] = $city->city;
		}
		$moduledata['ciudades2']=$ciudades;
		
		$suscripcion =
		Suscription::
		select('clu_suscription.*','ufr.*','fr.*','fr.state as department','ad.names as names_ad','ad.surnames as surnames_ad','ad.identificacion as identificacion_ad','clu_state.state')
		->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
		->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')		
		->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
		->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
		->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
		->where('clu_suscription.id', $suscription_id)
		->get()
		->toArray();
		
		//consultamos el departamento
		$department_id = \DB::table('clu_department')
		->where('department',$suscripcion[0]['department'])
		->get()[0]->id;
		
		//consultamos el ciudad
		$city_id = \DB::table('clu_city')
		->where('city',$suscripcion[0]['city'])
		->get();
		
		if(empty($city_id)){
			$city_id = 180;	
		}else{
			$city_id = $city_id[0]->id; 	
		}		
		
		//abonos
		//consultar los pagos de la suscripcción
		$moduledata['pagos'] =
		Payment::
		where('clu_payment.suscription_id', $suscription_id)
		->get()
		->toArray();
		
		//beneficiarios		
		$cnts =
		License::
		where('clu_license.suscription_id', $suscription_id)
		->get()
		->toArray();

		$moduledata['cnts'] = $cnts;
		
		$moduledata['bnes'] =
		Beneficiary::
		where(function ($query) use ($cnts){
			foreach($cnts as $key => $value){
				$query->orwhere('clu_beneficiary.license_id', $value['id']);
			}
		})
		->orderBy('license_id', 'asc')
		->get()
		->toArray();
		
		//estado
		$moduledata['estados']=\DB::table('clu_state')
		->select()
		->get();
			
		foreach ($moduledata['estados'] as $estado){
			$estados[$estado->id] = $estado->state;
		}
		$moduledata['estados']=$estados;
		
		Session::flash('_old_input.nb', 1);
		if(!empty(\DB::table('clu_beneficiary')->max('id'))){
			Session::flash('_old_input.nb', \DB::table('clu_beneficiary')->max('id') + 1);
		}
		Session::flash('_old_input.np', 1);
		if(!empty(\DB::table('clu_payment')->max('id'))){
			Session::flash('_old_input.np', \DB::table('clu_payment')->max('id') + 1);
		}
		Session::flash('_old_input.user_id',  $suscripcion[0]['user_id']);
		Session::flash('_old_input.name',  $suscripcion[0]['name']);
		Session::flash('_old_input.names',  $suscripcion[0]['names']);
		Session::flash('_old_input.surnames',  $suscripcion[0]['surnames']);
		Session::flash('_old_input.identification',  $suscripcion[0]['identificacion']);
		Session::flash('_old_input.type_id',  $suscripcion[0]['type_id']);
		Session::flash('_old_input.civil_status_suscriptor',  $suscripcion[0]['civil_status']);
		Session::flash('_old_input.email',  $suscripcion[0]['email']);
		Session::flash('_old_input.sex',  $suscripcion[0]['sex']);
		Session::flash('_old_input.birthdate',  $suscripcion[0]['birthdate']);
		Session::flash('_old_input.birthplace',  $suscripcion[0]['birthplace']);
		Session::flash('_old_input.adress',  $suscripcion[0]['adress']);		
		Session::flash('_old_input.state',  $department_id);
		Session::flash('_old_input.city',  $city_id);
		Session::flash('_old_input.neighborhood',  $suscripcion[0]['neighborhood']);
		Session::flash('_old_input.home',  $suscripcion[0]['home']);
		Session::flash('_old_input.movil_number',  $suscripcion[0]['movil_number']);
		Session::flash('_old_input.fix_number',  $suscripcion[0]['fix_number']);
		Session::flash('_old_input.date_suscription',  $suscripcion[0]['date_suscription']);
		Session::flash('_old_input.date_expiration',  $suscripcion[0]['date_expiration']);
		Session::flash('_old_input.paymentadress',  $suscripcion[0]['paymentadress']);
		Session::flash('_old_input.profession',  $suscripcion[0]['profession']);
		Session::flash('_old_input.reference',  $suscripcion[0]['reference']);
		Session::flash('_old_input.reference_phone',  $suscripcion[0]['reference_phone']);
		Session::flash('_old_input.code',  $suscripcion[0]['code']);
		Session::flash('_old_input.waytopay',  $suscripcion[0]['waytopay']);
		Session::flash('_old_input.price', $suscripcion[0]['price']);
		Session::flash('_old_input.pay_interval',  $suscripcion[0]['pay_interval']);
		Session::flash('_old_input.fee',  $suscripcion[0]['fee']);
		Session::flash('_old_input.provisional',  $suscripcion[0]['reason']);
		Session::flash('_old_input.state_id',  $suscripcion[0]['state_id']);
		Session::flash('_old_input.observation',  $suscripcion[0]['observation']);
		Session::flash('_old_input.adviser',  $suscripcion[0]['names_ad'].' '.$suscripcion[0]['surnames_ad'].' '.$suscripcion[0]['identificacion_ad']);		
		
		Session::flash('_old_input.suscription_id', $suscription_id);
		Session::flash('_old_input.modulo_id', 7);
		Session::flash('_old_input.edit', 'true');
		Session::flash('_old_input.renovar', 'true');
		Session::flash('titulo', 'Renovar');
		Session::flash('_old_input.mora', $suscription_mora);

		if(intval($suscription_mora)>0){
			Session::flash('alert', 'La Suscripciòn tiene saldo en mora, si se renueva el saldo en mora se abonara al precio de la renovación. Saldo en mora: '.$suscription_mora);
		}

		
		return Redirect::to('suscripcion/agregar')->with('modulo',$moduledata);		
	}
	
	//consultamos los carnet de la suscripción y sus beneficiarios
	public function postCarnet(Request $request){
		
		$array = array();
				
		//beneficiarios
		$cnts =
		License::
		where('clu_license.suscription_id', $request->input()['id'])
		->get()
		->toArray();
		
		$array['cnts'] = $cnts;
		
		$array['bnes'] =
		Beneficiary::
		where(function ($query) use ($cnts){
			foreach($cnts as $key => $value){
				$query->orwhere('clu_beneficiary.license_id', $value['id']);
			}
		})
		->orderBy('license_id', 'asc')
		->get()
		->toArray();
		
		if(!empty($array))return response()->json(['respuesta'=>true,'data'=>$array]);
		
		return response()->json(['respuesta'=>true,'data'=>null]);
	}
	
	//no esta en uso
	public function getCarnetprint($suscription_id = null){
		$array = array();
		$array['suscription'] = Suscription::where('clu_suscription.id', $suscription_id)
		->select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone')
		->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
		->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
		->get()[0]->toArray();
		
		//beneficiarios
		$cnts =
		License::
		where('clu_license.suscription_id', $suscription_id)
		->get()
		->toArray();
		
		$array['cnts'] = $cnts;
		
		$array['bnes'] =
		Beneficiary::
		where(function ($query) use ($cnts){
			foreach($cnts as $key => $value){
				$query->orwhere('clu_beneficiary.license_id', $value['id']);
			}
		})
		->orderBy('license_id', 'asc')
		->get()
		->toArray();
		
		//return view('license.suscription')->with('array',$array);
		$pdf = \PDF::loadView('license.suscription',$array);
		return $pdf->download(''.$array['suscription']['code'].'.pdf');		
	}
	
	public function postCarnetprint(Request $request){
		
		$array = array();
		$array['suscription'] = Suscription::where('clu_suscription.id', $request->input()['suscription_id'])
		->select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone')
		->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
		->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
		->get()[0]->toArray();
		
		$checkbox = Array();
		foreach($request->input() as $key=>$value){
			if(strpos($key,'cnt_') !== false) $checkbox[$key] = $value;			
		}
				
		$cnts =
		License::
		where('clu_license.suscription_id', $request->input()['suscription_id'])
		->where(function ($query) use ($checkbox){
			foreach($checkbox as $key => $value){
				$query->orwhere('clu_license.id', $value);
			}
		})
		->get()
		->toArray();
		
		$array['cnts'] = $cnts;
		
		$array['bnes'] =
		Beneficiary::
		where(function ($query) use ($cnts){
			foreach($cnts as $key => $value){
				$query->orwhere('clu_beneficiary.license_id', $value['id']);
			}
		})
		->orderBy('license_id', 'asc')
		->get()
		->toArray();
		
		//reimpresion de carnet
		if(array_key_exists('reimpresion',$request->input())){
			
			$cnt_prn = new LicensePrint();		
			$cnt_prn->price = env('PRICE_LICENSE',5000) * count($cnts);
			$cnt_prn->date = date("Y-m-d H:i:s");			
			$cnt_prn->description = 'Carnets reimpresos: '.count($cnts).' Beneficiarios: '.count($array['bnes']);
			$cnt_prn->suscription_id = $request->input()['suscription_id'];
			$cnt_prn->save();
		}
		
		$pdf = \PDF::loadView('license.suscription',$array);
		return $pdf->download(''.$array['suscription']['code'].'.pdf');		
	}
	
	//para consultar las reimpresiones realizadad
	public function postCarnetreprint(Request $request){
		
		$array = array();
		
		//reimpresiones
		$cnts_print =
		LicensePrint::
		where('clu_license_print.suscription_id', $request->input()['id'])
		->get()
		->toArray();
		
		$array['cnts_print'] = $cnts_print;
			
		if(!empty($array))return response()->json(['respuesta'=>true,'data'=>$array]);
		
		return response()->json(['respuesta'=>true,'data'=>null]);
	}
	
	//para actualizar las reimpresiones realizadas
	public function postCarnetprintedit(Request $request){
		
		//estas opciones se usaran para pintar las opciones adecuadamente con respecto al modulo
		$moduledata['modulo'] = 'suscripcion';
		$moduledata['id_app'] = 2;
		$moduledata['categoria'] = 'Componentes';
		$moduledata['id_mod'] = 7;
		
		$moduledata['fillable'] = ['N° Contrato','Suscriptor','Municipio','Asesor','Saldo','Abonos','Estado','Próximo abono','Fecha Vencimiento'];
		if(Session::get('opaplus.usuario.rol_id') == 4){
			$moduledata['fillable'] = ['N° Contrato','suscriptor','Identificación','Fecha Vencimiento'];
		}
			
		Session::flash('modulo', $moduledata);
		
		//preperacion de datos
		$checkbox = Array();
		foreach($request->input() as $key=>$value){
			if(strpos($key,'cnt_print_') !== false) $checkbox[$key] = (int)$value;
		}
		
		//consultamos todos las reimpresiones
		$cnts_print =
		LicensePrint::
		where('clu_license_print.suscription_id', $request->input()['suscription_id'])
		->get()
		->toArray();
				
		//Borramos los que no estan en checkbox
		foreach($cnts_print as $cntp){
			if(!in_array($cntp['id'],$checkbox)){
				LicensePrint::where('clu_license_print.id', $cntp['id'])->delete();
			}
		}
		
		Session::flash('message', 'Reimpresiones actualizadas adecuadamente.');
		return Redirect::to('suscripcion/listar');
	}

	public function getCarnets($datos = null){
		//creación de array de id de suscripción		
		$ids = explode('_',$datos);
		
		$array = array();
		$i=0;
		foreach($ids as $key=>$value){
			$array[$i]['suscription'] = Suscription::where('clu_suscription.id', (int)$value)
			->select('clu_suscription.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','fr.type_id','ufr.email','fr.movil_number','fr.fix_number','fr.birthdate','fr.birthplace','fr.sex','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.profession','fr.paymentadress','fr.reference','fr.reference_phone')
			->leftjoin('seg_user as ufr', 'clu_suscription.friend_id', '=', 'ufr.id')
			->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
			->get()[0]->toArray();
			
			$cnts =
			License::
			where('clu_license.suscription_id', $value)
			->get()
			->toArray();
			$array[$i]['cnts'] = $cnts;
			
			$array[$i]['bnes'] =
			Beneficiary::
			where(function ($query) use ($cnts){
				foreach($cnts as $key => $value){
					$query->orwhere('clu_beneficiary.license_id', $value['id']);
				}
			})
			->orderBy('license_id', 'asc')
			->get()
			->toArray();
			
			$i++;
		}
		$array['data'] = $array;
		$pdf = \PDF::loadView('license.suscriptions',$array);
		return $pdf->download('Carnest'.date("Y-m-d H:i:s").'.pdf');
	}

	public function postCargasus(Request $request){

		$moduledata['modulo'] = 'suscripcion';
		$moduledata['id_app'] = '2';
		$moduledata['categoria'] = 'Componentes';
		$moduledata['id_mod'] = '7';

		$moduledata['fillable'] = ['N° Contrato','Suscriptor','Municipio','Asesor','Saldo','Abonos','Estado','Próximo abono','Fecha Vencimiento'];
		if(Session::get('opaplus.usuario.rol_id') == 4){
			$moduledata['fillable'] = ['N° Contrato','suscriptor','Identificación','Fecha Vencimiento'];
		}


		$mimeTypes = [
		'application/csv',
		'application/excel',
		'application/vnd.ms-excel',
		'application/vnd.msexcel',
		'text/csv',
		'text/anytext',
		'text/plain',
		'text/x-c',
		'text/csv',
		'csv',
		'txt',
		'application/octet-stream',
		'text/comma-separated-values',
		'inode/x-empty',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'application/vnd.oasis.opendocument.spreadsheet',			
		];

		$file = request()->hasFile('carga_suscripcion');
		if ($file) {
			$file = array('carga_suscripcion' => Input::file('carga_suscripcion'));
			if (!in_array(request()->file('carga_suscripcion')->getClientMimeType(), $mimeTypes)) {
				Session::flash('error', 'Archivo no se puede cargar, no es un archivo valido.');
				return Redirect::to('suscripcion/listar')->with('modulo',$moduledata);;					
			}
			$message = array();
			if ($file['carga_suscripcion']->isValid()) {

				if($file['carga_suscripcion']->getClientSize() < 2097152){
					//.xls que se pueden cargar
					
					\Excel::load($file['carga_suscripcion'], function($results)  use(&$message) {

					//\Excel::filter('chunk')->load($file['carga_suscripcion']->getPathname(),'UTF-8', true)->noHeading()->formatDates(true, 'Y-m-d')->chunk(200, function($results) use(&$message, &$moduledata){				
						

						$moduledata['fillable'] = ['N° Contrato','Suscriptor','Municipio','Asesor','Saldo','Abonos','Estado','Próximo abono','Fecha Vencimiento'];
						if(Session::get('opaplus.usuario.rol_id') == 4){
							$moduledata['fillable'] = ['N° Contrato','suscriptor','Identificación','Fecha Vencimiento'];
						}
						$usuarios = array();	
						$usuarios_borrar = array();	
						$abonos = array();		
						
						//foreach($results as $hoja){
						foreach($results->get() as $hoja){							
							// Creamos el array							
							foreach($hoja as $row){

								if($hoja->getTitle() == 'CARGAR'){
									//suacripciones								

									$fecha_nacimiento = ((25569 + ((((string)$row->fecha_de_nacimiento - 25569) * 86400) / 86400)) - 25569) * 86400;
									$inicio_suscripcion = ((25569 + ((((string)$row->fecha_de_suscripcion - 25569) * 86400) / 86400)) - 25569) * 86400;
									$fin_suscripcion = ((25569 + ((((string)$row->fecha_de_terminacion - 25569) * 86400) / 86400)) - 25569) * 86400;
									
									if(!array_key_exists((string)$row->cedula,$usuarios) && !empty($row->cedula)){
										$usuarios[$row->cedula] = array(
											'name_sucriptor'=>(string)$row->cedula,
											'email_suscriptor'=>$row->cedula.'@yopmail.com',
											'user_profile_names' => $row->nombre_titular,
											'user_profile_birthplace' => $row->de,								
											'user_profile_birthdate' => gmdate("Y-m-d", $fecha_nacimiento),
											'user_profile_adress' => $row->direccion_de_cobro,
											'user_profile_city' => $row->ciudad_c,								
											'user_profile_neighborhhod' => $row->barrio_c,
											'user_profile_fix_number' => (string)$row->tel_trabajo.' - '.(string)$row->telefono_res,
											'user_profile_movil_number' => (string)$row->celular,								
											'suscription_description' => $row->contrato,
											'suscription_code' => $row->contrato,
											'suscription_date_suscription' => gmdate("Y-m-d", $inicio_suscripcion),
											'suscription_date_expiration' => gmdate("Y-m-d", $fin_suscripcion),
											'suscription_bne1' => $row->nombre_gf1,
											'suscription_bne2' => $row->nombre_gf2,
											'suscription_bne3' => $row->nombre_gf3,
											'suscription_bne4' => $row->nombre_gf4,
											'suscription_bne5' => $row->nombre_gf5,
											'suscription_bne6' => $row->nombre_gf6,
											'suscription_bne7' => $row->nombre_gf7
										);
									}else{
										$message[] = ' Cedula repetida: '.$row->cedula.'.';
									}
								}

								if($hoja->getTitle() == 'DESCARGAR'){
									
									if(!array_key_exists($row->cedula,$usuarios_borrar)){
										$usuarios_borrar[$row->cedula] = array(
											'name_sucriptor'=>$row->cedula
										);
									}else{
										$message[] = ' Cedula repetida: '.$row->cedula.'.';
									}
								}

								if($hoja->getTitle() == 'PAGOS'){
									//verificamos que si tenga el numero_suscripcion									
									if($row->contrato){
										$abonos[]=array(
											'contrato'=>$row->contrato,
											'date_payment'=>$row->fecha_pago,											
											'payment'=>$row->pago,
											'n_receipt'=>$row->numero_recibo
										);
									}
									
								}						
							}
						}
						//dd($usuarios);
						$nro_suscription = 0; 
						$nro_descarga = 0; 
						$nro_abonos = 0;
						$carnet = null;
						foreach ($usuarios as $key => $value) {
								
							$user = new User();
							$user->name = $value['name_sucriptor'];
							$user->email = $value['email_suscriptor'];
							$user->password = '0000';
							$user->active = 1;
							$user->ip = 0;
							$user->rol_id = 3;								

							try {
								$user->save();
							}catch (\Illuminate\Database\QueryException $e) {
								$message[] = ' La suscripciòn no se logro cargar '.$user->name.'.';					
							}
							
							if($user->id){					
								//se ha guardado con exito el usuario
								$userprofile = new UserProfile();
								$userprofile->identificacion = $value['name_sucriptor'];
								$userprofile->type_id = 'CEDULA CIUDADANIA';
								$userprofile->names = $value['user_profile_names'];
								$userprofile->birthdate = $value['user_profile_birthdate'];
								$userprofile->birthplace = $value['user_profile_birthplace'];
								//$userprofile->sex = null;
								//$userprofile->civil_status = 'NULL';
								$userprofile->state = 'Antioquia';
								$userprofile->adress = $value['user_profile_adress'];
								$userprofile->city = $value['user_profile_city'];								
								//$userprofile->home = null;
								$userprofile->neighborhood = $value['user_profile_neighborhhod'];
								$userprofile->avatar = 'default.png';
								$userprofile->description = 'default';
								$userprofile->template = 'default';									
								$userprofile->movil_number = $value['user_profile_movil_number'];
								$userprofile->fix_number = $value['user_profile_fix_number'];
								//$userprofile->date_start = null;
								//$userprofile->code_adviser = null;
								//$userprofile->zone = null;
								$userprofile->user_id = $user->id;

								if(empty($value['user_profile_movil_number'])){
									$userprofile->movil_number = 0;
									if($value['user_profile_fix_number'] == ' - ' || $value['user_profile_fix_number'] == '0 - 0'){								
										$message[] = ' El siguiente perfil no tiene Telefonos '.$userprofile->identificacion.'.';
									}										
								}
								
								try {
									$userprofile->save();
								}catch (\Illuminate\Database\QueryException $e) {
									$message[] = ' El siguiente perfil no se logro cargar '.$userprofile->identificacion.'.';							
								}

								//guardamos la suscripciòn
								$suscription = new Suscription();
								$suscription->code = $value['suscription_code'];
								$suscription->date_suscription = $value['suscription_date_suscription'];
								$suscription->date_expiration = $value['suscription_date_expiration'];
								$suscription->price =  env('PRICE_SUSCRIPTION',135000);
								$suscription->waytopay = 'EFECTIVO';
								$suscription->pay_interval = date ( 'Y-m-d' , strtotime ( '+1 month' , strtotime ( date('Y-m-j'))));
								$suscription->reason = $value['suscription_description'];//N provisional
								$suscription->adviser_id = 60;//para producciòn es 60 UNION
								$suscription->friend_id = $user->id;
								$suscription->state_id = 2;
								
								try {
									$suscription->save();
									$nro_suscription++;
								}catch (\Illuminate\Database\QueryException $e) {
									$message[] = ' LA SIGUIENTE SUSCRIPCON no se logro cargar '.$userprofile->identificacion.'.';
								}

								//creamos el carnet
								$carnet = new License();
								$carnet->type = 'suscription';
								$carnet->price = 0;
								$carnet->date = $value['suscription_date_suscription'];
								$carnet->suscription_id = $suscription->id;

								try {
									$carnet->save();
									
								}catch (\Illuminate\Database\QueryException $e) {
									$message[] = ' El siguiente carnet no se logro cargar '.$userprofile->identificacion.'.';
								}

							}else{
								//$message[] = 'No entra, Ya esta en la base de datos:'. $value['name_sucriptor'].'.';
							}

							//BENEFICIARIOS
							if($carnet != null){
								
								if(!empty($value['suscription_bne1'])){
									$beneficiario = new Beneficiary();
									$beneficiario->names = $value['suscription_bne1'];//explode(' ',$value['suscription_bne1'])[0];
									$beneficiario->surnames = '.';
									$beneficiario->state= 'Pago por suscripción';
									$beneficiario->alert= '#dff0d8';
									$beneficiario->price= 0;
									$beneficiario->license_id= $carnet->id;
									try {
										$beneficiario->save();
									}catch (\Illuminate\Database\QueryException $e) {
										$message[] = ' El siguiente beneficiario no se logro cargar '.$beneficiario->names .'.';
									}
								}
								if(!empty($value['suscription_bne2'])){
									$beneficiario = new Beneficiary();
									$beneficiario->names = $value['suscription_bne2'];//explode(' ',$value['suscription_bne2'])[0];
									$beneficiario->surnames = '.';
									$beneficiario->state= 'Pago por suscripción';
									$beneficiario->alert= '#dff0d8';
									$beneficiario->price= 0;
									$beneficiario->license_id= $carnet->id;
									try {
										$beneficiario->save();
									}catch (\Illuminate\Database\QueryException $e) {
										$message[] = ' El siguiente beneficiario no se logro cargar '.$beneficiario->names .'.';
									}
								}
								if(!empty($value['suscription_bne3'])){
									$beneficiario = new Beneficiary();
									$beneficiario->names = $value['suscription_bne3'];//explode(' ',$value['suscription_bne3'])[0];
									$beneficiario->surnames = '.';
									$beneficiario->state= 'Pago por suscripción';
									$beneficiario->alert= '#dff0d8';
									$beneficiario->price= 0;
									$beneficiario->license_id= $carnet->id;
									try {
										$beneficiario->save();
									}catch (\Illuminate\Database\QueryException $e) {
										$message[] = ' El siguiente beneficiario no se logro cargar '.$beneficiario->names .'.';
									}
								}
								if(!empty($value['suscription_bne4'])){
									$beneficiario = new Beneficiary();
									$beneficiario->names = $value['suscription_bne4'];//explode(' ',$value['suscription_bne4'])[0];
									$beneficiario->surnames = '.';
									$beneficiario->state= 'Pago por suscripción';
									$beneficiario->alert= '#dff0d8';
									$beneficiario->price= 0;
									$beneficiario->license_id= $carnet->id;
									try {
										$beneficiario->save();
									}catch (\Illuminate\Database\QueryException $e) {
										$message[] = ' El siguiente beneficiario no se logro cargar '.$beneficiario->names .'.';
									}
								}
								if(!empty($value['suscription_bne5'])){
									$beneficiario = new Beneficiary();
									$beneficiario->names = $value['suscription_bne5'];//explode(' ',$value['suscription_bne5'])[0];
									$beneficiario->surnames = '.';
									$beneficiario->state= 'Pago por suscripción';
									$beneficiario->alert= '#dff0d8';
									$beneficiario->price= 0;
									$beneficiario->license_id= $carnet->id;
									try {
										$beneficiario->save();
									}catch (\Illuminate\Database\QueryException $e) {
										$message[] = ' El siguiente beneficiario no se logro cargar '.$beneficiario->names .'.';
									}
								}
								if(!empty($value['suscription_bne6'])){
									$beneficiario = new Beneficiary();
									$beneficiario->names = $value['suscription_bne6'];//explode(' ',$value['suscription_bne6'])[0];
									$beneficiario->surnames = '.';
									$beneficiario->state= 'Pago por suscripción';
									$beneficiario->alert= '#dff0d8';
									$beneficiario->price= 0;
									$beneficiario->license_id= $carnet->id;
									try {
										$beneficiario->save();
									}catch (\Illuminate\Database\QueryException $e) {
										$message[] = ' El siguiente beneficiario no se logro cargar '.$beneficiario->names .'.';
									}
								}
								if(!empty($value['suscription_bne7'])){
									$beneficiario = new Beneficiary();
									$beneficiario->names = $value['suscription_bne7'];//explode(' ',$value['suscription_bne7'])[0];
									$beneficiario->surnames = '.';
									$beneficiario->state= 'Pago por suscripción';
									$beneficiario->alert= '#dff0d8';
									$beneficiario->price= 0;
									$beneficiario->license_id= $carnet->id;
									try {
										$beneficiario->save();
									}catch (\Illuminate\Database\QueryException $e) {
										$message[] = ' El siguiente beneficiario no se logro cargar '.$beneficiario->names .'.';
									}
								}
								
							}
													
						}

						foreach ($usuarios_borrar as $key => $value) {
							$user= User::where('name', $key)->first();
							if($user != null){
								try {
									$user->delete();
									$nro_descarga++;
								}catch (\Illuminate\Database\QueryException $e) {
									$message[] = ' El usuario no se logro borrar '.$key.'.';					
								}
								
							}
									
						}

						foreach ($abonos as $key => $value) {
							//consultamos las suscripciones							
							$suscripcion= Suscription::where('code', $value['contrato'])->first();							
							if($suscripcion != null){
								//hay suscripciòn
								$abono = new Payment($value);
								$abono->suscription_id = $suscripcion->id;								
								//preguntamos por el numero de recivo
								$abono_base= Payment::where('n_receipt', $value['n_receipt'])->first();
								if( $abono_base == null){
									//si se puede guardar
									try {										
										$abono->save();
										$nro_abonos++;
									}catch (\Illuminate\Database\QueryException $e) {
										$message[] = ' El siguiente abono no se logro cargar '. $key .'.';
									}
								}else{
									$message[] = ' El siguiente abono ya existe '. $key .'. Nùmero recibo: '.$value['n_receipt'].'.';
								}

							}
						}

						Session::flash('messageup', $message);
						Session::flash('modulo', $moduledata);

						$message[] = ' Las suscripciones cargadas fueròn: '.$nro_suscription;
						$message[] = ' Las suscripciones descargadas fueròn: '.$nro_descarga;
						$message[] = ' Abonos cargados fueròn: '.$nro_abonos;
						//return Redirect::to('suscripcion/listar')->with('modulo',$moduledata);	
						//return Redirect::back()->with('modulo',$moduledata);
					});				
					
				}else{
					$message[] = ' Archivo no se puede cargar, no es un archivo valido, es demaciado grande.';
					Session::flash('errorup', $message);
					Session::flash('modulo', $moduledata);
					return Redirect::to('suscripcion/listar')->with('modulo',$moduledata);
					//return Redirect::back()->with('modulo',$moduledata);
				}

				$message[] = ' La carga ha sido efectuada correctamente.';

				Session::flash('messageup', $message);
				Session::flash('modulo', $moduledata);
				return Redirect::to('suscripcion/listar')->with('modulo',$moduledata);
				//return Redirect::back()->with('modulo',$moduledata);

			}		
		}

		$message[] = ' La carga no ha sido efectuada, ya que no se selecciono un archivo de carga.';
		Session::flash('errorup', $message);
		Session::flash('modulo', $moduledata);
		return Redirect::to('suscripcion/listar')->with('modulo',$moduledata);
		//return Redirect::back()->with('modulo',$moduledata);
	}
	
	public function postConsultarcity(Request $request){		
		$city =
		City::
		where('clu_city.department_id', $request->input()['id'])
		->get()
		->toArray();
		
		if(count($city)){
			return response()->json(['respuesta'=>true,'data'=>$city]);
		}
		return response()->json(['respuesta'=>true,'data'=>null]);
	}
	
	public function getBeneficiarios($id_app=null,$categoria=null,$id_mod=null){
		
		return redirect()->action('Club\BeneficiaryController@getEnumerar', ['id_app' => $id_app, 'categoria'=>$categoria, 'id_mod'=>$id_mod]);
	}	
	
}
