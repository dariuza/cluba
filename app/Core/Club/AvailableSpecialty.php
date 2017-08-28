<?php namespace App\Core\Club;

use Illuminate\Database\Eloquent\Model;

class AvailableSpecialty extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $table = 'clu_available_x_specialty';
	
	protected $fillable =['id','available_id','specialty_id'];
			
}

