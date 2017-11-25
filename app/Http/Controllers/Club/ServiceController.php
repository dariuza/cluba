<?php namespace App\Http\Controllers\Club;

use Validator;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

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
			if($fechainicio > $fechafin)$diff = $diff*-1;			

			if(!$diff){
				//las fechas estan bien, se puede priceder
				//consultamos las citas progradas para dichas fechas




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
