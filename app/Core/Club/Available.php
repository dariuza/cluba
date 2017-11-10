<?php namespace App\Core\Club;

use Illuminate\Database\Eloquent\Model;

class Available extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $table = 'clu_available';
	
	protected $fillable =['id','day','hour_start','hour_end','observations','active','subentity_id','specialist_id'];
			
}

