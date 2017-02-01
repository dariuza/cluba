<?php namespace App\Core\Club;

use Illuminate\Database\Eloquent\Model;

class Kardex extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $table = 'clu_kardex';
	
	protected $fillable =['id','seat','type_seat','nro_receipt','description','date_seat','suscription_id'];
			
}

