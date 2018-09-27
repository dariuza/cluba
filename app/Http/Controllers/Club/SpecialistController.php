<?php namespace App\Http\Controllers\Club;

use Validator;
use App\Core\Club\Specialist;
use App\Core\Club\Entity;
use App\Core\Club\Specialty;
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
		$moduledata['fillable'] = ['Nombre','Identificación','Teléfono 1','Teléfono 2','Correo Electrónico','Entidad'];
		//recuperamos las variables del controlador anterior ante el echo de una actualización de pagina
		$url = explode("/", Session::get('_previous.url'));
		
		//estas opciones se usaran para pintar las opciones adecuadamente con respecto al modulo
		$moduledata['modulo'] = 'especialistas';
		$moduledata['id_app'] = '2';
		$moduledata['categoria'] = 'Componentes';
		$moduledata['id_mod'] = '11';
		
		//consultamos las especialidades
		$especialidades_null = array("NO HAY ESPECIALIDADES");
		$specialties= \DB::table('clu_specialty')->where('clu_specialty.active',1)->get();
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
			select('clu_specialist.*','clu_entity.business_name')
			->leftjoin('clu_entity', 'clu_specialist.entity_id', '=', 'clu_entity.id')
			->where(function ($query) {
				$query->where('clu_specialist.name', 'like', '%'.Session::get('search').'%')				
				->orWhere('clu_specialist.identification', 'like', '%'.Session::get('search').'%')
				->orWhere('clu_entity.business_name', 'like', '%'.Session::get('search').'%');
			})
			->skip($request->input('start'))->take($request->input('length'))
			->get();
			$moduledata['filtro'] = count($moduledata['especialistas']);
		}else{
			$moduledata['especialistas']=
			\DB::table('clu_specialist')
			->select('clu_specialist.*','clu_entity.business_name')
			->leftjoin('clu_entity', 'clu_specialist.entity_id', '=', 'clu_entity.id')
			->skip($request->input('start'))
			->take($request->input('length'))
			->get();
		
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
	
		return view('club.especialista.agregar');
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
			$specialist->entity_id = $request->input()['entidad'];
					
			if($request->input()['edit']){
				//SE PRETENDE ACTUALIZAR LA ESPECIALIDAD
				$specialist = Specialist::find($request->input('specialist_id'));
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
				$specialist->entity_id = $request->input()['entidad'];

				try {
					$specialist->save();						
				}catch (\Illuminate\Database\QueryException $e) {
					$message = 'El especialista o no se logro editar';
					return Redirect::to('especialista/agregar')->with('error', $message)->withInput();
				}

				//editar las especialidades
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

					//verificar si en nuevo o si es una actualización
					if(array_key_exists('id',$vector)){
						//es una actualización
						$speciallistspecialty = SpecialistSpecialty::find($vector['id']);
						//verificamos si se quiere borrar
						if($vector['especialidad'] != 0){

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

						}else{
							try {
								$speciallistspecialty->delete();
							}catch (\Illuminate\Database\QueryException $e) {										
								return Redirect::to('especialista/listar')->with('error', $e->getMessage())->withInput();
							}
							
						}
						
					}else{

						//es uno nuevo
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
				}

				//disponibilidades

			    $array = Array();
				foreach($request->input() as $key=>$value){
					if(strpos($key,'dispo_') !== false){
						$vector=explode('_',$key);
						$n=count($vector);
						$id_bne = end($vector);
						$array[$id_bne][$vector[1]] = $value;
					}
				}

				//borramos todas las disponibilidaes_x_especialidad y luego las recreamos
				foreach($array as $key => $vector){

					//verificar si en nuevo o si es una actualización, idavailable
					if(array_key_exists('idavailable',$vector)){						
						//lo primero es borrar las disponibilidades
						if($vector['dia'] == "" ){
							//borramos la disponibilidad
							Available::where('id',$vector['idavailable'])->delete();
						}else{
							//actualizamos la disponibilidad
							$available = Available::find($vector['idavailable']);

							$available->day = $vector['dia'];
							$available->hour_start = $vector['horainicio'];
							$available->hour_end = $vector['horafin'];
							$available->subentity_id = $vector['subentityselect'];
							$available->specialist_id = $specialist->id;
							try {
								$available->save();					
							}catch (\Illuminate\Database\QueryException $e) {										
								return Redirect::to('especialista/listar')->with('error', $e->getMessage())->withInput();
							}


							//borramos los registros de id availablede clu_available_x_specialty
							AvailableSpecialty::where('clu_available_x_specialty.available_id',$vector['idavailable'])
							->delete();
							//creamos todos los registros para clu_available_x_specialty
							//agregamos las especialidades a la disponibilidad
							$disponibilidades=explode(',',$vector['especialidades']);
							foreach($disponibilidades as $key => $dispo){

								//antes de guardar miramos si todavia existe la disponivilidad
								
								$availablespecialty = new AvailableSpecialty;
								$availablespecialty->available_id = $vector['idavailable'];
								$availablespecialty->specialty_id = intval($dispo);
								$availablespecialty->subentity_id = $vector['subentityselect'];
								try {
									$availablespecialty->save();					
								}catch (\Illuminate\Database\QueryException $e) {
									return Redirect::to('especialista/listar')->with('error', $e->getMessage())->withInput();
								}

							}
						}
						
					}else{
						
						//nueva disponibilidad con su available
						if($vector['dia'] != "" ){
							$available = new Available;
							$available->day = $vector['dia'];
							$available->hour_start = $vector['horainicio'];
							$available->hour_end = $vector['horafin'];
							$available->subentity_id = $vector['subentityselect'];
							$available->specialist_id = $specialist->id;
							try {
								$available->save();					
							}catch (\Illuminate\Database\QueryException $e) {										
								return Redirect::to('especialista/listar')->with('error', $e->getMessage())->withInput();
							}
							
							//agregamos las especialidades a la disponibilidad
							$disponibilidades=explode(',',$vector['especialidades']);
							foreach($disponibilidades as $key => $dispo){
								
								$availablespecialty = new AvailableSpecialty;
								$availablespecialty->available_id = $available->id;
								$availablespecialty->specialty_id = intval($dispo);
								$availablespecialty->subentity_id = $vector['subentityselect'];
								try {
									$availablespecialty->save();					
								}catch (\Illuminate\Database\QueryException $e) {
									return Redirect::to('especialista/listar')->with('error', $e->getMessage())->withInput();
								}

							}
						}
						
					}
					

				}
							
				return Redirect::to('especialista/listar')->withInput()->with('message', 'Especialista '.$specialist->name.' editado exitosamente');
			
			}else{

				//ESPECIALISTA NUEVO

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
						if($vector['especialidad'] != "" ){
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
						
					}

					//vector de disponibilidades
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
						if($vector['dia'] != "" ){
							$available = new Available;
							$available->day = $vector['dia'];
							$available->hour_start = $vector['horainicio'];
							$available->hour_end = $vector['horafin'];
							$available->subentity_id = $vector['subentityselect'];
							$available->specialist_id = $specialist->id;
							try {
								$available->save();					
							}catch (\Illuminate\Database\QueryException $e) {										
								return Redirect::to('especialista/listar')->with('error', $e->getMessage())->withInput();
							}

							//agregamos las especialidades a la disponibilidad
							$disponibilidades=explode(',',$vector['especialidades']);
							foreach($disponibilidades as $key => $dispo){
								
								$availablespecialty = new AvailableSpecialty;
								$availablespecialty->available_id = $available->id;
								$availablespecialty->specialty_id = intval($dispo);
								$availablespecialty->subentity_id = $vector['subentityselect'];
								try {
									$availablespecialty->save();					
								}catch (\Illuminate\Database\QueryException $e) {
									return Redirect::to('especialista/listar')->with('error', $e->getMessage())->withInput();
								}

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

		//preparación de datos	

		//el especialista	
		$specialist =
		Specialist::
		where('clu_specialist.id', $id)
		->get()
		->toArray();

		//consultamos las especialidades
		$especialidades_null = array("NO HAY ESPECIALIDADES");
		$specialties= \DB::table('clu_specialty')->get();
		foreach ($specialties as $especial){
			$especialidades[$especial->id] = $especial->name;
		}
		$moduledata['especialidades']=$especialidades_null;	
		if(count($moduledata['especialidades'])) $moduledata['especialidades']=$especialidades;

		//especialidades por especialista
		$clu_specialist_x_specialty =
		SpecialistSpecialty::
		where('clu_specialist_x_specialty.specialist_id', $id)
		->get()
		->toArray();
		$moduledata['clu_specialist_x_specialty']=$clu_specialist_x_specialty;

		//disponibilidades por especialista
		$clu_available =
		Available::
		where('clu_available.specialist_id', $id)
		//->leftjoin('clu_specialty', 'clu_available.specialist_id', '=', 'clu_specialty.id')
		->get()
		->toArray();
		$moduledata['clu_available']=$clu_available;
		
		//disponibilidades por sucursales y especialidad
		$moduledata['dispo_espec']= array();		
		$clu_available_x_specialty =
		AvailableSpecialty::
		where(function($q) use ($clu_available){
			foreach($clu_available as $value){
				$q->orwhere('available_id', '=', $value['id']);
			}
		})
		->get()
		->toArray();
		$moduledata['clu_available_x_specialty']=$clu_available_x_specialty;

		//separamos el array
		$dispo_espec = array();
		foreach ($clu_available as $value) {
			$dispo_espec[$value['id']]=array();
		}
		if(count($dispo_espec)){
			foreach ($clu_available_x_specialty as $value) {
				$dispo_espec[$value['available_id']][count($dispo_espec[$value['available_id']])]=$value['specialty_id'];
			}
			$moduledata['dispo_espec']=$dispo_espec;
		}

				

		//entidades
		$entity = \DB::table('clu_entity')->get();		
		foreach ($entity as $en){			
			$entidades[$en->id] = $en->business_name;
		}		
		$moduledata['entidades']=$entidades;

		//sucursales de entidad
		$subentity = \DB::table('clu_subentity')->where('clu_subentity.entity_id',$specialist[0]['entity_id'])->get();		
		foreach ($subentity as $en){			
			$subentidades[$en->id] = $en->sucursal_name;
		}		
		$moduledata['subentidades']=$subentidades;

		//especialidades, 
		//hay que llevarlas todas ya que no hay una relación de especialidad con entidad
		$specialty = \DB::table('clu_specialty')->get();		
		foreach ($specialty as $es){			
			$especialidades[$es->id] = $es->name;
		}		
		$moduledata['especialidades']=$especialidades;
		
		//dd($moduledata);

		Session::flash('_old_input.entidad', $specialist[0]['entity_id']);
		Session::flash('_old_input.nombres', $specialist[0]['name']);
		Session::flash('_old_input.nombres_asistente', $specialist[0]['name_assistant']);
		Session::flash('_old_input.identificacion', $specialist[0]['identification']);
		Session::flash('_old_input.telefono_uno_asistente', $specialist[0]['phone1_assistant']);
		Session::flash('_old_input.telefono_uno', $specialist[0]['phone1']);
		Session::flash('_old_input.telefono_dos_asistente', $specialist[0]['phone2_assistant']);
		Session::flash('_old_input.telefono_dos', $specialist[0]['phone2']);
		Session::flash('_old_input.correo_electronico_asistente', $specialist[0]['email_assistant']);
		Session::flash('_old_input.correo_electronico', $specialist[0]['email']);
		Session::flash('_old_input.descripcion', $specialist[0]['description']);
		
		Session::flash('_old_input.specialist_id', $id);
		Session::flash('_old_input.edit', true);
		Session::flash('titulo', 'Editar');
		
		return Redirect::to('especialista/agregar')->with('modulo',$moduledata);		;
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
		//consulta de elementos
		//entidad de especialista
		$entity =
		Entity::
		where('clu_entity.id', $request->input('entity_id'))
		->get()
		->toArray();

		//especialidades del especialista
		$especialtys = \DB::table('clu_specialist_x_specialty')
		->where('clu_specialist_x_specialty.specialist_id', $request->input('id'))
		->leftjoin('clu_specialty', 'clu_specialist_x_specialty.specialty_id', '=', 'clu_specialty.id')			
		->get();

		//disponibilidadades
		$dispos = \DB::table('clu_available_x_specialty')		
		->leftjoin('clu_specialty', 'clu_available_x_specialty.specialty_id', '=', 'clu_specialty.id')			
		->leftjoin('clu_available', 'clu_available_x_specialty.available_id', '=', 'clu_available.id')			
		->where('clu_available.specialist_id', $request->input('id'))
		->get();

		if(count($entity)){
			return response()->json(['respuesta'=>true,'data'=>$entity,'specialidades'=>$especialtys,'dispos'=>$dispos]);	
		}
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
