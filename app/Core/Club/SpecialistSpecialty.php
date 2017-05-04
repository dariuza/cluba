<?php namespace App\Core\Club;

use Illuminate\Database\Eloquent\Model;

class SpecialistSpecialty extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $table = 'clu_specialist_x_specialty';
	
	protected $fillable =['id','rate_particular','rate_suscriptor','tiempo','active','specialist_id','specialty_id'];
			
}

