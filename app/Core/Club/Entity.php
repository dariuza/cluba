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
	
	protected $fillable =['id','business_name','nit','legal_representative','contact_representative','phone1_contact','phone2_contact','email_contact','description','active'];
			
}

