<?php namespace App\Core\Club;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $table = 'clu_license';
	
	protected $fillable =['id','type','price','date','suscription_id'];
			
}

