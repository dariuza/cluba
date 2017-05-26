<?php namespace App\Http\Controllers\Club;

use Validator;
use App\Core\Club\Specialist;
use App\Core\Club\Entity;
use App\Core\Club\Subentity;
use App\Core\Club\SpecialistSpecialty;
use App\Core\Club\Available;
use App\Core\Club\AvailableSpecialty;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class SpecialistController extends Controller {
	
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
		
	}
	public function getGeneral(){
			
	}
	
	public function getEnumerar($id_app=null,$categoria=null,$id_mod=null){
		if(is_null($id_mod)) return Redirect::to('/')->with('error', 'Este modulo no se debe alcanzar por url, solo es valido desde las opciones del menú');		
		
		return Redirect::to('especialista/listar');
	}
	public function getListar(){
		//las siguientes dos lineas solo son utiles cuando se refresca la pagina, ya que al refrescar no se pasa por el controlador
		$moduledata['fillable'] = ['Nombre','Identificación','Teléfono 1','Teléfono 2','Correo Electrónico'];
		//recuperamos las variables del controlador anterior ante el echo de una actualización de pagina
		$url = explode("/", Session::get('_previous.url'));
		
		//estas opciones se usaran para pintar las opciones adecuadamente con respecto al modulo
		$moduledata['modulo'] = 'especialistas';
		$moduledata['id_app'] = '2';
		$moduledata['categoria'] = 'Componentes';
		$moduledata['id_mod'] = '11';
		
		//consultamos las especialidades
		$especialidades_null = array("NO HAY ESPECIALIDADES");
		$specialties= \DB::table('clu_specialty')->get();
		foreach ($specialties as $especial){
			$especialidades[$especial->id] = $especial->name;
		}
		$moduledata['especialidades']=$especialidades_null;	
		if(count($moduledata['especialidades'])) $moduledata['especialidades']=$especialidades;		
				
		//para llevar temporalmente las variables a la vista.
		Session::flash('modulo', $moduledata);
		
		return view('club.especialista.listar');
	}
	
	public function getListarajax(Request $request){
		//otros parametros
		$moduledata['total']=Specialist::count();
		
		//realizamos la consulta
		if(!empty($request->input('search')['value'])){
			Session::flash('search', $request->input('search')['value']);
				
			$moduledata['especialistas']=
			Specialist::
			where(function ($query) {
				$query->where('clu_specialist.name', 'like', '%'.Session::get('search').'%')				
				->orWhere('clu_specialty.identification', 'like', '%'.Session::get('search').'%');
			})
			->skip($request->input('start'))->take($request->input('length'))
			->get();
			$moduledata['filtro'] = count($moduledata['especialistas']);
		}else{
			$moduledata['especialistas']=\DB::table('clu_specialist')->skip($request->input('start'))->take($request->input('length'))->get();
		
			$moduledata['filtro'] = $moduledata['total'];
		}
		
		return response()->json(['draw'=>$request->input('draw')+1,'recordsTotal'=>$moduledata['total'],'recordsFiltered'=>$moduledata['filtro'],'data'=>$moduledata['especialistas']]);
		
	}
	
	//Función para la opción: agregar
	public function getCrear($id_app=null,$categoria=null,$id_mod=null){
		//Modo de evitar que otros roles ingresen por la url
		if(is_null($id_mod)) return Redirect::to('/')->with('error', 'Este modulo no se puede alcanzar por url, solo es valido desde las opciones del menú');
			
		return Redirect::to('especialista/agregar');
	}
	
	public function getAgregar(){
	
		return view('club.especialidad.agregar');
	}
	
	//función para guardar usuarios con su perfil
	public function postSave(Request $request){
		
		$array_input = array();		
		$array_input['_token'] = $request->input('_token');
		$array_input['correo_electronico'] = $request->input('correo_electronico');
		$array_input['correo_electronico_asistente'] = $request->input('correo_electronico_asistente');
		foreach($request->input() as $key=>$value){
			if($key != "_token" && $key != "correo_electronico" && $key != "correo_electronico_asistente"){			
				$array_input[$key] = strtoupper($value);
			}		
						
		}
		$request->replace($array_input);
		
		$messages = [
			'required' => 'El campo :attribute es requerido.',
		];
		
		$rules = array(
			'entidad'=>'required',	
			'nombres'=>'required',			
		);

		$validator = Validator::make($request->input(), $rules, $messages);
		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}else{
			
			$specialist = new Specialist();
				
			$specialist->name = $request->input()['nombres'];
			$specialist->identification = $request->input()['identificacion'];
			$specialist->phone1 = $request->input()['telefono_uno'];
			$specialist->phone2 = $request->input()['telefono_dos'];
			$specialist->email = $request->input()['correo_electronico'];
			$specialist->name_assistant = $request->input()['nombres_asistente'];
			$specialist->phone1_assistant = $request->input()['telefono_uno_asistente'];
			$specialist->phone2_assistant = $request->input()['telefono_dos_asistente'];
			$specialist->email_assistant = $request->input()['correo_electronico_asistente'];			
			$specialist->description = $request->input()['descripcion'];
			$specialist->enity_id = $request->input()['entidad'];
					
			if($request->input()['edit']){
				//se pretende actualizar el rol
				try {
					$specialtyAffectedRows = Specialty::where('id', $request->input()['specialty_id'])->update(array(
						'name' => $specialty->name,
						'code' => $specialty->code,
						'description' => $specialty->description));
						
				}catch (\Illuminate\Database\QueryException $e) {
					$message = 'La especialidad o no se logro editar';
					return Redirect::to('especialidad/agregar')->with('error', $message)->withInput();
				}
				
				Session::flash('_old_input.nombre', $specialty->name);
				Session::flash('_old_input.codigo', $specialty->code);
				Session::flash('_old_input.descripcion', $specialty->description);
				Session::flash('_old_input.specialty_id', $request->input()['specialty_id']);
				Session::flash('_old_input.edit', true);
				Session::flash('titulo', 'Editar');
			
				return Redirect::to('especialista/listar')->withInput()->with('message', 'Especialidad editada exitosamente');
			
			}else{
				try {
					$specialist->save();
					//relacionamos las especialidades
					$array = Array();
					foreach($request->input() as $key=>$value){
						if(strpos($key,'espe_') !== false){
							$vector=explode('_',$key);
							$n=count($vector);
							$id_bne = end($vector);
							$array[$id_bne][$vector[1]] = $value;
						}
					}					
					foreach($array as $key => $vector){
						$speciallistspecialty = new SpecialistSpecialty;

						$speciallistspecialty->rate_particular= $vector['precioparticular'];
						$speciallistspecialty->rate_suscriptor= $vector['preciosuscriptor'];
						$speciallistspecialty->tiempo= $vector['duracion'];
						$speciallistspecialty->specialist_id = $specialist->id;//especialista
						$speciallistspecialty->specialty_id = $vector['especialidad'];//especialidad
						try {
							$speciallistspecialty->save();					
						}catch (\Illuminate\Database\QueryException $e) {										
							return Redirect::to('especialista/listar')->with('error', $e->getMessage())->withInput();
						}
					}

					$array = Array();
					foreach($request->input() as $key=>$value){
						if(strpos($key,'dispo_') !== false){
							$vector=explode('_',$key);
							$n=count($vector);
							$id_bne = end($vector);
							$array[$id_bne][$vector[1]] = $value;
						}
					}

					foreach($array as $key => $vector){
						$available = new Available;
						$available->day = $vector['dia'];
						$available->hour_start = $vector['horainicio'];
						$available->hour_end = $vector['horafin'];
						$available->specialist_id = $specialist->id;
						try {
							$available->save();					
						}catch (\Illuminate\Database\QueryException $e) {										
							return Redirect::to('especialista/listar')->with('error', $e->getMessage())->withInput();
						}

						//agregamos las especialidades a la disponibilidad
						$subentyties=explode(',',$vector['especialidades']);
						foreach($subentyties as $key => $subentidad){
							
							$availablespecialty = new AvailableSpecialty;
							$availablespecialty->available_id = $available->id;
							$availablespecialty->specialty_id = intval($subentidad);
							try {
								$availablespecialty->save();					
							}catch (\Illuminate\Database\QueryException $e) {
								return Redirect::to('especialista/listar')->with('error', $e->getMessage())->withInput();
							}

						}
						
					}					
					
					return Redirect::to('especialista/listar')->withInput()->with('message', 'Especialista agregado exitosamente');

				}catch (\Illuminate\Database\QueryException $e) {
					$message = 'El especialista no se logro agregar';
					return Redirect::to('especialista/agregar')->with('error', $e->getMessage())->withInput();
				}
			}
		}
	}
	public function getActualizar($id_app=null,$categoria=null,$id_mod=null,$id=null){
		if(is_null($id_mod)) return Redirect::to('/')->with('error', 'Este modulo no se puede alcanzar por url, solo es valido desde las opciones del menú');
		
		$specialty =
		Specialty::
		where('clu_specialty.id', $id)
		->get()
		->toArray();
				
		Session::flash('_old_input.especialidad', $specialty[0]['name']);
		Session::flash('_old_input.codigo', $specialty[0]['code']);
		Session::flash('_old_input.descripcion', $specialty[0]['description']);
		Session::flash('_old_input.specialty_id', $id);
		Session::flash('_old_input.edit', true);
		Session::flash('titulo', 'Editar');
		
		return Redirect::to('especialidad/agregar');
	}
	
	public function postBuscar(Request $request){
		$url = explode("/", Session::get('_previous.url'));
		$moduledata['fillable'] = ['Nombre','Identificación','Teléfono 1','Teléfono 2','Correo Electrónico'];		
		//estas opciones se usaran para pintar las opciones adecuadamente con respecto al modulo
		$moduledata['modulo'] = 'especialidades';
		$moduledata['id_app'] = '2';
		$moduledata['categoria'] = 'Componentes';
		$moduledata['id_mod'] = '10';
			
		Session::flash('modulo', $moduledata);
		Session::flash('filtro', $name);
		return view('club.especialidad.listar');
		
	}
	
	public function postVer(Request $request){
		return response()->json(['respuesta'=>true,'data'=>null]);	
	}
	
	public function postNuevo(Request $request){
		$entity=Entity::select('clu_entity.id','clu_entity.business_name')->where('clu_entity.active', 1)->get()->toArray();		
		if(count($entity))return response()->json(['respuesta'=>true,'data'=>$entity]);
		return response()->json(['respuesta'=>true,'data'=>null]);
	}

	public function postSelectentity(Request $request){
		//buscamos las sucursales de la entidad
		$subentity =
		Subentity::
		where('clu_subentity.entity_id', $request->input('id_entity'))
		->get()
		->toArray();
		if(count($subentity))return response()->json(['respuesta'=>true,'data'=>$subentity]);
		return response()->json(['respuesta'=>true,'data'=>null]);
	}
	
}
