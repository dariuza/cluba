<?php namespace App\Core\Club;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $table = 'clu_service';
	
	protected $fillable =['id','city','price','identification_user','names_user','surnames_user','day','date_service','date_service_time','hour_start','duration','status','active','especialty_id','especialist_id','suscription_id'];
			
}

