<?php namespace App\Http\Controllers\Club;

use Validator;
use DateTime;
use DateInterval;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Core\Club\Beneficiary;
use App\Core\Club\Service;
use App\Core\Club\Specialist;
use App\Core\Club\Specialty;
use App\Core\Club\Subentity;
use App\Core\Club\Entity;
use App\Core\Club\Suscription;

class ServiceController extends Controller {
	
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
			
	public function getEnumerar($id_app=null,$categoria=null,$id_mod=null){
		if(is_null($id_mod)) return Redirect::to('/')->with('error', 'Este modulo no se debe alcanzar por url, solo es valido desde las opciones del menú');
		
		return Redirect::to('servicio/listar');
	}
	public function getListar(){

		$moduledata['modulo'] = 'servicios';
		$moduledata['id_app'] = '2';
		$moduledata['categoria'] = 'Componentes';
		$moduledata['id_mod'] = '12';

		//Consultas primarias
		//para modal nueva cita
		$especialties= \DB::table('clu_specialty')->get();
		foreach ($especialties as $especialty){
			$moduledata['especialidades'][$especialty->id] = $especialty->name;
		}

		$cities= \DB::table('clu_city')->get();
		foreach ($cities as $city){
			$moduledata['municipios'][$city->city] = $city->city;
		}

		$moduledata['fillable'] = ['Usuario','Contacto','Ciudad','Especialista','Especialidad','Día y Hora','Estado'];

		//para llevar temporalmente las variables a la vista.
		Session::flash('modulo', $moduledata);		

		return view('club.servicio.listar');
		
	}

	public function getListarajax(Request $request){

		$moduledata['total'] = Service::count();

		$order_column = 'names';
		$order_dir = 'desc';		
		if(!empty($request->input('columns'))){
			$order_column = $request->input('columns')[$request->input('order')[0]['column']]['data'];
			$order_dir = $request->input('order')[0]['dir'];
			
			if($order_column == 'beneficiario')$order_column = 'names';
			if($order_column == 'suscriptor')$order_column = 'names_fr';
			
		}

		//realizamos la consulta
		if(!empty($request->input('search')['value'])){
			Session::flash('search', $request->input('search')['value']);			
			
			$moduledata['servicios']=\DB::table('clu_service')
			->select('clu_service.*',
				'clu_specialist.name as name_specialist',
				'clu_specialty.name as name_specialty',
				'clu_state_service.state as name_state',
				'clu_state_service.alert as status_alert')
			->leftjoin('clu_specialist', 'clu_service.especialist_id', '=', 'clu_specialist.id')
			->leftjoin('clu_specialty', 'clu_service.especialty_id', '=', 'clu_specialty.id')
			->leftjoin('clu_state_service', 'clu_service.status', '=', 'clu_state_service.id')	
			->where(function ($query) {
				$query
				->where('clu_service.city', 'like', '%'.Session::get('search').'%')
				->orWhere('clu_service.identification_user', 'like', '%'.Session::get('search').'%')
				->orWhere('clu_service.names_user', 'like', '%'.Session::get('search').'%')
				->orWhere('clu_service.day', 'like', '%'.Session::get('search').'%')
				->orWhere('clu_specialist.name', 'like', '%'.Session::get('search').'%')
				->orWhere('clu_specialty.name', 'like', '%'.Session::get('search').'%');			
			})
			->orderBy($order_column, $order_dir)
			->skip($request->input('start'))
			->take($request->input('length'))
			->get();

			$moduledata['filtro'] = count($moduledata['servicios']);
		}else{

			$moduledata['servicios']=\DB::table('clu_service')
			->select('clu_service.*',
				'clu_specialist.name as name_specialist',
				'clu_specialty.name as name_specialty',
				'clu_state_service.state as name_state',
				'clu_state_service.alert as status_alert')

			->leftjoin('clu_specialist', 'clu_service.especialist_id', '=', 'clu_specialist.id')
			->leftjoin('clu_specialty', 'clu_service.especialty_id', '=', 'clu_specialty.id')
			->leftjoin('clu_state_service', 'clu_service.status', '=', 'clu_state_service.id')
			->orderBy($order_column, $order_dir)
			->skip($request->input('start'))->take($request->input('length'))
			->get();		
			
				
			$moduledata['filtro'] = $moduledata['total'];
		}

		foreach($moduledata['servicios'] as $servicio){

			$servicio->day_alert = '#dff0d8';//pago efectuado
			//alert para dia de servicio
			$next_day = date_create($servicio->date_service_time);//fecha proximo pago	
			$hoy = new DateTime();
			//$hoy = $hoy->format('Y-m-d');
			//$hoy = date_create($hoy);
				
			$diff = $hoy->diff(new DateTime($servicio->date_service_time), true)->days + 1;
			if($hoy > $next_day) {$diff = $diff*-1;}

			if($diff <= 0){
				$servicio->day_alert = '#f2dede';				
			}elseif($diff <= 1){
				$servicio->day_alert = '#fff5cc';
			}elseif($diff >= 2){
				$servicio->day_alert = '#d9edf7';
			}

		}
		
		return response()->json(['draw'=>$request->input('draw')+1,'recordsTotal'=>$moduledata['total'],'recordsFiltered'=>$moduledata['filtro'],'data'=>$moduledata['servicios']]);	
		
	}

