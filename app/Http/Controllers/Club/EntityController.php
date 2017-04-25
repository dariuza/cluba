<?php namespace App\Http\Controllers\Club;

use Validator;
use App\Core\Club\Entity;
use App\Core\Club\Subentity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class EntityController extends Controller {
	
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
		
		return Redirect::to('entidad/listar');
	}
	public function getListar(){
		
		$moduledata['fillable'] = ['Nombre','Nit','Representante Legal','Contacto RLegal','Telefono 1','Telefono 2','Correo Electrónico'];
		//recuperamos las variables del controlador anterior ante el echo de una actualización de pagina
		$url = explode("/", Session::get('_previous.url'));
		
		//estas opciones se usaran para pintar las opciones adecuadamente con respecto al modulo
		$moduledata['modulo'] = 'entidad';
		$moduledata['id_app'] = '2';
		$moduledata['categoria'] = 'Componentes';
		$moduledata['id_mod'] = '9';//este es el que manda la parada para la generación de opciones

		//para llevar temporalmente las variables a la vista.
		Session::flash('modulo', $moduledata);

		return view('club.entidad.listar');
	}
	public function getListarajax(Request $request){
		$moduledata['total']=Entity::count();
		//realizamos la consulta
		if(!empty($request->input('search')['value'])){
			Session::flash('search', $request->input('search')['value']);			
			
			$moduledata['entidades']=
			Entity::			
			where(function ($query) {
				$query->where('clu_entity.business_name', 'like', '%'.Session::get('search').'%')
				->orWhere('clu_entity.nit', 'like', '%'.Session::get('search').'%')
				->orWhere('clu_entity.legal_representative', 'like', '%'.Session::get('search').'%')
				->orWhere('clu_entity.contact_representative', 'like', '%'.Session::get('search').'%')
				->orWhere('clu_entity.phone1_contact', 'like', '%'.Session::get('search').'%')
				->orWhere('clu_entity.phone2_contact', 'like', '%'.Session::get('search').'%')	
				->orWhere('clu_entity.email_contact', 'like', '%'.Session::get('search').'%');			
			})
			->skip($request->input('start'))->take($request->input('length'))
			->get();		
			$moduledata['filtro'] = count($moduledata['entidades']);
		}else{			
			$moduledata['entidades']=\DB::table('clu_entity')->skip($request->input('start'))->take($request->input('length'))->get();
				
			$moduledata['filtro'] = $moduledata['total'];
		}
		
		return response()->json(['draw'=>$request->input('draw')+1,'recordsTotal'=>$moduledata['total'],'recordsFiltered'=>$moduledata['filtro'],'data'=>$moduledata['entidades']]);
		
	}
	
	//Función para la opción: agregar
	public function getCrear($id_app=null,$categoria=null,$id_mod=null){	
		
	}
	public function getAgregar(){
	
		
	}
	//función para guardar usuarios con su perfil
	public function postSave(Request $request){

		//preparación de datos

		$messages = [
			'required' => 'El campo :attribute es requerido.',				
		];
		
		$rules = array(
			'nombre'    => 'required',
			'nit' => 'required',
			//'titular_id' => 'required',
		);
		
		$validator = Validator::make($request->input(), $rules, $messages);
		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}else{
			$entidad = new Entity();
			$entidad->business_name = strtoupper($request->input()['nombre']);
			$entidad->nit = $request->input()['nit'];
			$entidad->legal_representative = strtoupper($request->input()['representante_legal']);
			$entidad->contact_representative = strtoupper($request->input()['contancto_rlegal']);
			$entidad->phone1_contact = $request->input()['telefono1'];
			$entidad->phone2_contact = $request->input()['telefono2'];
			$entidad->email_contact = $request->input()['correo'];
			$entidad->description = $request->input()['descripcion'];

			if($request->input()['edit']){
				//editar entidad
			}else{
				//nueva entidad
				try {
					$entidad->save();					
				}catch (\Illuminate\Database\QueryException $e) {										
					return Redirect::to('entidad/listar')->with('error', $e->getMessage())->withInput();
				}	
			}

			//agregar las suscripciones
			$array = Array();
			foreach($request->input() as $key=>$value){
				if(strpos($key,'subent_') !== false){
					$vector=explode('_',$key);
					$n=count($vector);
					$id_bne = end($vector);
					//$array[$vector[$n-2]][$id_bne][$vector[1]] = strtoupper($value);
					if($vector[1] != 'nombre'){
						$array[$id_bne][$vector[1]] = ($value);
					}else{
						$array[$id_bne][$vector[1]] = strtoupper($value);
					}
					
				}
			}
			
			foreach($array as $key => $vector){
				$subentidad = new Subentity();
				if($vector['nombre'] != '' ) {
					//podemos guardar
					$subentidad->sucursal_name = $vector['nombre'];
					$subentidad->adress = $vector['direccion'];
					$subentidad->phone1_contact = $vector['telefonouno'];
					$subentidad->phone2_contact = $vector['telefonodos'];
					$subentidad->email_contact = $vector['email'];
					$subentidad->entity_id =$entidad->id;
					try {
						$subentidad->save();					
					}catch (\Illuminate\Database\QueryException $e) {										
						return Redirect::to('entidad/listar')->with('error', $e->getMessage())->withInput();
					}	
				}
				
			}

			return Redirect::to('entidad/listar')->withInput()->with('message', 'Entidad agregada exitosamente');
		}
		
	}
	public function getActualizar($id_app=null,$categoria=null,$id_mod=null,$id=null){
		
	}

	public function postVer(Request $request){

		$entidad =
		Entity::		
		where('clu_entity.id', $request->input()['id'])		
		->get()
		->toArray();
		
		$array['entidad'] = $entidad;

		$sucursales =
		Subentity::		
		where('clu_subentity.entity_id', $request->input()['id'])		
		->get()
		->toArray();
		
		$array['sucursales'] = $sucursales;

		if(count($entidad)){
			return response()->json(['respuesta'=>true,'data'=>$array]);
		}
		

		return response()->json(['respuesta'=>true,'data'=>null]);
	}
	
	public function postBuscar(Request $request){
	
	}

	public function postNuevo(Request $request){
		//consultas necesarias
		return response()->json(['respuesta'=>true,'data'=>null]);
	}
	
	public function getEspecialidades($id_app=null,$categoria=null,$id_mod=null){	
		return redirect()->action('Club\SpecialtyController@getEnumerar', ['id_app' => $id_app, 'categoria'=>$categoria, 'id_mod'=>$id_mod]);
	}
	
	public function getEspecialistas($id_app=null,$categoria=null,$id_mod=null){		
		return redirect()->action('Club\SpecialistController@getEnumerar', ['id_app' => $id_app, 'categoria'=>$categoria, 'id_mod'=>$id_mod]);
	}
	
}
