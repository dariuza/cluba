<?php namespace App\Core\Club;

use Illuminate\Database\Eloquent\Model;

class Specialist extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $table = 'clu_specialist';
	
	protected $fillable =['id','name','identification','phone1','phone2','email','name_assistant','phone1_assistant','phone2_assistant','email_assistant','description','entity_id'];
			
}

