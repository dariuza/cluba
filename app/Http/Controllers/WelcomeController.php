<?php namespace App\Http\Controllers;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		/**Creación de informes simples**/
		
		//total agrupado por estados
		try {
			$moduledata['suscripciones_state']=\DB::table('clu_suscription')
			->select('state','alert', \DB::raw('count(*) as total'))
			->leftjoin('clu_state', 'clu_suscription.state_id', '=', 'clu_state.id')			
			->groupBy('state')
			->get();
		}catch (ModelNotFoundException $e) {
			$message = 'Problemas al hallar datos de '.$modulo.' de usuario';
			return Redirect::to('suscripcion/general')->with('error', $message);
		}
		
		//total agrupado por asesores
		try {
			$moduledata['suscripciones_advser']=\DB::table('clu_suscription')
			->select('names','surnames','identificacion', \DB::raw('count(*) as total'))
			->leftjoin('seg_user as uad', 'clu_suscription.adviser_id', '=', 'uad.id')
			->leftjoin('seg_user_profile as ad', 'uad.id', '=', 'ad.user_id')
			->groupBy('clu_suscription.adviser_id')
			->get();
		}catch (ModelNotFoundException $e) {
			$message = 'Problemas al hallar datos de '.$modulo.' de usuario';
			return Redirect::to('suscripcion/general')->with('error', $message);
		}
		
		return view('welcome',['modulo'=>$moduledata]);
		
	}

}