	//ajax para consultar la preinformacion de la entidad nueva
	public function postNuevo(Request $request){
		return response()->json(['respuesta'=>true,'data'=>null]);
	}

	public function getCrear($id_app=null,$categoria=null,$id_mod=null){
		//Modo de evitar que otros roles ingresen por la url
		if(is_null($id_mod)) return Redirect::to('/')->with('error', 'Este modulo no se puede alcanzar por url, solo es valido desde las opciones del menú');
		
		return Redirect::to('servicio/agregar');
	}

	public function getAgregar(){

		//consultamos la especialidades
		//Consultas primarias		
		$especialties= \DB::table('clu_specialty')->get();
		foreach ($especialties as $especialty){
			$moduledata['especialidades'][$especialty->id] = $especialty->name;
		}

		$entidades = \DB::table('clu_subentity')
		->select('clu_subentity.*','clu_entity.business_name')
		->join('clu_entity', 'clu_subentity.entity_id', '=', 'clu_entity.id')
		->join('clu_available_x_specialty', 'clu_subentity.id', '=', 'clu_available_x_specialty.subentity_id')		
		->get();
		
		foreach ($entidades as $entidad){
			$moduledata['entidades'][$entidad->id] = $entidad->sucursal_name.' - '.$entidad->business_name;
		}

		//para llevar temporalmente las variables a la vista.
		Session::flash('moduloagregar', $moduledata);
	
		return view('club.servicio.agregar');
	}

	public function postSave(Request $request){
		
		$messages = [
			'required' => 'El campo :attribute es requerido.',				
		];
		
		$rules = array(
			'municipio'    => 'required',
			'price' => 'required',
			'identificacion' => 'required',
			'nombreusuario'    => 'required',
			'dia' => 'required',
			'fechahora' => 'required',
			'duration' => 'required',
			'id_especialidad' => 'required',
			'id_especialista' => 'required',
			'id_entidad' => 'required',
			'id_suscription' => 'required',
		);
		
		$validator = Validator::make($request->input(), $rules, $messages);
		if ($validator->fails()) {
			return view('club.servicio.agregar')->withErrors($validator)->withInput();
		}else{
			
			$servicio = new Service();

			$date = date_create($request->input('fechahora'));			

			$servicio->city = $request->input()['municipio'];
			$servicio->price = str_replace('$','',$request->input()['price']);
			$servicio->identification_user = $request->input()['identificacion'];
			$servicio->names_user = $request->input()['nombreusuario'];
			$servicio->surnames_user = $request->input()['numerocontacto'];
			$servicio->description = $request->input()['description'];
			$servicio->day = $request->input()['dia'];
			$servicio->date_service = date_format($date,"Y-m-d H:i");;//con hora y todo
			$servicio->date_service_time = date_format($date,"Y-m-d H:i");//con hora y todo
			$servicio->hour_start = date_format($date,"H:i");
			$servicio->duration = $request->input()['duration'];
			$servicio->especialty_id = $request->input()['id_especialidad'];
			$servicio->especialist_id = $request->input()['id_especialista'];
			$servicio->subentity_id = $request->input()['entidad'];
			$servicio->suscription_id = $request->input()['id_suscription'];

			if($request->input()['service_id']){
				//actualizacion de servicio - borrado
			}else{
				//nueva entidad
				try {
					$servicio->save();
					return Redirect::to('servicio/listar')->withInput()->with('message', 'Servicio agregado exitosamente');					
				}catch (\Illuminate\Database\QueryException $e) {										
					return Redirect::to('servicio/listar')->with('error', $e->getMessage())->withInput();
				}	
			}
			
		}
	}
	

