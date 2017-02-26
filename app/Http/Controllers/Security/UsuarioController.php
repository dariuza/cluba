<?php namespace App\Http\Controllers\Security;

use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\Security\UserProfile;
use App\Core\Security\Rol;
use App\Core\Security\Aplications;
use App\Core\Security\AppUser;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

class UsuarioController extends Controller {

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
	//Función para mostar los datos de la opción: general
	public function getIndex($id=null, $modulo=null, $descripcion= null, $id_aplicacion = null, $categoria = null)
	{	
		//Modo de evitar que otros roles ingresen por la url
		if(is_null($id)) return Redirect::to('/')->with('error', 'Este modulo no se debe alcanzar por url, solo es valido desde las opciones del menú');
		//preparación de los datos
		$moduledata['id']=$id;
		$moduledata['modulo']=$modulo;		
		$moduledata['description']=$descripcion;
		$moduledata['id_aplicacion']=$id_aplicacion;
		$moduledata['categoria']=$categoria;
		
		//consultas de usuario		
		//total
		try {
			$moduledata['usuarios']=\DB::table('seg_user')
        	->select(\DB::raw('count(*) as total'))        	
        	->where('seg_user.active', '=' , Session::get('opaplus.usuario.lugar.active'))
        	->get()[0]->total;
		}catch (ModelNotFoundException $e) {
			$message = 'Problemas al hallar datos de usuario';
			return Redirect::to('usuario/general')->with('error', $message);
		}
		//total agrupado por roles		
        try {
        	$moduledata['roles']=\DB::table('seg_user')
            ->select('rol', \DB::raw('count(*) as total'))
            ->join('seg_rol', 'rol_id', '=', 'seg_rol.id')
            ->where('seg_user.active', '=' , Session::get('opaplus.usuario.lugar.active'))
            ->groupBy('rol_id')
            ->get();
        }catch (ModelNotFoundException $e) {
        	$message = 'Problemas al hallar datos de ro de usuario';
            return Redirect::to('usuario/general')->with('error', $message);
        }        
              
		return Redirect::to('usuario/general')->with('modulo',$moduledata);
		
	}
	public function getGeneral(){		
		if(is_null(Session::get('modulo.id'))) return Redirect::to('/')->with('error', 'Este modulo no se debe alcanzar por url, solo es valido desde las opciones del menú');
		return view('seguridad.usuario.usuario_index');
	}
	public function postBuscar(Request $request){		
				
		$url = explode("/", Session::get('_previous.url'));
		$moduledata['fillable'] = ['Nombre','Apellidos','Identificación','Correo Electronico','Dirección','Movil','Fijo','Edad','Fecha Nacimiento','Genero','Rol'];
		//estas opciones se usaran para pintar las opciones adecuadamente con respecto al modulo
		$moduledata['modulo'] = $url[count($url)-4];
		$moduledata['id_app'] = $url[count($url)-2];
		$moduledata['categoria'] = $url[count($url)-1];
		$moduledata['id_mod'] = $url[count($url)-5];	
		
		Session::flash('modulo', $moduledata);
		Session::flash('filtro', $request->input()['names']);
		return view('seguridad.usuario.listar');
	
	}
	//Funcion para la opción listar
	public function getEnumerar($id_app=null,$categoria=null,$id_mod=null){
		if(is_null($id_mod)) return Redirect::to('/')->with('error', 'Este modulo no se debe alcanzar por url, solo es valido desde las opciones del menú');
		return Redirect::to('usuario/listar');
	}
	public function getListar(){
		//las siguientes dos lineas solo son utiles cuando se refresca la pagina, ya que al refrescar no se pasa por el controlador
		$moduledata['fillable'] = ['Nombre','Apellidos','Identificación','Correo Electronico','Dirección','Movil','Fijo','Edad','Fecha Nacimiento','Genero','Rol'];
		//recuperamos las variables del controlador anterior ante el echo de una actualización de pagina
		$url = explode("/", Session::get('_previous.url'));
		
		//estas opciones se usaran para pintar las opciones adecuadamente con respecto al modulo
		/*
		$moduledata['modulo'] = $url[count($url)-5];
		$moduledata['id_app'] = $url[count($url)-3];		
		$moduledata['categoria'] = $url[count($url)-2];
		$moduledata['id_mod'] = $url[count($url)-1];
		*/		
		$moduledata['modulo'] = 'usuario';
		$moduledata['id_app'] = 1;
		$moduledata['categoria'] = 'Agentes';
		$moduledata['id_mod'] = 6;
		
		Session::flash('modulo', $moduledata);		
		return view('seguridad.usuario.listar');
	}
	//Funcion para retornar ante cambios en la tabla listada
	public function getListarajax(Request $request){		
		
		//otros parametros
		$moduledata['total']=User::
		where('seg_user.active', '=' ,  Session::get('opaplus.usuario.lugar.active'))
		->where('seg_user.rol_id','!=',1)
		->where('seg_user.rol_id','!=',3)
		->where('seg_user.rol_id','!=',7)
		->where('seg_user.rol_id','!=',8)
		->count();
				
		//realizamos la consulta
		if(!empty($request->input('search')['value'])){			
			Session::flash('search', $request->input('search')['value']);
			$moduledata['users']=
			User::
			where('seg_user.active', '=' ,  Session::get('opaplus.usuario.lugar.active'))
			->join('seg_user_profile','seg_user.id','=','seg_user_profile.user_id')
			->join('seg_rol','seg_user.rol_id','=','seg_rol.id')
			->where('seg_user.rol_id','!=',1)
			->where('seg_user.rol_id','!=',3)
			->where('seg_user.rol_id','!=',7)
			->where('seg_user.rol_id','!=',8)
			->where(function ($query) {
                $query->where('seg_user_profile.names', 'like', '%'.Session::get('search').'%')
                      ->orWhere('seg_user.email', 'like', '%'.Session::get('search').'%')
                      ->orWhere('seg_rol.rol', 'like', '%'.Session::get('search').'%')
                      ->orWhere('seg_user_profile.identificacion', 'like', '%'.Session::get('search').'%');
            })
            ->orderBy($request->input('columns')[$request->input('order')[0]['column']]['data'], $request->input('order')[0]['dir'])
			->skip($request->input('start'))->take($request->input('length'))
			->get();
            
			$moduledata['filtro'] = count($moduledata['users']);
		}else{			
			$moduledata['users']=
			User::			
			where('seg_user.active', '=' , Session::get('opaplus.usuario.lugar.active'))
			->join('seg_user_profile','seg_user.id','=','seg_user_profile.user_id')
			->join('seg_rol','seg_user.rol_id','=','seg_rol.id')
			->where('seg_user.rol_id','!=',1)
			->where('seg_user.rol_id','!=',3)
			->where('seg_user.rol_id','!=',7)
			->where('seg_user.rol_id','!=',8)
			->skip($request->input('start'))->take($request->input('length'))
			->orderBy($request->input('columns')[$request->input('order')[0]['column']]['data'], $request->input('order')[0]['dir'])
			->get();
			
			$moduledata['filtro'] = $moduledata['total'];		
		}	
		
		//consulta de las zonas
		foreach($moduledata['users'] as $usuario){
			//consultamos las ciudades
			if(!empty($usuario->zone)){
				$ciudades_id = explode(",", $usuario->zone);
				$zonas=\DB::table('clu_city')->select('city')
				->where(function($q) use ($ciudades_id){
					foreach($ciudades_id as $key => $value){
						$q->orwhere('id', '=', $value);
					}
				})->get();
					
				foreach($zonas as $zona){
					$ciudades[] = $zona->city;
				}
					
				$usuario->ciudades = implode(",",$ciudades);
			}
		}
		
		return response()->json(['draw'=>$request->input('draw')+1,'recordsTotal'=>$moduledata['total'],'recordsFiltered'=>$moduledata['filtro'],'data'=>$moduledata['users']]);
	}
	//Función para la opción: agregar
	public function getCrear($id_app=null,$categoria=null,$id_mod=null){
		
		//Modo de evitar que otros roles ingresen por la url
		if(is_null($id_mod)) return Redirect::to('/')->with('error', 'Este modulo no se puede alcanzar por url, solo es valido desde las opciones del menú');
		$moduledata['id']=$id_mod;
		$moduledata['id_app']=$id_app;
				
		//preparación de los datos
		//consultamos los roles
		$rols = \DB::table('seg_rol')
		->where('id','!=',1)
		->where('id','!=',3)
		->get();
				
		foreach ($rols as $rol){
			$roles[$rol->id] = $rol->rol;
		}
		$moduledata['roles']=$roles;
		
		//consultamos las ciudades
		$cities = \DB::table('clu_city')->get();
		foreach ($cities as $city){
			$ciudades[$city->id] = $city->city;
		}
		$moduledata['ciudades']=$ciudades;
		
		//preparamos las aplicaciones
		$moduledata['apps']=Aplications::all()->toArray();
		
		return Redirect::to('usuario/agregar')->with('modulo',$moduledata);
	}
	public function getAgregar(){	
		
		return view('seguridad.usuario.agregar');
	}
	//función para guardar usuarios con su perfil
	public function postSave(Request $request){
				
		$array_input = array();
		$array_input['_token'] = $request->input('_token');
		$array_input['email'] = $request->input('email');
		$array_input['name'] = $request->input('name');
		$array_input['password'] = $request->input('password');
		foreach($request->input() as $key=>$value){
			if($key != "_token" && $key != "email" && $key != "name" && $key != "password"){
				$array_input[$key] = strtoupper($value);
			}
		}
		$request->replace($array_input);
				
		$checkbox = Array();
		foreach($request->input() as $key=>$value){
			if(strpos($key,$request->input()['mod_id']) !== false) $checkbox[$key] = $value;			
		}
		
		//enviamos el mod id
		$moduledata['id']=$request->input()['mod_id'];
				
		//reconstruimos el vector de roles, esto hay que hacerlo de otro modo
		$rols = \DB::table('seg_rol')
		->where('id','!=',1)
		->where('id','!=',3)
		->get();
				
		foreach ($rols as $rol){
			$roles[$rol->id] = $rol->rol;
		}
		$moduledata['roles']=$roles;
		
		//preparamos las aplicaciones
		$moduledata['apps']=Aplications::all()->toArray();	
		
		//consultamos las ciudades
		$cities = \DB::table('clu_city')->get();
		foreach ($cities as $city){
			$ciudades[$city->id] = $city->city;
		}
		$moduledata['ciudades']=$ciudades;
		
		//zonas del usuario
		$moduledata['zonas']=explode(",", $request->input()['zone']);
		
		//calculo de fechas para mayores de 18 años
		$hoy = date('Y-m-j');
		$fecha = strtotime('-18 year',strtotime($hoy));
		$fecha = date('Y-m-j',$fecha);
		
		$messages = [
			'required' => 'El campo :attribute es requerido.',
			'size' => 'La :attribute deberia ser mayor a :size.',
			'min' => 'La :attribute deberia tener almenos :min. caracteres',
			'max' => 'La :attribute no debe tener maximo :max. caracteres',
			'numeric' => 'El :attribute  debe ser un número',
			'before' => "El :attribute  debe menor a: $fecha",
		];
		
		$rules = array(
			'name'    => 'required|min:4|max:60',					
			'email'    => 'required|min:4|max:60',
			'names'    => 'required|min:4|max:60', // make sure the username field is not empty
			'surnames' => 'required|min:4|max:60',			
			'rol'    => 'required',
			//'sex' => 'required',
			//'adress' => 'required',
			'movil_number' => 'numeric',
			'fix_number' => 'numeric',
			'birthdate' => "date|before:$fecha",
			'date_start' => "date",
		);
		
		
		/*
		$messages2 = [
			'required' => 'El campo :attribute es requerido.',				
			'mimes' => 'La :attribute debe ser de tipo jpeg, png o bmp',
		];
		$rules2 = array(
				'image' => 'required|mimes:jpeg,bmp,png',
		);
		$validator2 = Validator::make($file, $rules2,$messages2);
		if ($validator2->fails()) {
			return Redirect::back()->withErrors($validator)->withInput()->with('modulo',$moduledata);
		}
		*/
		$validator = Validator::make($request->input(), $rules, $messages);		
		if ($validator->fails()) {				
			return Redirect::back()->withErrors($validator)->withInput()->with('modulo',$moduledata);
		}else{		
			
			$user = new User();
			$userprofile = new UserProfile();		
						
			$user->name = $request->input()['name'];
			$user->ip = $request->server()['REMOTE_ADDR'];
			$user->email = $request->input()['email'];
			$user->password = '0000';
			if(!empty($request->input()['password'])){
				$user->password = $request->input()['password'];					
			}			
			$user->rol_id = $request->input()['rol'];
			$user->login = 0;
			$user->id = $request->input()['user_id'];
			
			//preparación de imagen
			$file = array('image' => Input::file('image'));
			
			$fileName = 'default.png';
			$bandera_file = false;
			if (Input::file('image')) {
				$destinationPath = 'images/user';
				$extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
				$fileName = rand(11111,99999).'.'.$extension; // renameing image
				Input::file('image')->move($destinationPath, $fileName); // uploading file to given path	$userprofile = UserProfile::find(Session::get('opaplus.usuario.id'));
				$bandera_file = true;
				//return Redirect::back()->with('message', 'Correcta actualización de imagen de perfil');
			}else{
				//$message = 'Archivo Imagen Invalido';
				//return Redirect::back()->with('error', $message)->with('modulo',$moduledata);
			}
			
			//verificacion del code_adviser
			//$old_profile_user = \DB::table('seg_user_profile')->where('user_id', $user->id)->get()[0];
			//if($old_profile_user->code_adviser != $request->input()['code_adviser']){
			
			/*CODIGO DE EMPLEADO FALTA POR 
			
			if($request->input()['rol'] == '2' || $request->input()['rol'] == '4'|| $request->input()['rol'] == '5' || $request->input()['rol'] == '6'){
				if(count(\DB::table('seg_user_profile')->where('code_adviser', $request->input()['code_adviser'])->get())){
					//existe el codigo
					return Redirect::to('usuario/agregar')->with('error', 'Codigo de Empleado invalido, ya existe otro empleado con codigo: '.$request->input()['code_adviser'])->withInput()->with('modulo',$moduledata);
				}				
			}
			
			*/
			
			//}
						
			if($request->input()['edit']){
				//se pretende actualizar el usuario				
				try {
					//modificación de password
					if(!empty($request->input()['password'])){
						$userAffectedRows = User::where('id', $request->input()['user_id'])->update(array('ip' => $user->ip,'name' => $user->name,'password' => $user->password,'email' => $user->email,'rol_id' => $user->rol_id));						
					}else{
						$userAffectedRows = User::where('id', $request->input()['user_id'])->update(array('ip' => $user->ip,'name' => $user->name,'email' => $user->email,'rol_id' => $user->rol_id));
					}
					
					
					//borramos todas las entradas del usuario
					AppUser::where('user_id', (int)$request->input()['user_id'])->delete();
					//relacion de aplicaciones nuevas					
					foreach($checkbox as $value){
						$user_app = new AppUser();
						$user_app->app_id = (int)$value;
						$user_app->user_id = (int)$request->input()['user_id'];
						$user_app->active = 1;						
						$user_app->save();
					}	
					
					//se calcula las app con las cuales quedo el usuario
					$array=
					User::
					select('seg_app.id')
					->where('seg_user.id', $request->input()['user_id'])
					->where('seg_app_x_user.active', 1)
					->join('seg_app_x_user','seg_user.id','=','seg_app_x_user.user_id')
					->join('seg_app','seg_app_x_user.app_id','=','seg_app.id')
					->get()
					->toArray();
					
					$user_apps = Array();
					foreach($array as $value)$user_apps[]=$value['id'];
					$moduledata['user_apps']=$user_apps;
					
					if(!$bandera_file)$fileName = UserProfile::where('user_id', (int)$request->input()['user_id'])->get()->toArray()[0]['avatar'];
					
				}catch (\Illuminate\Database\QueryException $e) {
					$message = 'El nombre de usuario o el correo ya existe - editar';
					return Redirect::to('usuario/agregar')->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
				}
			}else{
				
				try {
					//guardado de usuario nuevo
					$user->save();
					//relacion de aplicaciones
					foreach($checkbox as $value){
						$user_app = new AppUser();
						$user_app->app_id = (int)$value;
						$user_app->user_id = $user->id;						
						$user_app->save();
					}					
				}catch (\Illuminate\Database\QueryException $e) {
					//eliminamos el usuario
					//User::destroy($user->id);
					$message = 'El nombre de usuario o el correo ya existe - agregar';
					return Redirect::to('usuario/agregar')->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
				}				
			}
			
			$userprofile ->identificacion =  $request->input()['identificacion'];
			$userprofile ->type_id =  $request->input()['type_id'];
			$userprofile ->names =  $request->input()['names'];			
			$userprofile ->surnames =  $request->input()['surnames'];			
			$userprofile ->birthdate =  $request->input()['birthdate'];	
			$userprofile ->birthplace =  $request->input()['birthplace'];				
			$userprofile ->sex =  $request->input()['sex'];	
			$userprofile ->civil_status =  $request->input()['civil_status'];
			$userprofile ->adress =  $request->input()['adress'];
			$userprofile ->city =  $request->input()['city'];
			$userprofile ->neighborhood =  $request->input()['neighborhood'];				
			$userprofile ->description =  $request->input()['perfil_description'];			
			$userprofile ->movil_number =  $request->input()['movil_number'];
			$userprofile ->fix_number =  $request->input()['fix_number'];
			$userprofile ->date_start =  $request->input()['date_start'];
			$userprofile ->code_adviser =  $request->input()['code_adviser'];
			$userprofile ->zone = $request->input()['zone'];
			
			/*Esto se guarda por el modulo de suscripción
			$userprofile ->home =  $request->input()['home'];
			$userprofile ->profession =  $request->input()['profession'];
			$userprofile ->paymentadress =  $request->input()['paymentadress'];						
			$userprofile ->reference =  $request->input()['reference'];			
			$userprofile ->reference_adress =  $request->input()['reference_adress'];
			$userprofile ->reference_phone =  $request->input()['reference_phone'];
			*/
			$userprofile ->salary =  $request->input()['salary'];			
			$userprofile ->template =  'default';
			$userprofile ->location =  57;
			$userprofile ->avatar =  $fileName;			
			$userprofile ->user_id =  $user->id;			
			
			if($request->input()['edit']){
				//se pretende actualizar el usuario				
				try {					
					$userProfileAffectedRows = UserProfile::where('user_id', $user->id)->update(array(
					'identificacion' => $userprofile->identificacion,
					'type_id' => $userprofile->type_id,
					'names' => $userprofile->names,
					'surnames' => $userprofile->surnames,
					'birthdate' => $userprofile->birthdate,
					'birthplace' => $userprofile->birthplace,
					'sex' => $userprofile->sex,
					'adress' => $userprofile->adress,
					'city' => $userprofile->city,
					'neighborhood' => $userprofile->neighborhood,
					'home' => $userprofile->home,
					'description' => $userprofile->description,
					'movil_number' => $userprofile->movil_number,
					'fix_number' => $userprofile->fix_number,
					'date_start' => $userprofile->date_start,
					'code_adviser' => $userprofile->code_adviser,
					'zone' => $userprofile->zone,
					/*		
					'profession' => $userprofile->profession,
					'paymentadress' => $userprofile->paymentadress,									
					'reference' => $userprofile->reference,
					'reference_adress' => $userprofile->reference_adress,
					'reference_phone' => $userprofile->reference_phone,
					*/
					'salary' => $userprofile->salary,
					'avatar' => $userprofile->avatar));
				}catch (\Illuminate\Database\QueryException $e) {
					$message = 'La identificación de usuario ya existe - edición';
					return Redirect::to('usuario/agregar')->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
				}
				
				Session::flash('_old_input.name', $request->input()['name']);
				Session::flash('_old_input.email', $request->input()['email']);
				Session::flash('_old_input.rol_id', $request->input()['rol']);
				Session::flash('_old_input.names', $request->input()['names']);
				Session::flash('_old_input.surnames', $request->input()['surnames']);
				Session::flash('_old_input.identificacion', $request->input()['identificacion']);
				Session::flash('_old_input.type_id', $request->input()['type_id']);
				Session::flash('_old_input.birthdate', $request->input()['birthdate']);
				Session::flash('_old_input.birthplace', $request->input()['birthplace']);
				Session::flash('_old_input.sex', $request->input()['sex']);
				Session::flash('_old_input.civil_status', $request->input()['civil_status']);
				Session::flash('_old_input.adress', $request->input()['adress']);
				Session::flash('_old_input.city', $request->input()['city']);
				Session::flash('_old_input.neighborhood', $request->input()['neighborhood']);				
				Session::flash('_old_input.movil_number', $request->input()['movil_number']);
				Session::flash('_old_input.fix_number', $request->input()['fix_number']);
				Session::flash('_old_input.date_start', $request->input()['date_start']);
				Session::flash('_old_input.code_adviser', $request->input()['code_adviser']);
				Session::flash('_old_input.zone', $request->input()['zone']);
				Session::flash('_old_input.zone_select', $moduledata['zonas']);
				/*
				Session::flash('_old_input.home', $request->input()['home']);
				Session::flash('_old_input.profession', $request->input()['profession']);
				Session::flash('_old_input.paymentadress', $request->input()['paymentadress']);							
				Session::flash('_old_input.reference', $request->input()['reference']);
				Session::flash('_old_input.reference_adress', $request->input()['reference_adress']);
				Session::flash('_old_input.reference_phone', $request->input()['reference_phone']);
				*/
				Session::flash('_old_input.salary', $request->input()['salary']);
				Session::flash('_old_input.perfil_description', $request->input()['perfil_description']);
				Session::flash('_old_input.avatar',  $fileName);				
				Session::flash('_old_input.user_id', $request->input()['user_id']);
				Session::flash('_old_input.edit', true);
				Session::flash('titulo', 'Editar');
				
				return Redirect::to('usuario/agregar')->with('modulo',$moduledata)->with('message', 'Usuario editado adecuadamente');
			}else{
				//nuevo usuario
				try {
					//vericficamos la unicidad del code	
					if(count(\DB::table('seg_user_profile')->where('code_adviser', $request->input()['code_adviser'])->get())){
						User::destroy($user->id);
						$message = 'El codigo ya se esta usando para otro asesor';
						return Redirect::back()->with('error', $message)->withInput()->with('modulo',$moduledata);
					}
					
					$userprofile->save();
				}catch (\Illuminate\Database\QueryException $e) {
					//eliminamos el usuario					
					User::destroy($user->id);
					$message = 'La identificación de usuario ya existe - edición';
					return Redirect::back()->with('error', $e->getMessage())->withInput()->with('modulo',$moduledata);
					//return Redirect::back()->withErrors($message);
				}
				return Redirect::back()->with('modulo',$moduledata)->with('message', 'Usuario creado adecuadamente');
				
			}
							
		}
	}
	
