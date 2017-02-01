<?php namespace App\Core\Club;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $table = 'clu_entity';
	
	protected $fillable =['id','entity','identification','adress','description'];
			
}

