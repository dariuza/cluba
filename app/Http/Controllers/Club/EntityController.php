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
		
	}
	public function getListar(){
		
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
	
	public function getEspecialidades($id_app=null,$categoria=null,$id_mod=null){	
		return redirect()->action('Club\SpecialtyController@getEnumerar', ['id_app' => $id_app, 'categoria'=>$categoria, 'id_mod'=>$id_mod]);
	}
	
	public function getEspecialistas($id_app=null,$categoria=null,$id_mod=null){		
		return redirect()->action('Club\SpecialistController@getEnumerar', ['id_app' => $id_app, 'categoria'=>$categoria, 'id_mod'=>$id_mod]);
	}
	
}
