<?php namespace App\Core\Club;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $table = 'clu_payment';
	
	protected $fillable =['id','date_payment','payment','n_receipt','suscription_id'];
			
}

