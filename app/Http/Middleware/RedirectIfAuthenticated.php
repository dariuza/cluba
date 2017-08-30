<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
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
	}
	
    public function handle($request, Closure $next, $guard = null)
    {    	   	
        if ($this->auth->guest())
        {
        	/*
        	 * Asignamos nombre de aplicación
        	 */
        	Session::put('app', env('APP_NAME','clubDamigos'));        	
        	Session::put('copy', env('APP_RIGTH','Club_de_Amigos'));
        	Session::put('mail', env('MAIL_USERNAME','info.clubDamigos@gmail.com'));
        	Session::put('icon', env('APP_ICON','icon.png'));
        	//return redirect()->route('login');//esto es una ruta		
        	return redirect()->to('auth/login');//esto es una direccion,return redirect()->to('ingreso'); 
        }
        
       return $next($request);
    }
}
