<?php namespace App\Http\Controllers\Club;

use Validator;
use App\Core\Club\Specialty;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class SpecialtyController extends Controller {
	
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
		
		return Redirect::to('especialidad/listar');
	}
	public function getListar(){
		//las siguientes dos lineas solo son utiles cuando se refresca la pagina, ya que al refrescar no se pasa por el controlador
		$moduledata['fillable'] = ['Especialidad','Codigo','Descripción'];
		//recuperamos las variables del controlador anterior ante el echo de una actualización de pagina
		$url = explode("/", Session::get('_previous.url'));
		
		//estas opciones se usaran para pintar las opciones adecuadamente con respecto al modulo
		$moduledata['modulo'] = 'especialidades';
		$moduledata['id_app'] = '2';
		$moduledata['categoria'] = 'Componentes';
		$moduledata['id_mod'] = '10';
		
		//para llevar temporalmente las variables a la vista.
		Session::flash('modulo', $moduledata);
		
		return view('club.especialidad.listar');
	}
	
	public function getListarajax(Request $request){
		//otros parametros
		$moduledata['total']=Specialty::count();
		
		//realizamos la consulta
		if(!empty($request->input('search')['value'])){
			Session::flash('search', $request->input('search')['value']);
				
			$moduledata['especialidades']=
			Specialty::
			where(function ($query) {
				$query->where('clu_specialty.name', 'like', '%'.Session::get('search').'%')				
				->orWhere('clu_specialty.code', 'like', '%'.Session::get('search').'%');
			})
			->skip($request->input('start'))->take($request->input('length'))
			->get();
			$moduledata['filtro'] = count($moduledata['especialidades']);
		}else{
			$moduledata['especialidades']=\DB::table('clu_specialty')->skip($request->input('start'))->take($request->input('length'))->get();
		
			$moduledata['filtro'] = $moduledata['total'];
		}
		
		return response()->json(['draw'=>$request->input('draw')+1,'recordsTotal'=>$moduledata['total'],'recordsFiltered'=>$moduledata['filtro'],'data'=>$moduledata['especialidades']]);
		
	}
	
	//Función para la opción: agregar
	public function getCrear($id_app=null,$categoria=null,$id_mod=null){
		//Modo de evitar que otros roles ingresen por la url
		if(is_null($id_mod)) return Redirect::to('/')->with('error', 'Este modulo no se puede alcanzar por url, solo es valido desde las opciones del menú');
			
		return Redirect::to('especialidad/agregar');
	}
	
	public function getAgregar(){
	
		return view('club.especialidad.agregar');
	}
	
	//función para guardar usuarios con su perfil
	public function postSave(Request $request){
		$array_input = array();
		$array_input['_token'] = $request->input('_token');		
		foreach($request->input() as $key=>$value){			
			$array_input[$key] = strtoupper($value);			
		}
		$request->replace($array_input);
		$messages = [
			'required' => 'El campo :attribute es requerido.',
		];
		
		$rules = array(
			'especialidad'=>'required',			
		);
		$validator = Validator::make($request->input(), $rules, $messages);
		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}else{
			$specialty = new Specialty();
				
			$specialty->name = $request->input()['especialidad'];
			$specialty->code = $request->input()['codigo'];
			$specialty->description = $request->input()['descripcion'];			
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
			
				return Redirect::to('especialidad/agregar')->withInput()->with('message', 'Especialidad editada exitosamente');
			
			}else{
				try {
					$specialty->save();
					return Redirect::to('especialidad/agregar')->withInput()->with('message', 'Especialidad agregada exitosamente');
				}catch (\Illuminate\Database\QueryException $e) {
					$message = 'La especialidad no se logro agregar';
					return Redirect::to('especialidad/agregar')->with('error', $e->getMessage())->withInput();
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
		$moduledata['fillable'] = ['Especialidad','Codigo','Descripción'];		
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
	
}
