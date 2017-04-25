<?php namespace App\Core\Club;

use Illuminate\Database\Eloquent\Model;

class Subentity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $table = 'clu_subentity';
	
	protected $fillable =['id','sucursal_name','adress','phone1_contact','phone2_contact','email_contact','description','entity_id','active'];
			
}

