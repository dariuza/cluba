<?php namespace App\Core\Club;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $table = 'clu_department';
	
	protected $fillable =['id','code','department'];
			
}

