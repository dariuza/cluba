<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('ingreso',[
	'uses' => 'Auth\KeyController@getLogin',
	'as' => 'login'
]);
Route::post('llave_acceso',[
	'uses' => 'Auth\KeyController@getAcces',
	'as' => 'keygenAcces'
]);
Route::get('recuperar_contrasenia/{user}/{psw}',[
	'uses' => 'Auth\KeyController@getRecoverpsw',
	'as' => 'recoverPassword',		
]);


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
Route::group(['middleware' => 'guest'], function () {
	Route::get('/',[
		'uses' => 'WelcomeController@index',
		'as' => 'home'
	]);
	Route::get('salida_segura',[
		'uses' => 'Auth\KeyController@getLogout',
		'as' => 'keygenOut'
	]);
	Route::get('perfil_usuario',[
		'uses' => 'Security\UserController@getPerfil',
		'as' => 'UserPerfil'
	]);
	Route::get('buzon_usuario',[
		'uses' => 'Security\UserController@getBuzon',
		'as' => 'UserMailBox'
	]);
	/*
	Route::get('/{id}', function ($id) {
		return 'User '.$id;
	});	
	*/	

});

Route::controllers([
	'auth' => 'Auth\KeyController',
	'user' => 'Security\UserController',
	'usuario' => 'Security\UsuarioController',
	'rol' => 'Security\RolController',
	'aplicacion' => 'Security\AplicacionController',
	'modulo' => 'Security\ModuloController',
	'opcion' => 'Security\OpcionController',
	'permiso' => 'Security\PermisoController',
	'suscripcion' => 'Club\SuscriptionController',
	'beneficiario' => 'Club\BeneficiaryController',
	'entidad' => 'Club\EntityController',
	'especialidad' => 'Club\SpecialtyController',
	'especialista' => 'Club\SpecialistController',
	'servicio' => 'Club\ServiceController',
	'reporte' => 'Club\ReportController'
	//'password' => 'Auth\PasswordController',
	
]);

