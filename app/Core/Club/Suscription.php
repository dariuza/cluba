<?php namespace App\Core\Club;

use Illuminate\Database\Eloquent\Model;

class Suscription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $table = 'clu_suscription';
	
	protected $fillable = ['id','code','date_suscription','date_expiration','price','waytopay','pay_interval','fee','reason','observation','adviser_id','friend_id','state_id'];
			
}

