<?php namespace App\Http\Controllers\Club;

use Validator;
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
		
	}
	
	//Función para la opción: agregar
	public function getCrear($id_app=null,$categoria=null,$id_mod=null){	
		
	}
	public function getAgregar(){
	
		
	}
	//función para guardar usuarios con su perfil
	public function postSave(Request $request){		
		
	}
	public function getActualizar($id_app=null,$categoria=null,$id_mod=null,$id=null){
		
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
