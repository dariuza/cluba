<?php namespace App\Core\Club;

use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $table = 'clu_beneficiary';
	
	protected $fillable =['id','type_id','identification','names','surnames','relationship','movil_number','state','alert','price','civil_status','more','birthdate','birthplace','adress','city','email'];
			
}

