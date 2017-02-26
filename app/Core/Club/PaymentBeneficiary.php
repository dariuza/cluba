<?php namespace App\Core\Club;

use Illuminate\Database\Eloquent\Model;

class PaymentBeneficiary extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $table = 'clu_payment_beneficiary';
	
	protected $fillable =['id','date_payment','payment','beneficiary_id'];
			
}

