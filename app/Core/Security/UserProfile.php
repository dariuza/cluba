<?php namespace App\Core\Security;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $table = 'seg_user_profile';
	
	protected $fillable = ['id','identificacion','type_id','names','surnames','birtdate','birthplace','sex','civil_status','adress','home','state','city','neighborhood','avatar','description','template','movil_number','fix_number','fix_number','date_start','code_adviser','zone','date_in','date_out','salary','profession','paymentadress','reference','reference_adress','reference_phone','location','user_id'];
}