	public function getActualizar($id_app=null,$categoria=null,$id_mod=null,$id=null){
		if(is_null($id_mod)) return Redirect::to('/')->with('error', 'Este modulo no se puede alcanzar por url, solo es valido desde las opciones del menú');
		//preparación de los datos
		//consultamos los roles, para la pagina de ingreso de usuario
		$moduledata['id']=$id_mod;
		
		$rols = \DB::table('seg_rol')
		->where('id','!=',1)
		->where('id','!=',3)
		->get();
				
		foreach ($rols as $rol){
			$roles[$rol->id] = $rol->rol;
		}
		$moduledata['roles']=$roles;
		
		//consultamos las ciudades
		$cities = \DB::table('clu_city')->get();
		foreach ($cities as $city){
			$ciudades[$city->id] = $city->city;
		}
		$moduledata['ciudades']=$ciudades;
		
		//preparamos als aplicaciones
		$moduledata['apps']=Aplications::all()->toArray();
		
		/* se consultan los datos por actualizar del usuario
		 * con respecto al $id
		 * */		
		$user =
		User::
		where('seg_user.id', $id)
		->join('seg_user_profile','seg_user.id','=','seg_user_profile.user_id')
		->join('seg_rol','seg_user.rol_id','=','seg_rol.id')
		->get()
		->toArray();
		
		//se consultan las app que posee el usuario
		$array=
		User::
		select('seg_app.id')
		->where('seg_user.id', $id)
		->where('seg_app_x_user.active', 1)
		->join('seg_app_x_user','seg_user.id','=','seg_app_x_user.user_id')
		->join('seg_app','seg_app_x_user.app_id','=','seg_app.id')
		->get()
		->toArray();
		
		$user_apps = Array();
		foreach($array as $value)$user_apps[]=$value['id'];
		$moduledata['user_apps']=$user_apps;
		
		//zonas del usuario
		$moduledata['zonas']=explode(",", $user[0]['zone']);
		
		Session::flash('_old_input.name', $user[0]['name']);		
		Session::flash('_old_input.email', $user[0]['email']);
		Session::flash('_old_input.rol_id', $user[0]['rol_id']);
		Session::flash('_old_input.names', $user[0]['names']);
		Session::flash('_old_input.surnames', $user[0]['surnames']);
		Session::flash('_old_input.identificacion', $user[0]['identificacion']);
		Session::flash('_old_input.type_id', $user[0]['type_id']);
		Session::flash('_old_input.birthdate', $user[0]['birthdate']);	
		Session::flash('_old_input.birthplace', $user[0]['birthplace']);	
		Session::flash('_old_input.sex', $user[0]['sex']);
		Session::flash('_old_input.civil_status', $user[0]['civil_status']);
		Session::flash('_old_input.adress', $user[0]['adress']);
		Session::flash('_old_input.city', $user[0]['city']);
		Session::flash('_old_input.neighborhood', $user[0]['neighborhood']);		
		Session::flash('_old_input.movil_number', $user[0]['movil_number']);
		Session::flash('_old_input.fix_number', $user[0]['fix_number']);
		Session::flash('_old_input.date_start', $user[0]['date_start']);
		Session::flash('_old_input.date_out', $user[0]['date_out']);
		Session::flash('_old_input.code_adviser', $user[0]['code_adviser']);
		Session::flash('_old_input.zone', $user[0]['zone']);
		Session::flash('_old_input.zone_select', $moduledata['zonas']);
		/*
		Session::flash('_old_input.home', $user[0]['home']);
		Session::flash('_old_input.profession', $user[0]['profession']);
		Session::flash('_old_input.paymentadress', $user[0]['paymentadress']);		
		*/		
		Session::flash('_old_input.reference', $user[0]['reference']);
		Session::flash('_old_input.reference_adress', $user[0]['reference_adress']);
		Session::flash('_old_input.reference_phone', $user[0]['reference_phone']);
		Session::flash('_old_input.salary', $user[0]['salary']);
		Session::flash('_old_input.perfil_description', $user[0]['description']);
		Session::flash('_old_input.avatar', $user[0]['avatar']);
		Session::flash('_old_input.user_id', $id);
		Session::flash('_old_input.edit', true);
		Session::flash('titulo', 'Editar');
		
		return Redirect::to('usuario/agregar')->with('modulo',$moduledata);		
	}
		