	//retorna los especialistas y sus disponibilidades
	public function postConsultservicio(Request $request){
		//preparación de datos
		$moduledata['modulo'] = 'servicios';
		$moduledata['id_app'] = '2';
		$moduledata['categoria'] = 'Componentes';
		$moduledata['id_mod'] = '12';
		//Consultas primarias
		//para modal nueva cita
		$especialties= \DB::table('clu_specialty')->get();
		foreach ($especialties as $especialty){
			$moduledata['especialidades'][$especialty->id] = $especialty->name;
		}
		$cities= \DB::table('clu_city')->get();
		foreach ($cities as $city){
			$moduledata['municipios'][$city->city] = $city->city;
		}
		//para llevar temporalmente las variables a la vista.
		Session::flash('modulo', $moduledata);
		
		//verificamos los datos, (int)especialidad, (string)municipio, (int)entidad, fechainicio, fechafin
		$messages = [
			'required' => 'El campo :attribute es requerido.',
		];
		
		$rules = array(
			'especialidad'=>'required',			
			'municipio'=>'required',			
			'entidad'=>'required',			
		);
		$validator = Validator::make($request->input(), $rules, $messages);
		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}else{
			//se realizan los calculos sobre las disponibilidades
			//primero validaciones
			//1. la fecha fin debe ser mayor que la fecha inicio
			if($request->input('fechainicio') != ""){
				$fechainicio = new DateTime($request->input('fechainicio'));
			}else{
				$fechainicio = new DateTime();
			}
			if($request->input('fechafin') != ""){
				$fechafin = new DateTime($request->input('fechafin'));
			}else{
				$fechafin = new DateTime($fechainicio->format('Y-m-d'));
				$fechafin->modify('+8 day');//avanza 8 dias
			}

			$diff = $fechainicio->diff($fechafin, true)->days;
			//puede ser igual

			
			if($fechainicio > $fechafin) $diff = $diff*-1;			
			if($diff == 0) $diff = $diff+1;

			if($diff){
				//las fechas estan bien, se puede priceder
				//consultamos los especialistas disponibles en las fechas y con las especialidades y el municio dado.
				if($request->input('entidad_check') != "1"){
					$especialistas = \DB::table('clu_specialist')
					->select('clu_specialist.*','clu_entity.business_name','clu_entity.nit','clu_subentity.sucursal_name','clu_subentity.adress','clu_subentity.phone1_contact','clu_subentity.phone2_contact','clu_subentity.email_contact','clu_subentity.city','clu_specialty.name as especialidad','clu_specialty.id as specialty_id','clu_specialist_x_specialty.rate_particular','clu_specialist_x_specialty.rate_suscriptor','clu_specialist_x_specialty.tiempo','clu_available.day','clu_available.hour_start','clu_available.hour_end')	

					->join('clu_specialist_x_specialty', 'clu_specialist.id', '=', 'clu_specialist_x_specialty.specialist_id')
					->join('clu_available', 'clu_specialist.id', '=', 'clu_available.specialist_id')
					->join('clu_available_x_specialty', 'clu_available.id', '=', 'clu_available_x_specialty.available_id')

					->join('clu_subentity', 'clu_available.subentity_id', '=', 'clu_subentity.id')
					->join('clu_entity', 'clu_subentity.entity_id', '=', 'clu_entity.id')
					->join('clu_specialty', 'clu_available_x_specialty.specialty_id', '=', 'clu_specialty.id')

					->where('clu_specialist_x_specialty.specialty_id',$request->input('especialidad'))
					->where('clu_available_x_specialty.specialty_id',$request->input('especialidad'))
					->where('clu_available_x_specialty.subentity_id',$request->input('entidad'))
					->get();
				}else{
					$especialistas = \DB::table('clu_specialist')
					->select('clu_specialist.*','clu_entity.business_name','clu_entity.nit','clu_subentity.sucursal_name','clu_subentity.adress','clu_subentity.phone1_contact','clu_subentity.phone2_contact','clu_subentity.email_contact','clu_subentity.city','clu_specialty.name as especialidad','clu_specialty.id as specialty_id','clu_specialist_x_specialty.rate_particular','clu_specialist_x_specialty.rate_suscriptor','clu_specialist_x_specialty.tiempo','clu_available.day','clu_available.hour_start','clu_available.hour_end')	

					->join('clu_specialist_x_specialty', 'clu_specialist.id', '=', 'clu_specialist_x_specialty.specialist_id')
					->join('clu_available', 'clu_specialist.id', '=', 'clu_available.specialist_id')
					->join('clu_available_x_specialty', 'clu_available.id', '=', 'clu_available_x_specialty.available_id')

					->join('clu_subentity', 'clu_available.subentity_id', '=', 'clu_subentity.id')
					->join('clu_entity', 'clu_subentity.entity_id', '=', 'clu_entity.id')
					->join('clu_specialty', 'clu_available_x_specialty.specialty_id', '=', 'clu_specialty.id')

					->where('clu_specialist_x_specialty.specialty_id',$request->input('especialidad'))
					->where('clu_available_x_specialty.specialty_id',$request->input('especialidad'))
					//->where('clu_available_x_specialty.subentity_id',$request->input('entidad'))
					->get();
				}			
				
				//array de crono cronogrma
				$crono = array();

				//creamos los campos para las crono que llamaremos cronograma
				//corriendo todos los especialistas y evaluando su disponibilidad en dias y horas
				foreach($especialistas as $especialista){

					//por cada especialista los datos importantes
					/*
					+"tiempo": "1:30"
				    +"day": "MIERCOLES"
				    +"hour_start": "13:15"
				    +"hour_end": "16:15"
				    */
				    $arrayMapaDias = array(
				    	'LUNES'=>1,
				    	'MARTES'=>2,
				    	'MIERCOLES'=>3,
				    	'JUEVES'=>4,
				    	'VIERNES'=>5,
				    	'SABADO'=>6,
				    	'DOMINGO'=>7
				    ); 
				    
				    $especialista->nrodia = $arrayMapaDias[$especialista->day];
				    
				    $datestart = date_create($fechainicio->format('Y-m-d')." ".$especialista->hour_start);
				    $datestart2 = date_create($fechainicio->format('Y-m-d')." ".$especialista->hour_start);
				    $dateend = date_create($fechainicio->format('Y-m-d')." ".$especialista->hour_end);
				    $time = explode(':',$especialista->tiempo);
				    
				    //creamos los dias de cronograma
				    //Corremos los días

				    for($i=0;$i<=$diff;$i++){
				    	//1.verificamos que este día halla disponibilidad
				    	$dia =  date('N', strtotime($datestart->format('Y-m-d')));

				    	if(intval($dia) == $especialista->nrodia){
				    		//si hay disponibilidad este dia

				    		//corremos las horas				    		

					    	$datestart2->add(new DateInterval('PT'.$time[0].'H'.$time[1].'M'));
					    	while($datestart2<=$dateend){
					    		
					    		$crono[] = array(
					    			$datestart->format('Y-m-d'),
					    			$datestart->format('Y-m-d H:i'),
					    			$especialista->day,

					    			$especialista->name,
					    			$especialista->phone1,
					    			$especialista->phone2,
					    			$especialista->email,
					    			$especialista->name_assistant,
					    			$especialista->phone1_assistant,
					    			$especialista->phone2_assistant,
					    			$especialista->email_assistant,

					    			$especialista->business_name.' - '. $especialista->sucursal_name,
					    			$especialista->nit,
					    			$especialista->adress,
					    			$especialista->phone1_contact,
					    			$especialista->phone2_contact,
					    			$especialista->email_contact,

					    			$especialista->city,
					    			$especialista->especialidad,
					    			$especialista->rate_particular,
					    			$especialista->rate_suscriptor,
					    			$especialista->tiempo,

					    			$especialista->id,//id especialista
					    			$especialista->entity_id,
					    			$especialista->specialty_id,
					    			'libre',
					    			'#dee2d7'

				    			);
					    		
					    		$datestart->add(new DateInterval('PT'.$time[0].'H'.$time[1].'M'));	
					    		$datestart2->add(new DateInterval('PT'.$time[0].'H'.$time[1].'M'));
					    	}
					    	
				    	}

				    	$datestart = date_create($fechainicio->format('Y-m-d')." ".$especialista->hour_start);
				    	$datestart2 = date_create($fechainicio->format('Y-m-d')." ".$especialista->hour_start);

				    	$paramday = '+'.($i+1).' day';
				    	
				    	$datestart->modify($paramday);//avanza 1 dia
				    	$datestart2->modify($paramday);//avanza 1 dia
				    	$dateend->modify('+1 day');//avanza 1 dia
				    				    	
				    }
				}

				//consultamos las citas habiles para las fechas y las entidades
				if($request->input('entidad_check') != "1"){
					//si esta NO seleccionada
					$citas  = \DB::table('clu_service')
					->whereBetween('date_service', [$fechainicio->format('Y-m-d'),$fechafin->format('Y-m-d')])
					->where('clu_service.subentity_id',$request->input('entidad'))
					->get();

				}else{
					
					$citas = \DB::table('clu_service')
					->whereBetween('date_service', [$fechainicio->format('Y-m-d'),$fechafin->format('Y-m-d')])
					//->where('clu_service.subentity_id',$request->input('entidad'))
					->get();
				}				
				
				//filtramos el cronograma por las citas
				foreach($citas as $cita){
					
					$datecita = date_create($cita->date_service." ".$cita->hour_start);
					
					for($i=0;$i<count($crono);$i++){

						$datecrt = date_create($crono[$i][1]);//fecha de creación
						
						//comparamos la fecha y el especialista
						if($datecrt == $datecita && $crono[$i][22] == $cita->especialist_id){							

							$crono[$i][25] = 'ocupado';
							$crono[$i][26] = '#e2d7d7';							

						}else{
							//ELSE ALTAMENTE SENSIBLE PROBAR CON CUIDADO
							//hay que revisar tambien en las otras especialidades del especiasta su disponibilidad OJO!!!
							//encontrar una cita de algun otra especialidad del especialista que cumpla las condiciones
							$datecita2 = date_create($cita->date_service." ".$cita->hour_start);
							$datecita_time2 = explode(':',$cita->duration);
							$datecita2->add(new DateInterval('PT'.$datecita_time2[0].'H'.$datecita_time2[1].'M'));
							if($datecita2 > $datecrt && $datecita < $datecrt && $crono[$i][22] == $cita->especialist_id){
								$crono[$i][25] = 'ocupado';
								$crono[$i][26] = '#e2d7d7';
							}

							$datecita2 = date_create($cita->date_service." ".$cita->hour_start);
							$datecita_time2 = explode(':',$cita->duration);
							$datecita2->sub(new DateInterval('PT'.$datecita_time2[0].'H'.$datecita_time2[1].'M'));
							if($datecita2 < $datecrt && $datecita > $datecrt && $crono[$i][22] == $cita->especialist_id){
								$crono[$i][25] = 'ocupado';
								$crono[$i][26] = '#e2d7d7';
							}
							
						}						

					}

				}

				//rotornamos el cronograma filtrado				
				return Redirect::to('servicio/agregar')->with('modulo',[$request->input(),$fechainicio->format('Y-m-d'),$fechafin->format('Y-m-d'),$crono,$especialistas]);

			}else{				
				$message = 'No se puede consultar, la fecha inicio es mayor que la fecha fin';				
				return Redirect::to('servicio/listar')->with('error', $message)->withInput();				
			}

		}

