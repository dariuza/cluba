<?php namespace App\Http\Controllers\Club;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\Club\Beneficiary;
use App\Core\Club\PaymentBeneficiary;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
//use Illuminate\Support\Facades\Input;

class BeneficiaryController extends Controller {
	
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
		
		//consultas de beneficiarios
		//total
		try {
			$moduledata['total_beneficiarios']=\DB::table('clu_beneficiary')
			->select(\DB::raw('count(*) as total'))
			->get()[0]->total;
		}catch (ModelNotFoundException $e) {
			$message = 'Problemas al hallar datos de '.$modulo;
			return Redirect::to('beneficiario/general')->with('error', $message);
		}
		
		return Redirect::to('beneficiario/general')->with('modulo',$moduledata);
	}
	public function getGeneral(){
		if(is_null(Session::get('modulo.id'))) return Redirect::to('/')->with('error', 'Este modulo no se debe alcanzar por url, solo es valido desde las opciones del menú');
		return view('club.beneficiario.beneficiario_index');
	}
	
	public function getEnumerar($id_app=null,$categoria=null,$id_mod=null){		
		if(is_null($id_mod)) return Redirect::to('/')->with('error', 'Este modulo no se debe alcanzar por url, solo es valido desde las opciones del menú');
		return Redirect::to('beneficiario/listar');
	}
	public function getListar(){
		//las siguientes dos lineas solo son utiles cuando se refresca la pagina, ya que al refrescar no se pasa por el controlador
		$moduledata['fillable'] = ['Beneficiario','Titular','Parentesco','Celular','Celular Titular'];
		//recuperamos las variables del controlador anterior ante el echo de una actualización de pagina
		$url = explode("/", Session::get('_previous.url'));
		
		//estas opciones se usaran para pintar las opciones adecuadamente con respecto al modulo		
		$moduledata['modulo'] = 'beneficiario';
		$moduledata['id_app'] = '2';
		$moduledata['categoria'] = 'Componentes';
		$moduledata['id_mod'] = '8';
		
		Session::flash('modulo', $moduledata);
		
		return view('club.beneficiario.listar');
	}
	public function getListarajax(Request $request){
		//otros parametros
		$moduledata['total']=Beneficiary::count();
		
		$order_column = 'names';
		$order_dir = 'desc';		
		if(!empty($request->input('columns'))){
			$order_column = $request->input('columns')[$request->input('order')[0]['column']]['data'];
			$order_dir = $request->input('order')[0]['dir'];
			
			if($order_column == 'beneficiario')$order_column = 'names';
			if($order_column == 'suscriptor')$order_column = 'names_fr';
			
		}
		
		Session::put('inputs_bne',$request->input());
		//realizamos la consulta
		if(!empty($request->input('search')['value'])){
			
			Session::flash('search', $request->input('search')['value']);
			
			if(Session::get('opaplus.usuario.rol_id') == 1 || Session::get('opaplus.usuario.rol_id') == 2 || Session::get('opaplus.usuario.rol_id') == 9){
				
				$moduledata['beneficiarios']=\DB::table('clu_beneficiary')
				->select('clu_beneficiary.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email','fr.movil_number as fr_movil','fr.fix_number','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.paymentadress','fr.reference','fr.reference_phone')
				->leftjoin('clu_license as ls', 'clu_beneficiary.license_id', '=', 'ls.id')
				->leftjoin('clu_suscription as ss', 'ls.suscription_id', '=', 'ss.id')
				->leftjoin('seg_user as ufr', 'ss.friend_id', '=', 'ufr.id')
				->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
				->where(function ($query) {
					$query->where('clu_beneficiary.identification', 'like', '%'.Session::get('search').'%')
					->orWhere('clu_beneficiary.state', 'like', '%'.Session::get('search').'%')
					->orWhere('clu_beneficiary.names', 'like', '%'.Session::get('search').'%')
					->orWhere('clu_beneficiary.surnames', 'like', '%'.Session::get('search').'%')
					->orWhere('fr.identificacion', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.names', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.surnames', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.state', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.city', 'like',  '%'.Session::get('search').'%')
					->orWhere('ss.date_suscription', 'like', '%'.Session::get('search').'%');
				})
				->orderBy($order_column, $order_dir)
				->skip($request->input('start'))->take($request->input('length'))
				->get();
				
			}elseif( Session::get('opaplus.usuario.rol_id') == 3){
				$moduledata['beneficiarios']=\DB::table('clu_beneficiary')
				->select('clu_beneficiary.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email','fr.movil_number as fr_movil','fr.fix_number','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.paymentadress','fr.reference','fr.reference_phone')
				->leftjoin('clu_license as ls', 'clu_beneficiary.license_id', '=', 'ls.id')
				->leftjoin('clu_suscription as ss', 'ls.suscription_id', '=', 'ss.id')
				->leftjoin('seg_user as ufr', 'ss.friend_id', '=', 'ufr.id')
				->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
				->where('ss.friend_id',Session::get('opaplus.usuario.id'))
				->where(function ($query) {
					$query->where('clu_beneficiary.identification', 'like', '%'.Session::get('search').'%')
					->orWhere('clu_beneficiary.state', 'like', '%'.Session::get('search').'%')
					->orWhere('clu_beneficiary.names', 'like', '%'.Session::get('search').'%')
					->orWhere('clu_beneficiary.surnames', 'like', '%'.Session::get('search').'%')
					->orWhere('fr.identificacion', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.names', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.surnames', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.state', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.city', 'like',  '%'.Session::get('search').'%')
					->orWhere('ss.date_suscription', 'like', '%'.Session::get('search').'%');
				})
				->orderBy($order_column, $order_dir)
				->skip($request->input('start'))->take($request->input('length'))
				->get();
				
			}elseif(Session::get('opaplus.usuario.rol_id') == 4){
				$moduledata['beneficiarios']=\DB::table('clu_beneficiary')
				->select('clu_beneficiary.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email','fr.movil_number as fr_movil','fr.fix_number','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.paymentadress','fr.reference','fr.reference_phone')
				->leftjoin('clu_license as ls', 'clu_beneficiary.license_id', '=', 'ls.id')
				->leftjoin('clu_suscription as ss', 'ls.suscription_id', '=', 'ss.id')
				->leftjoin('seg_user as ufr', 'ss.friend_id', '=', 'ufr.id')
				->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
				->where('ss.adviser_id',Session::get('opaplus.usuario.id'))
				->where(function ($query) {
					$query->where('clu_beneficiary.identification', 'like', '%'.Session::get('search').'%')
					->orWhere('clu_beneficiary.state', 'like', '%'.Session::get('search').'%')
					->orWhere('clu_beneficiary.names', 'like', '%'.Session::get('search').'%')
					->orWhere('clu_beneficiary.surnames', 'like', '%'.Session::get('search').'%')
					->orWhere('fr.identificacion', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.names', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.surnames', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.state', 'like',  '%'.Session::get('search').'%')
					->orWhere('fr.city', 'like',  '%'.Session::get('search').'%')
					->orWhere('ss.date_suscription', 'like', '%'.Session::get('search').'%');
				})
				->orderBy($order_column, $order_dir)
				->skip($request->input('start'))->take($request->input('length'))
				->get();
				
			}elseif(Session::get('opaplus.usuario.rol_id') == 5 || Session::get('opaplus.usuario.rol_id') == 6){
				
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
				
					$moduledata['beneficiarios']=\DB::table('clu_beneficiary')
					->select('clu_beneficiary.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email','fr.movil_number as fr_movil','fr.fix_number','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.paymentadress','fr.reference','fr.reference_phone')
					->leftjoin('clu_license as ls', 'clu_beneficiary.license_id', '=', 'ls.id')
					->leftjoin('clu_suscription as ss', 'ls.suscription_id', '=', 'ss.id')
					->leftjoin('seg_user as ufr', 'ss.friend_id', '=', 'ufr.id')
					->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
					->where(function($q) use ($ciudades){
						foreach($ciudades as $key => $value){
							$q->orwhere('fr.city', '=', $value);
						}
					})
					->where(function ($query) {
						$query->where('clu_beneficiary.identification', 'like', '%'.Session::get('search').'%')
						->orWhere('clu_beneficiary.state', 'like', '%'.Session::get('search').'%')
						->orWhere('clu_beneficiary.names', 'like', '%'.Session::get('search').'%')
						->orWhere('clu_beneficiary.surnames', 'like', '%'.Session::get('search').'%')
						->orWhere('fr.identificacion', 'like',  '%'.Session::get('search').'%')
						->orWhere('fr.names', 'like',  '%'.Session::get('search').'%')
						->orWhere('fr.surnames', 'like',  '%'.Session::get('search').'%')
						->orWhere('fr.state', 'like',  '%'.Session::get('search').'%')	
						->orWhere('fr.city', 'like',  '%'.Session::get('search').'%')
						->orWhere('ss.date_suscription', 'like', '%'.Session::get('search').'%');
					})
					->orderBy($order_column, $order_dir)
					->skip($request->input('start'))->take($request->input('length'))
					->get();
						
				}else{
					//dd('vacio');
					$moduledata['beneficiarios'];
				}
				
			}			
			
			$moduledata['filtro'] = count($moduledata['beneficiarios']);			
			
		}else{	
			if(Session::get('opaplus.usuario.rol_id') == 1 || Session::get('opaplus.usuario.rol_id') == 2 || Session::get('opaplus.usuario.rol_id') == 9){
				//el administrador tien acceso total
				$moduledata['beneficiarios']=\DB::table('clu_beneficiary')
				->select('clu_beneficiary.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email','fr.movil_number as fr_movil','fr.fix_number','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.paymentadress','fr.reference','fr.reference_phone')
				->leftjoin('clu_license as ls', 'clu_beneficiary.license_id', '=', 'ls.id')
				->leftjoin('clu_suscription as ss', 'ls.suscription_id', '=', 'ss.id')
				->leftjoin('seg_user as ufr', 'ss.friend_id', '=', 'ufr.id')
				->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')				
				->orderBy($order_column, $order_dir)
				->skip($request->input('start'))->take($request->input('length'))
				->get();
				
				
			}elseif(Session::get('opaplus.usuario.rol_id') == 3){
				//el amigo solo puede ver sus beneficiarios
				$moduledata['beneficiarios']=\DB::table('clu_beneficiary')
				->select('clu_beneficiary.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email','fr.movil_number as fr_movil','fr.fix_number','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.paymentadress','fr.reference','fr.reference_phone')
				->leftjoin('clu_license as ls', 'clu_beneficiary.license_id', '=', 'ls.id')
				->leftjoin('clu_suscription as ss', 'ls.suscription_id', '=', 'ss.id')
				->leftjoin('seg_user as ufr', 'ss.friend_id', '=', 'ufr.id')
				->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
				->where('ss.friend_id',Session::get('opaplus.usuario.id'))				
				->orderBy($order_column, $order_dir)
				->skip($request->input('start'))->take($request->input('length'))				
				->get();
				
				
			}elseif(Session::get('opaplus.usuario.rol_id') == 4){
				//el asesor solo puede ver los beneficiarios de las suscripciones adscritas a el			
				$moduledata['beneficiarios']=\DB::table('clu_beneficiary')
				->select('clu_beneficiary.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email','fr.movil_number as fr_movil','fr.fix_number','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.paymentadress','fr.reference','fr.reference_phone')
				->leftjoin('clu_license as ls', 'clu_beneficiary.license_id', '=', 'ls.id')
				->leftjoin('clu_suscription as ss', 'ls.suscription_id', '=', 'ss.id')
				->leftjoin('seg_user as ufr', 'ss.friend_id', '=', 'ufr.id')
				->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
				->where('ss.adviser_id',Session::get('opaplus.usuario.id'))
				->orderBy($order_column, $order_dir)
				->skip($request->input('start'))->take($request->input('length'))
				->get();				
				
				
			}elseif(Session::get('opaplus.usuario.rol_id') == 5 || Session::get('opaplus.usuario.rol_id') == 6){
				//asesor financiero y jefe de area
				//solo ven los beneficiarios de sus zonas
				
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
				
					$moduledata['beneficiarios']=\DB::table('clu_beneficiary')
					->select('clu_beneficiary.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email','fr.movil_number as fr_movil','fr.fix_number','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.paymentadress','fr.reference','fr.reference_phone')
					->leftjoin('clu_license as ls', 'clu_beneficiary.license_id', '=', 'ls.id')
					->leftjoin('clu_suscription as ss', 'ls.suscription_id', '=', 'ss.id')
					->leftjoin('seg_user as ufr', 'ss.friend_id', '=', 'ufr.id')
					->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
					->where(function($q) use ($ciudades){
						foreach($ciudades as $key => $value){
							$q->orwhere('fr.city', '=', $value);
						}
					})
					->orderBy($order_column, $order_dir)
					->skip($request->input('start'))->take($request->input('length'))
					->get();					
					
				}else{
					//dd('vacio');
					$moduledata['beneficiarios'];
				}				
			}
			
			$moduledata['filtro'] = $moduledata['total'];
			
		}
		
		
		foreach($moduledata['beneficiarios'] as $beneficiario){
			$beneficiario->suscriptor = $beneficiario->names_fr.' '.$beneficiario->surnames_fr;
			$beneficiario->beneficiario = $beneficiario->names.' '.$beneficiario->surnames;
		}
		
		
		return response()->json(['draw'=>$request->input('draw')+1,'recordsTotal'=>$moduledata['total'],'recordsFiltered'=>$moduledata['filtro'],'data'=>$moduledata['beneficiarios']]);
		
	}
	
	//Función para la opción: agregar
	public function getCrear($id_app=null,$categoria=null,$id_mod=null){	
		//Modo de evitar que otros roles ingresen por la url
		if(is_null($id_mod)) return Redirect::to('/')->with('error', 'Este modulo no se puede alcanzar por url, solo es valido desde las opciones del menú');
			
		return Redirect::to('beneficiario/agregar');
	}
	public function getAgregar(){
		
		return view('club.beneficiario.agregar');
	}
	//función para guardar usuarios con su perfil
	public function postSave(Request $request){
		
		$array_input = array();
		$array_input['_token'] = $request->input('_token');		
		foreach($request->input() as $key=>$value){
			if($key != "_token"){
				$array_input[$key] = strtoupper($value);
			}
		}
		$request->replace($array_input);
		
		$messages = [
			'required' => 'El campo :attribute es requerido.',				
		];
		
		$rules = array(
			'names'    => 'required',
			'surnames' => 'required',
			//'titular_id' => 'required',
		);
		
		$validator = Validator::make($request->input(), $rules, $messages);
		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}else{
			
			$beneficiario = new Beneficiary();
			
			$beneficiario->identification = $request->input()['identification'];
			$beneficiario->type_id = $request->input()['type_id'];
			$beneficiario->names = $request->input()['names'];
			$beneficiario->surnames = $request->input()['surnames'];
			$beneficiario->relationship = $request->input()['relationship'];
			$beneficiario->movil_number = $request->input()['movil_number'];
			$beneficiario->civil_status = $request->input()['civil_status'];
			
			/*
			$array = explode(" ",$request->input()['titular_id']);
			$identification = end($array);					
			*/		
			if($request->input()['edit']){
				
				if(!empty($request->input()['beneficiary_id'])){					
					
					
					$bneAffectedRows = Beneficiary::where('id', $request->input()['beneficiary_id'])->update(
					array(
						'identification' => $beneficiario->identification,
						'type_id' => $beneficiario->type_id,
						'names' => $beneficiario->names,
						'surnames' => $beneficiario->surnames,
						'relationship' => $beneficiario->relationship,
						'movil_number' => $beneficiario->movil_number,
						'civil_status' => $beneficiario->civil_status
						)
						);
					
					Session::flash('_old_input.identification', $beneficiario->identification);
					Session::flash('_old_input.names', $beneficiario->names);
					Session::flash('_old_input.surnames', $beneficiario->surnames);
					Session::flash('_old_input.relationship', $beneficiario->relationship);
					Session::flash('_old_input.movil_number', $beneficiario->movil_number);
					Session::flash('_old_input.civil_status', $beneficiario->civil_status);
					Session::flash('_old_input.state', $request->input()['state']);					
					Session::flash('_old_input.beneficiary_id', $request->input()['beneficiary_id']);
					Session::flash('_old_input.edit', true);
					Session::flash('titulo', 'Editar');
						
					//return Redirect::to('beneficiario/agregar')->withInput()->with('message', 'Beneficiario editado exitosamente')->with('modulo',$moduledata);
					return Redirect::to('beneficiario/agregar')->withInput()->with('message', 'Beneficiario editado exitosamente');
					
				}
				return Redirect::to('beneficiario/agregar')->withInput()->with('error', 'No se hallo el titular');
				
			}else{
				try {
					//verificamos que el titular tenga una suscripción
					$friend_id =  \DB::table('seg_user_profile')
					->select('user_id')
					->where('identificacion',$identification)
					->get();					
					
					if(!empty($friend_id)){
						$beneficiario->titular_id = $friend_id[0]->user_id;						
						$suscripcion =  \DB::table('clu_suscription')
						->where('friend_id',$friend_id[0]->user_id)
						->where(function ($query) {
							$query->where('state_id',1)
							->orwhere('state_id',2)
							->orwhere('state_id',3);
						})
						->get();
						
						if(!empty($suscripcion)){
							//el titular tiene almenos una suscripción activa
							//verificamos la cantidad de beneficiarios
							$total_beneficiarios=\DB::table('clu_beneficiary')
							->select(\DB::raw('count(*) as total'))
							->where('titular_id',$friend_id[0]->user_id)
							->get()[0]->total;
							
							if($total_beneficiarios < 8){
								$beneficiario->state = 'Pago por suscripción';
								$beneficiario->alert = '#dff0d8';
								$beneficiario->price = 0;
							}else{
								$beneficiario->state = 'Pago pendiente';
								$beneficiario->alert = '#fff5cc';
								$beneficiario->price = env('PRICE_BENEFICIARY',20000);
							}
							
							$beneficiario->save();
							return Redirect::to('beneficiario/agregar')->withInput()->with('message', 'Beneficiario agregado exitosamente');
							
						}						
						return Redirect::to('beneficiario/agregar')->withInput()->with('error', 'El Titular no posee suscripción vigente');
					}
					return Redirect::to('beneficiario/agregar')->withInput()->with('error', 'No se hallo el titular');				
					
				}catch (\Illuminate\Database\QueryException $e) {
					$message = 'El beneficiario no se logro agregar';					
					return Redirect::to('beneficiario/agregar')->with('error', $e->getMessage())->withInput();
				}				
			}		
		}
		
	}
	public function getActualizar($id_app=null,$categoria=null,$id_mod=null,$id=null){
		if(is_null($id_mod)) return Redirect::to('/')->with('error', 'Este modulo no se puede alcanzar por url, solo es valido desde las opciones del menú');
				
		$bne =
		Beneficiary::
		where('clu_beneficiary.id', $id)
		->get()
		->toArray();
		
		//consultamos el titular
		/*
		$user =  \DB::table('seg_user')
		->select('names','surnames','identificacion')
		->join('seg_user_profile','seg_user.id','=','seg_user_profile.user_id')
		->where('seg_user.id',$bne[0]['titular_id'])
		->get();	
		
		//abonos
		//consultar los pagos de la suscripcción
		$moduledata['pagos'] =
		PaymentBeneficiary::
		where('clu_payment_beneficiary.beneficiary_id', $id)
		->get()
		->toArray();
		*/
		Session::flash('_old_input.identification', $bne[0]['identification']);
		Session::flash('_old_input.type_id', $bne[0]['type_id']);
		Session::flash('_old_input.names', $bne[0]['names']);
		Session::flash('_old_input.surnames', $bne[0]['surnames']);
		Session::flash('_old_input.relationship', $bne[0]['relationship']);
		Session::flash('_old_input.movil_number', $bne[0]['movil_number']);
		Session::flash('_old_input.civil_status', $bne[0]['civil_status']);		
		Session::flash('_old_input.state', $bne[0]['state']);
		Session::flash('_old_input.alert', $bne[0]['alert']);
		//Session::flash('_old_input.titular_id', $user[0]->names.' '.$user[0]->surnames.' '.$user[0]->identificacion);
		//Session::flash('_old_input.titular_id_id', $bne[0]['titular_id']);
		Session::flash('_old_input.beneficiary_id', $id);
		Session::flash('_old_input.edit', true);
		Session::flash('titulo', 'Editar');
		
		//return Redirect::to('beneficiario/agregar')->with('modulo',$moduledata);
		return Redirect::to('beneficiario/agregar');
	}
	
	public function postBuscar(Request $request){
	
		$url = explode("/", Session::get('_previous.url'));
		$moduledata['fillable'] = ['Rol','Descripción'];
		//estas opciones se usaran para pintar las opciones adecuadamente con respecto al modulo
		$moduledata['modulo'] = $url[count($url)-4];
		$moduledata['id_app'] = $url[count($url)-2];
		$moduledata['categoria'] = $url[count($url)-1];
		$moduledata['id_mod'] = $url[count($url)-5];
			
		Session::flash('modulo', $moduledata);
		Session::flash('filtro', $request->input()['names']);
		return view('seguridad.rol.listar');
		
	}
	
	public function getConsultartitular(Request $request){
		
		$term = $request->input()['term'];
		$terms = explode(" ",$term);
		
		//solo titulares que tienen una suscripción - es así. Debe ubicarce en algun carnet
		/*		
		$titulares = \DB::table('seg_user')
		->select('identificacion','names','surnames')
		->leftjoin('seg_user_profile', 'seg_user.id', '=', 'seg_user_profile.user_id')
		->where('seg_user.rol_id',3)
		->where('seg_user.active', '=' ,  Session::get('opaplus.usuario.lugar.active'))
		->where(function ($query) use ($terms){
			foreach($terms as $key => $value){
				$query->orwhere('seg_user_profile.identificacion', 'like', '%'.$value.'%')
				->orWhere('seg_user_profile.names', 'like', '%'.$value.'%')
				->orWhere('seg_user_profile.surnames', 'like', '%'.$value.'%');
			}						
		})
		->limit(15)
		->get();
		
		foreach($titulares as $titular){
			$array[] = $titular->names.' '.$titular->surnames.' '.$titular->identificacion; 
		}
		*/
		$titulares=\DB::table('clu_license')
		->select('clu_license.*','fr.names as names_fr','fr.surnames as surnames_fr','fr.identificacion as identificacion_fr','ufr.email','fr.movil_number as fr_movil','fr.fix_number','fr.adress','fr.state as departamento','fr.city','fr.neighborhood','fr.paymentadress','fr.reference','fr.reference_phone')
		->leftjoin('clu_suscription as ss', 'clu_license.suscription_id', '=', 'ss.id')
		->leftjoin('seg_user as ufr', 'ss.friend_id', '=', 'ufr.id')
		->leftjoin('seg_user_profile as fr', 'ufr.id', '=', 'fr.user_id')
		->orwhere('clu_license.type', 'suscription')
		->orwhere('clu_license.type', 'suscription_add')
		->where(function ($query) use ($terms){
			foreach($terms as $key => $value){
				$query->orwhere('fr.identificacion', 'like', '%'.$value.'%')
				->orWhere('fr.names', 'like', '%'.$value.'%')
				->orWhere('fr.surnames', 'like', '%'.$value.'%');
			}
		})
		->limit(15)
		->get();
		
		//solo los que tienen menos de 8 para solicitudes y solicitudes add
		$array_tit = array();
		$array_ides = array();
		foreach($titulares as $license){
			
			//total de beneficiarios por carnet
			$total=\DB::table('clu_beneficiary')
			->select(\DB::raw('count(*) as total'))
			->leftjoin('clu_license', 'clu_beneficiary.license_id', '=', 'clu_license.id')
			->where('clu_license.id', $license->id)
			->groupBy('clu_beneficiary.license_id')
			->get();
			
			$license->benes = 0;
			if(!empty($total)) $license->benes =  $total['0']->total;
				
			if($license->benes < 8){
				//hace parte del array definitivo
				$array_tit[] = $license;
			}
			
		}
		
		foreach($array_tit as $titular){
			$array[] = $titular->names_fr.' '.$titular->surnames_fr.' '.$titular->identificacion_fr;
		}
		
		if(!empty($term)){
			return $array;
		}
		return [];
	}
	
	public function postAbonar(Request $request){
		
		//consultar los pagos del beneficiario
		$pagos = \DB::table('clu_payment_beneficiary')
		->select('payment','date_payment')
		->where('beneficiary_id', $request->input()['id'])
		->get();
		$array['pagos'] =$pagos;
		
		if(count($pagos)){
			return response()->json(['respuesta'=>true,'data'=>$array]);
		}
		
		return response()->json(['respuesta'=>true,'data'=>null]);
	}
	
	public function getAbonarsave($payment = null, $date_payment = null,$beneficiary_id = null,$mora = null){
		
		//las siguientes dos lineas solo son utiles cuando se refresca la pagina, ya que al refrescar no se pasa por el controlador
		$moduledata['fillable'] = ['Beneficiario','Titular','Parentesco','Celular','Celular Titular'];
		
		//estas opciones se usaran para pintar las opciones adecuadamente con respecto al modulo
		$moduledata['modulo'] = 'beneficiario';
		$moduledata['id_app'] = '2';
		$moduledata['categoria'] = 'Componentes';
		$moduledata['id_mod'] = '8';
		
		Session::flash('modulo', $moduledata);
		
		$payment_obj = new PaymentBeneficiary();
		//Fecha  de abono de suscripción
		$payment_obj->date_payment = date("Y-m-d H:i:s");
		if(!empty($date_payment)){
			$payment_obj->date_payment = $date_payment;
		}
		
		$payment_obj->payment =$payment;
		$payment_obj->beneficiary_id = $beneficiary_id;
		
		if($payment_obj->payment > 0){
			//hay abono
			try {
				//el abono debe ser menor o igual a la mora
				if($payment_obj->payment <= $mora){
					$payment_obj->save();
				}else{
					Session::flash('error', 'El abono no ha sido agregado al beneficiario, pago es mayor a la mora. ');
					return Redirect::to('beneficiario/listar');
				}
			
			}catch (\Illuminate\Database\QueryException $e) {
				Session::flash('error', 'El abono no ha sido agregado al beneficiario. ');
				return Redirect::to('beneficiario/listar');
			}
			
			Session::flash('message', 'El abono ha sido agregado al beneficiario. ');
			return Redirect::to('beneficiario/listar');
		}
		
		Session::flash('error', 'El abono no ha sido agregado al beneficiario. El valor de abono es cero ');
		return Redirect::to('beneficiario/listar');		
			
	}
	
}