	public function postLugar(Request $request){		
		/*Consultamos el usuario
		 * */		
		$user =
		User::
		where('seg_user.id', $request->input()['id'])
		->join('seg_user_profile','seg_user.id','=','seg_user_profile.user_id')
		->join('seg_rol','seg_user.rol_id','=','seg_rol.id')
		->get()
		->toArray();
		
		/*Detección de oparacion
		 * */
		$array = Array();
		$array['opp'] = $request->input()['activo'];
		
		$array['respuesta'] = false;
		if(count($user)){
			/*El usuario existe
			 * */			
			$userAffectedRows = User::where('id', $request->input()['id'])->update(array('active' => $request->input()['activo']));
			$array['respuesta'] = false;
			if($userAffectedRows) $array['respuesta'] = true;	
			
		}		
				
		return response()->json(['respuesta'=>true,'data'=>$array]);
		
	}
	
	public function postVer(Request $request){
		
		$user =
		User::
		select('seg_app.app', 'seg_app.description', 'seg_app.preferences', 'seg_app_x_user.active')
		->where('seg_user.id', $request->input()['id'])
		->join('seg_app_x_user','seg_user.id','=','seg_app_x_user.user_id')
		->join('seg_app','seg_app_x_user.app_id','=','seg_app.id')		
		->get()
		->toArray();
		
		if(count($user)){
			return response()->json(['respuesta'=>true,'data'=>$user]);
		}
		return response()->json(['respuesta'=>false,'data'=>null]);		
	}
	
	
}