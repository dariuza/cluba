<?php namespace App\Http\Controllers\Club;

use Validator;
use DateTime;
use DateInterval;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Core\Club\Service;
use App\Core\Club\Specialist;
use App\Core\Club\Specialty;

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

		//para llevar temporalmente las variables a la vista.
		Session::flash('modulo', $moduledata);

		return view('club.servicio.listar');
		
	}

	//ajax para consultar la preinformacion de la entidad nueva
	public function postNuevo(Request $request){
		return response()->json(['respuesta'=>true,'data'=>null]);
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
				if($request->input('entidad_check') == "1"){
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
					    			'libre'

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

				//consultamos las sitas habiles para las fechas y las entidades
				if($request->input('entidad_check') == "1"){
					
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
				
				//filtramos el cronograma por las crono
				foreach($citas as $cita){
					
					$datecita = date_create($cita->date_service." ".$cita->hour_start);
					
					for($i=0;$i<count($crono);$i++){

						$datecrt = date_create($crono[$i][1]);
						
						if($datecrt == $datecita && $crono[$i][22] == $cita->especialist_id){

							$crono[$i][25] = 'ocupado';


						}						

					}

				}

				//rotornamos el cronograma filtrado			
				dd($crono);


			}else{				
				$message = 'No se puede consultar, la fecha inicio es mayor que la fecha fin';				
				return Redirect::to('servicio/listar')->with('error', $message)->withInput();				
			}

		}

		return view('club.servicio.listar');
	}

	public function getCrear($id_app=null,$categoria=null,$id_mod=null){
		//Modo de evitar que otros roles ingresen por la url
		if(is_null($id_mod)) return Redirect::to('/')->with('error', 'Este modulo no se puede alcanzar por url, solo es valido desde las opciones del menú');
		
		return Redirect::to('servicio/agregar');
	}

	public function getAgregar(){
	
		return view('club.servicio.agregar');
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
	
}