		return view('club.servicio.listar');
	}


	public function postConsultarentidad(Request $request){

		//consultas necesarias
		$array = array();
		//consultamos las sucursales para la especialidad dada
		$entidades = \DB::table('clu_subentity')
		->select('clu_subentity.*','clu_entity.business_name')
		->join('clu_entity', 'clu_subentity.entity_id', '=', 'clu_entity.id')
		->join('clu_available_x_specialty', 'clu_subentity.id', '=', 'clu_available_x_specialty.subentity_id')
		->where('clu_subentity.city','LIKE',$request->input('idmunicipio'))
		->where('clu_available_x_specialty.specialty_id',$request->input('idespecialidad'))
		->get();
		
		foreach ($entidades as $entidad){
			$array['entidades'][$entidad->id] = $entidad->sucursal_name.' - '.$entidad->business_name;
		}

		return response()->json(['respuesta'=>true,'data'=>$array]);

	}

	public function postConsultaruser(Request $request){
		
		//colsultamos primero en titulares
		$array = array();
		$array['titular'] = \DB::table('seg_user_profile')
		->select('seg_user_profile.*','clu_suscription.*','clu_state.state as estado','clu_suscription.id as suscription_id')
		->join('seg_user', 'seg_user_profile.user_id', '=', 'seg_user.id')
		->join('clu_suscription', 'seg_user.id', '=', 'clu_suscription.friend_id')
		->join('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
		->where('seg_user_profile.identificacion',$request->input('id'))
		->get();

		if(empty($array['titular'])){
			$array['beneficiario'] = \DB::table('clu_beneficiary')
			->select('clu_beneficiary.*','clu_suscription.*','seg_user_profile.names as friendnames','seg_user_profile.surnames as friendsurnames','seg_user_profile.identificacion as friendidentificacion','clu_state.state as estado','clu_suscription.id as suscription_id')
			->join('clu_license', 'clu_beneficiary.license_id', '=', 'clu_license.id')
			->join('clu_suscription', 'clu_license.suscription_id', '=', 'clu_suscription.id')
			->join('seg_user_profile', 'clu_suscription.friend_id', '=', 'seg_user_profile.user_id')
			->join('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->where('clu_beneficiary.identification',$request->input('id'))
			->get();
		}
		
		return response()->json(['respuesta'=>true,'data'=>$array]);

	}

	//funcion del modal desplegado en la vista listar
	public function postConsultarusermodal(Request $request){

		//consultamos los beneficiarios y el titular de la suscripción
		$array = array();
		if(is_numeric($request->input('id'))){

			$array['titular'] = \DB::table('seg_user_profile')
			->select(
				'seg_user_profile.*',
				'clu_suscription.*',
				'clu_state.state as estado',
				'clu_suscription.id as suscription_id',
				'clu_suscription.code as suscription_code')
			->join('seg_user', 'seg_user_profile.user_id', '=', 'seg_user.id')
			->join('clu_suscription', 'seg_user.id', '=', 'clu_suscription.friend_id')
			->join('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
			->where('seg_user_profile.identificacion',$request->input('id'))
			->get();

		
			if(!empty($array['titular'])){
				//ya tenemos el titular y suscripción
				//falta los beneficiarios
				$array['beneficiario'] = \DB::table('clu_beneficiary')
				->select('clu_beneficiary.*')
				->join('clu_license', 'clu_beneficiary.license_id', '=', 'clu_license.id')
				->join('clu_suscription', 'clu_license.suscription_id', '=', 'clu_suscription.id')
				->join('seg_user_profile', 'clu_suscription.friend_id', '=', 'seg_user_profile.user_id')			
				->where('clu_license.suscription_id',$array['titular'][0]->suscription_id)
				->get();

			}else{
				
				$array['beneficiario'] = \DB::table('clu_beneficiary')
				->select(
					'clu_beneficiary.*',
					'clu_suscription.*',
					'clu_suscription.id as suscription_id')
				->join('clu_license', 'clu_beneficiary.license_id', '=', 'clu_license.id')
				->join('clu_suscription', 'clu_license.suscription_id', '=', 'clu_suscription.id')
				->join('seg_user_profile', 'clu_suscription.friend_id', '=', 'seg_user_profile.user_id')
				->join('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
				->where('clu_beneficiary.identification',$request->input('id'))
				->get();

				if(!empty($array['beneficiario'])){
					//es un beneficiario
					$array['titular'] = \DB::table('seg_user_profile')
					->select('seg_user_profile.*',
						'clu_suscription.*',
						'clu_state.state as estado',
						'clu_suscription.id as suscription_id',
						'clu_suscription.code as suscription_code')
					->join('seg_user', 'seg_user_profile.user_id', '=', 'seg_user.id')
					->join('clu_suscription', 'seg_user.id', '=', 'clu_suscription.friend_id')
					->join('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
					->where('clu_suscription.id',$array['beneficiario'][0]->suscription_id)
					->get();

					$array['beneficiario'] = \DB::table('clu_beneficiary')
					->select('clu_beneficiary.*')
					->join('clu_license', 'clu_beneficiary.license_id', '=', 'clu_license.id')
					->join('clu_suscription', 'clu_license.suscription_id', '=', 'clu_suscription.id')
					->join('seg_user_profile', 'clu_suscription.friend_id', '=', 'seg_user_profile.user_id')			
					->where('clu_license.suscription_id',$array['beneficiario'][0]->suscription_id)
					->get();


				}else{
					//posiblemnte es un codigo
					
					$array['suscripcion'] = \DB::table('clu_suscription')
					->select(
						'clu_suscription.*',
						'clu_suscription.id as suscription_id')				
					->where('clu_suscription.code','LIKE',$request->input('id'))
					->get();
					if(empty($array['suscripcion'])){
						$array['suscripcion'] = \DB::table('clu_suscription')
						->select(
							'clu_suscription.*',
							'clu_suscription.id as suscription_id')				
						->where('clu_suscription.code','LIKE',' '.$request->input('id'))
						->get();
					}

					if(!empty($array['suscripcion'])){

						//si es un codigo
						$array['titular'] = \DB::table('seg_user_profile')
						->select('seg_user_profile.*',
							'clu_suscription.*',
							'clu_state.state as estado',
							'clu_suscription.id as suscription_id',
							'clu_suscription.code as suscription_code')
						->join('seg_user', 'seg_user_profile.user_id', '=', 'seg_user.id')
						->join('clu_suscription', 'seg_user.id', '=', 'clu_suscription.friend_id')
						->join('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
						->where('clu_suscription.id',$array['suscripcion'][0]->suscription_id)
						->get();

						$array['beneficiario'] = \DB::table('clu_beneficiary')
						->select('clu_beneficiary.*')
						->join('clu_license', 'clu_beneficiary.license_id', '=', 'clu_license.id')
						->join('clu_suscription', 'clu_license.suscription_id', '=', 'clu_suscription.id')
						->join('seg_user_profile', 'clu_suscription.friend_id', '=', 'seg_user_profile.user_id')			
						->where('clu_license.suscription_id',$array['suscripcion'][0]->suscription_id)
						->get();
					}
				}
				
			}		

		}else{
			//posiblemnte es un codigo
					
			$array['suscripcion'] = \DB::table('clu_suscription')
			->select(
				'clu_suscription.*',
				'clu_suscription.id as suscription_id')				
			->where('clu_suscription.code','LIKE',$request->input('id'))
			->get();

			if(!empty($array['suscripcion'])){

				//si es un codigo
				$array['titular'] = \DB::table('seg_user_profile')
				->select('seg_user_profile.*',
					'clu_suscription.*',
					'clu_state.state as estado',
					'clu_suscription.id as suscription_id',
					'clu_suscription.code as suscription_code')
				->join('seg_user', 'seg_user_profile.user_id', '=', 'seg_user.id')
				->join('clu_suscription', 'seg_user.id', '=', 'clu_suscription.friend_id')
				->join('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
				->where('clu_suscription.id',$array['suscripcion'][0]->suscription_id)
				->get();

				$array['beneficiario'] = \DB::table('clu_beneficiary')
				->select('clu_beneficiary.*')
				->join('clu_license', 'clu_beneficiary.license_id', '=', 'clu_license.id')
				->join('clu_suscription', 'clu_license.suscription_id', '=', 'clu_suscription.id')
				->join('seg_user_profile', 'clu_suscription.friend_id', '=', 'seg_user_profile.user_id')			
				->where('clu_license.suscription_id',$array['suscripcion'][0]->suscription_id)
				->get();
			}
		}


		

		return response()->json(['respuesta'=>true,'data'=>$array]);
	}

	//actualizar los beneficiarios
	public function postEditbeneficiario(Request $request){		

		$beneficiario = Beneficiary::find($request->input()['id']);		
		$beneficiario->identification = $request->input()['identification'];
		$beneficiario->type_id = $request->input()['type_id'];
		$beneficiario->movil_number = $request->input()['telefono'];
		$beneficiario->names = $request->input()['names'];
		$beneficiario->surnames = $request->input()['surnames'];

		try {
			$beneficiario->save();						
		}catch (\Illuminate\Database\QueryException $e) {			
			return response()->json(['respuesta'=>false]);
		}
		
		return response()->json(['respuesta'=>true]);
	}


	public function postConsultarservicio(Request $request){

		$array = array();

		$array['servicio'] = Service::find($request->input()['id_service']);		
		$array['especialidad'] = Specialty::find($request->input()['especialty_id']);		
		$array['especialista'] = Specialist::find($request->input()['especialist_id']);		
		$array['sucursal'] = Subentity::find($request->input()['subentity_id']);		
		$array['entidad'] = Entity::find($array['sucursal']->entity_id);		
		//$array['suscripcion']= Suscription::find($request->input()['suscription_id']);	
		$array['suscripcion'] = \DB::table('clu_suscription')
		->select(
			'clu_suscription.*',
			'clu_state.state as state')	
		->join('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
		->where('clu_suscription.id',$request->input()['suscription_id'])
		->get();
		
		$array['titular'] = \DB::table('seg_user_profile')
		->select('seg_user_profile.*',
			'clu_suscription.*',
			'clu_state.state as estado',
			'clu_suscription.id as suscription_id',
			'clu_suscription.code as suscription_code')
		->join('seg_user', 'seg_user_profile.user_id', '=', 'seg_user.id')
		->join('clu_suscription', 'seg_user.id', '=', 'clu_suscription.friend_id')
		->join('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')
		->where('clu_suscription.id',$request->input()['suscription_id'])
		->get();	

		//usuario de cita
		$array['usuario'] =  \DB::table('clu_service')
		->select('seg_user_profile.*','clu_beneficiary.*')
		->leftjoin('seg_user_profile', 'clu_service.identification_user', '=', 'seg_user_profile.identificacion')
		->leftjoin('clu_beneficiary', 'clu_service.identification_user', '=', 'clu_beneficiary.identification')
		->where('clu_service.id',$request->input()['id_service'])
		->get();		

		return response()->json(['respuesta'=>true,'data'=>$array]);

	}

	public function postBorrarservicio(Request $request){

		$servicio = Service::find($request->input()['id_service']);		

		try {
			$servicio->delete();			
		}catch (\Illuminate\Database\QueryException $e) {			
			return response()->json(['respuesta'=>false]);
		}

		return response()->json(['respuesta'=>true]);		
	}

	//consulta javascript ajax
	public function postEditarservicio(Request $request){

		$array['estados'] = \DB::table('clu_state_service')->get();
		return response()->json(['respuesta'=>true,'data'=>$array]);

	}

	//datos que llegan el form
	public function postMetodeditarservicio(Request $request){

		$servicio = Service::find($request->input()['id_service_form']);

		$date = date_create($request->input('date_service'));

		$servicio->status = $request->input()['sel_status_service'];
		$servicio->description = $request->input()['description'];
		$servicio->date_service = date_format($date,"Y-m-d H:i");;//con hora y todo
		$servicio->date_service_time = date_format($date,"Y-m-d H:i");//con hora y todo
		$servicio->hour_start = date_format($date,"H:i");

		try {
			$servicio->save();						
		}catch (\Illuminate\Database\QueryException $e) {			
			$message = 'No se puedo editar el servicio';				
			return Redirect::to('servicio/listar')->with('error', $message)->withInput();			
		}
		
		$message = 'Servicio correctamente editado';				
		return Redirect::to('servicio/listar')->with('message', $message)->withInput();			
	}	
	
}
