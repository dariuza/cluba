<?php namespace App\Core\Club;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $table = 'clu_city';
	
	protected $fillable =['id','code','city','department_id'];
			
}

